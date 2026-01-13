#!/bin/bash

# 開発環境のWordPressアクセス権を確認するスクリプト

SSH_HOST="nichicoma.sakura.ne.jp"
WP_PATH="/home/nichicoma/www/moriwp"

echo "=========================================="
echo "開発環境のWordPressアクセス権確認"
echo "=========================================="
echo "接続先: ${SSH_HOST}"
echo "WordPressパス: ${WP_PATH}"
echo ""

# 1. SSH接続確認
echo "1. SSH接続確認..."
ssh -F ~/.ssh/config sakura "echo 'SSH接続成功'" 2>&1 && echo "   ✓ SSH接続成功" || {
    echo "   ✗ SSH接続失敗"
    exit 1
}
echo ""

# 2. WordPressファイルの存在確認
echo "2. WordPressファイルの存在確認..."
ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && test -f wp-admin/index.php && echo '   ✓ wp-admin/index.php 存在' || echo '   ✗ wp-admin/index.php 不存在'" 2>&1
ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && test -f wp-config.php && echo '   ✓ wp-config.php 存在' || echo '   ✗ wp-config.php 不存在'" 2>&1
echo ""

# 3. WordPressファイルの権限確認
echo "3. WordPressファイルの権限確認..."
ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && ls -la wp-admin/index.php wp-config.php 2>&1" | head -3
echo ""

# 4. WordPressのバージョン確認
echo "4. WordPressのバージョン確認..."
WP_VERSION=$(ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && wp core version --allow-root 2>&1" | head -1)
if [ -n "$WP_VERSION" ]; then
    echo "   ✓ WordPressバージョン: ${WP_VERSION}"
else
    echo "   ✗ WordPressのバージョン取得に失敗"
fi
echo ""

# 5. サイトURL設定確認
echo "5. サイトURL設定確認..."
SITEURL=$(ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && wp option get siteurl --allow-root 2>&1" | head -1)
HOMEURL=$(ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && wp option get home --allow-root 2>&1" | head -1)
echo "   siteurl: ${SITEURL}"
echo "   home: ${HOMEURL}"
echo ""

# 6. ユーザー一覧確認
echo "6. WordPressユーザー一覧確認..."
echo ""
ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && wp user list --allow-root --format=table --fields=ID,user_login,user_email,roles 2>&1" 2>&1 | head -15
echo ""

# 7. 管理者ユーザーの確認
echo "7. 管理者ユーザーの確認..."
ADMIN_USERS=$(ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && wp user list --role=administrator --format=table --fields=ID,user_login,user_email --allow-root 2>&1" 2>&1 | grep -v "ID")
if [ -n "$ADMIN_USERS" ]; then
    echo "   管理者ユーザー:"
    echo "$ADMIN_USERS" | head -5
else
    echo "   ⚠ 管理者ユーザーが見つかりません"
fi
echo ""

# 8. データベース接続確認
echo "8. データベース接続確認..."
DB_CHECK=$(ssh -F ~/.ssh/config sakura "cd ${WP_PATH} && wp db check --allow-root 2>&1" | head -3)
if echo "$DB_CHECK" | grep -q "Success"; then
    echo "   ✓ データベース接続正常"
else
    echo "   ⚠ データベース接続に問題がある可能性があります"
    echo "$DB_CHECK" | head -3
fi
echo ""

# 9. アクセス可能性の確認
echo "9. アクセス可能性の確認..."
echo "   トップページ: https://nichicoma.sakura.ne.jp/moriwp/"
echo "   管理画面: https://nichicoma.sakura.ne.jp/moriwp/wp-admin/"
echo ""
echo "   ブラウザでアクセスして確認してください。"
echo ""

echo "=========================================="
echo "確認完了"
echo "=========================================="
