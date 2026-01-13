#!/bin/bash

# 開発環境のHOMEページ404エラーを修正するスクリプト

SSH_HOST="nichicoma.sakura.ne.jp"
WP_PATH="/home/nichicoma/www/moriwp"

echo "=========================================="
echo "HOMEページ404エラーを修正"
echo "=========================================="
echo "接続先: ${SSH_HOST}"
echo "WordPressパス: ${WP_PATH}"
echo ""

echo "以下のコマンドを開発環境で実行してください:"
echo ""
echo "ssh ${SSH_HOST}"
echo "cd ${WP_PATH}"
echo ""
echo "# 1. 現在の設定を確認"
echo "echo '=== 現在の設定 ==='"
echo "wp option get siteurl --allow-root"
echo "wp option get home --allow-root"
echo "wp option get show_on_front --allow-root"
echo "wp option get page_on_front --allow-root"
echo "wp option get permalink_structure --allow-root"
echo ""
echo "# 2. TOPページの確認"
echo "wp post list --post_type=page --name=top --format=table --fields=ID,post_title,post_name,post_status --allow-root"
echo ""
echo "# 3. サイトURL設定を修正"
echo "wp option update siteurl 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root"
echo "wp option update home 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root"
echo ""
echo "# 4. TOPページIDを取得してフロントページ設定"
echo "TOP_ID=\$(wp post list --post_type=page --name=top --format=ids --allow-root | head -1 | tr -d '\r\n')"
echo "echo \"TOPページID: \$TOP_ID\""
echo "if [ -n \"\$TOP_ID\" ] && [ \"\$TOP_ID\" != \"\" ]; then"
echo "    wp option update show_on_front 'page' --allow-root"
echo "    wp option update page_on_front \$TOP_ID --allow-root"
echo "    echo \"✓ フロントページ設定完了（ページID: \$TOP_ID）\""
echo "fi"
echo ""
echo "# 5. パーマリンク設定を確認・更新"
echo "wp option update permalink_structure '/%postname%/' --allow-root"
echo ""
echo "# 6. .htaccessファイルの確認"
echo "ls -la .htaccess"
echo "cat .htaccess 2>/dev/null | head -20"
echo ""
echo "# 7. リライトルールをフラッシュ"
echo "wp rewrite flush --hard --allow-root"
echo ""
echo "# 8. 最終確認"
echo "echo '=== 修正後の設定 ==='"
echo "wp option get siteurl --allow-root"
echo "wp option get page_on_front --allow-root"
echo "wp post get \$(wp option get page_on_front --allow-root) --field=post_name --allow-root"
echo ""

echo "=========================================="
echo ""
echo "ワンライナーで実行（推奨）:"
echo ""
cat << 'SCRIPT_END'
ssh nichicoma.sakura.ne.jp "cd /home/nichicoma/www/moriwp && \
wp option update siteurl 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root && \
wp option update home 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root && \
TOP_ID=\$(wp post list --post_type=page --name=top --format=ids --allow-root | head -1 | tr -d '\r\n') && \
if [ -n \"\$TOP_ID\" ] && [ \"\$TOP_ID\" != \"\" ]; then \
    wp option update show_on_front 'page' --allow-root && \
    wp option update page_on_front \$TOP_ID --allow-root && \
    echo \"✓ フロントページ設定完了（ページID: \$TOP_ID）\"; \
fi && \
wp option update permalink_structure '/%postname%/' --allow-root && \
wp rewrite flush --hard --allow-root && \
echo '✓ 設定完了'"
SCRIPT_END
