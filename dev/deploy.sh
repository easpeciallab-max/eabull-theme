#!/usr/bin/env bash
# ส่งธีมขึ้น eabull.com: push main → รอ GitHub เตรียม zip รุ่นใหม่ → ส่ง webhook ของ WP Pusher ซ้ำ → เทียบไฟล์บนเว็บกับในเครื่อง
#
# เหตุที่ต้องส่งซ้ำ: GitHub ยิง webhook ทันทีหลัง push แต่ zip ของ branch ยังเป็นรุ่นก่อนหน้าอยู่ไม่กี่วินาที
# WP Pusher จึงอาจได้ไฟล์ชุดเก่า (ทดสอบ 2 ต.ค. 2026) · การส่งซ้ำหลัง 20 วินาทีได้รุ่นล่าสุดเสมอ
#
# ใช้: bash dev/deploy.sh            (จาก root ของ repo · ใช้สิทธิ์ของ gh ที่ล็อกอินไว้ ไม่ต้องใช้รหัสของ WP Pusher)
set -euo pipefail
cd "$(dirname "$0")/.."

REPO="easpeciallab-max/eabull-theme"
SITE="https://eabull.com/wp-content/themes/eabull"

if [ "$(git rev-parse --abbrev-ref HEAD)" != "main" ]; then
	echo "อยู่บน branch $(git rev-parse --abbrev-ref HEAD) · deploy ได้เฉพาะ main" >&2
	exit 1
fi

BEFORE=$(git rev-parse origin/main 2>/dev/null || echo "")
git push origin main
AFTER=$(git rev-parse HEAD)

HOOK=$(gh api "repos/$REPO/hooks" --jq '[.[] | select(.config.url | test("wppusher-hook"))][0].id')
if [ -z "$HOOK" ] || [ "$HOOK" = "null" ]; then
	echo "ไม่พบ webhook ของ WP Pusher ใน $REPO" >&2
	exit 1
fi

echo "รอ GitHub เตรียม zip รุ่นใหม่ 20 วินาที..."
sleep 20
DELIVERY=$(gh api "repos/$REPO/hooks/$HOOK/deliveries" --jq '[.[] | select(.event == "push")][0].id')
gh api -X POST "repos/$REPO/hooks/$HOOK/deliveries/$DELIVERY/attempts" > /dev/null
echo "ส่ง webhook ซ้ำแล้ว · รอ WP Pusher ติดตั้ง"
sleep 8

# เทียบไฟล์ static ที่เปลี่ยนใน push นี้ (PHP อ่านจากเว็บไม่ได้ จึงเทียบได้เฉพาะ css/js/รูป/ไฟล์ดาวน์โหลด)
if [ -n "$BEFORE" ] && [ "$BEFORE" != "$AFTER" ]; then
	FILES=$(git diff --name-only "$BEFORE" "$AFTER" -- eabull | grep -E '\.(css|js|webp|png|jpe?g|svg|zip|txt)$' || true)
else
	FILES="eabull/style.css"
fi
[ -z "$FILES" ] && FILES="eabull/style.css"

FAIL=0
for f in $FILES; do
	[ -f "$f" ] || continue
	rel="${f#eabull/}"
	local_hash=$(md5sum < "$f" | cut -d' ' -f1)
	live_hash=$(curl -s -H "Cache-Control: no-cache" "$SITE/$rel?nc=$(date +%s%N)" | md5sum | cut -d' ' -f1)
	if [ "$local_hash" = "$live_hash" ]; then
		echo "  ✓ $rel"
	else
		echo "  ✗ $rel (บนเว็บยังไม่ตรง)"
		FAIL=1
	fi
done

if [ "$FAIL" = "1" ]; then
	echo "บางไฟล์ยังไม่ตรง · รันสคริปต์นี้อีกครั้ง หรือกด WP Pusher > Themes > Update theme" >&2
	exit 1
fi
echo "deploy เสร็จ: $(git log -1 --format='%h %s')"
