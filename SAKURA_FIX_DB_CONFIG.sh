#!/bin/bash
# wp-config.phpのデータベース設定を修正

cd ~/www

# バックアップ
cp wp-config.php wp-config.php.backup.db.$(date +%Y%m%d_%H%M%S)

# データベース設定を修正
php -r "
\$file = 'wp-config.php';
\$content = file_get_contents(\$file);

// データベース設定を置き換え
\$content = preg_replace(
    \"/define\( 'DB_NAME', getenv_docker\('WORDPRESS_DB_NAME', '[^']*'\) \);/\",
    \"define( 'DB_NAME', 'nichicoma_moriwp' );\",
    \$content
);

\$content = preg_replace(
    \"/define\( 'DB_USER', getenv_docker\('WORDPRESS_DB_USER', '[^']*'\) \);/\",
    \"define( 'DB_USER', 'nichicoma_moriwp' );\",
    \$content
);

\$content = preg_replace(
    \"/define\( 'DB_PASSWORD', getenv_docker\('WORDPRESS_DB_PASSWORD', '[^']*'\) \);/\",
    \"define( 'DB_PASSWORD', 'bananamilk1104' );\",
    \$content
);

\$content = preg_replace(
    \"/define\( 'DB_HOST', getenv_docker\('WORDPRESS_DB_HOST', '[^']*'\) \);/\",
    \"define( 'DB_HOST', 'mysql80.nichicoma.sakura.ne.jp' );\",
    \$content
);

file_put_contents(\$file, \$content);
echo \"データベース設定を修正しました！\n\";
"

echo ""
echo "確認:"
grep -E "DB_NAME|DB_USER|DB_PASSWORD|DB_HOST" wp-config.php
