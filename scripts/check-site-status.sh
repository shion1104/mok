#!/bin/bash

# サイトの状態を確認するスクリプト

SSH_HOST="nichicoma.sakura.ne.jp"
WP_PATH="/home/nichicoma/www/moriwp"

echo "=========================================="
echo "サイトの状態を確認中..."
echo "=========================================="
echo "接続先: ${SSH_HOST}"
echo "WordPressパス: ${WP_PATH}"
echo ""

echo "1. WordPressのバージョン確認..."
ssh "${SSH_HOST}" "cd ${WP_PATH} && wp core version --allow-root 2>&1"

echo ""
echo "2. テーマの状態確認..."
ssh "${SSH_HOST}" "cd ${WP_PATH} && wp theme list --allow-root 2>&1"

echo ""
echo "3. プラグインの状態確認..."
ssh "${SSH_HOST}" "cd ${WP_PATH} && wp plugin list --allow-root 2>&1"

echo ""
echo "4. Elementorプラグインの状態確認..."
ssh "${SSH_HOST}" "cd ${WP_PATH}/wp-content/plugins && ls -la | grep elementor"

echo ""
echo "5. elementor_checklistオプションの確認..."
ssh "${SSH_HOST}" "cd ${WP_PATH} && wp option get elementor_checklist --allow-root 2>&1 || echo '   ✓ オプションは削除されています（正常）'"

echo ""
echo "=========================================="
echo "確認完了"
echo "=========================================="
echo ""
echo "サイトURL: https://nichicoma.sakura.ne.jp/moriwp/"
