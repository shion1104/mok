#!/bin/bash

# 開発環境のエラーを確認するスクリプト

# 環境変数または引数から接続情報を取得
if [ ! -z "$1" ] && [ ! -z "$2" ]; then
    SSH_CONNECTION="$1"
    PROD_WP_PATH="$2"
elif [ ! -z "$DEV_SSH_CONNECTION" ] && [ ! -z "$DEV_WP_PATH" ]; then
    SSH_CONNECTION="$DEV_SSH_CONNECTION"
    PROD_WP_PATH="$DEV_WP_PATH"
else
    echo "エラー: SSH接続情報とWordPressパスが必要です"
    exit 1
fi

SSH_CONFIG_PATH="$HOME/.ssh/config"
SSH_CMD="ssh -F $SSH_CONFIG_PATH"

echo "=========================================="
echo "エラーの確認"
echo "=========================================="
echo "接続先: ${SSH_CONNECTION}"
echo "WordPressパス: ${PROD_WP_PATH}"
echo ""

echo "1. WordPressの状態を確認..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp core version --allow-root"

echo ""
echo "2. テーマの状態を確認..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp theme list --allow-root"

echo ""
echo "3. プラグインの状態を確認..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp plugin list --allow-root"

echo ""
echo "4. エラーログを確認..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && if [ -f wp-content/debug.log ]; then tail -30 wp-content/debug.log; else echo 'debug.logが見つかりません'; fi"

echo ""
echo "5. PHPエラーログを確認..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && tail -30 /var/log/php_errors.log 2>/dev/null || tail -30 /var/log/php-fpm/error.log 2>/dev/null || echo 'PHPエラーログが見つかりません'"

echo ""
echo "6. テーマファイルの構文チェック..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH}/wp-content/themes/swell_child && php -l functions.php && php -l page-top.php && php -l page-mitus.php"

echo ""
echo "=========================================="
echo "確認完了"
echo "=========================================="
