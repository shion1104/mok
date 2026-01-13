#!/bin/bash
set -e

# ダミー記事にカテゴリーを追加するスクリプト

echo "ダミー記事にカテゴリーを追加します..."
echo ""

# WP-CLIコマンドの設定
WP_CLI_CMD="docker compose exec -T wordpress php /tmp/wp-cli.phar"
if ! docker compose exec -T wordpress test -f /tmp/wp-cli.phar; then
  if ! docker compose exec -T wordpress which wp > /dev/null 2>&1; then
    echo "WP-CLIをダウンロード中..."
    docker compose exec -T wordpress php -r "copy('https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar', '/tmp/wp-cli.phar');"
    docker compose exec -T wordpress chmod +x /tmp/wp-cli.phar
  else
    WP_CLI_CMD="docker compose exec -T wordpress wp"
  fi
fi

# 既存のカテゴリーを取得
CATEGORIES=$(docker compose exec -T wordpress php /tmp/wp-cli.phar term list category --format=csv --fields=slug --allow-root 2>/dev/null | tail -n +2 || echo "")
if [ -z "$CATEGORIES" ]; then
  if docker compose exec -T wordpress which wp > /dev/null 2>&1; then
    CATEGORIES=$(docker compose exec -T wordpress wp term list category --format=csv --fields=slug --allow-root 2>/dev/null | tail -n +2 || echo "")
  fi
fi

CATEGORY_ARRAY=()
if [ ! -z "$CATEGORIES" ]; then
  while IFS= read -r line; do
    if [ ! -z "$line" ]; then
      CATEGORY_ARRAY+=("$line")
    fi
  done <<< "$CATEGORIES"
fi

if [ ${#CATEGORY_ARRAY[@]} -eq 0 ]; then
  echo "エラー: カテゴリーが見つかりませんでした"
  exit 1
fi

echo "利用可能なカテゴリー: ${CATEGORY_ARRAY[@]}"
echo ""

# ダミー記事のIDを取得
DUMMY_POSTS=$(docker compose exec -T wordpress php /tmp/wp-cli.phar post list --format=ids --post_title__like="ダミー記事" --allow-root)

if [ -z "$DUMMY_POSTS" ]; then
  echo "エラー: ダミー記事が見つかりませんでした"
  exit 1
fi

POST_IDS=($DUMMY_POSTS)
echo "対象記事数: ${#POST_IDS[@]}件"
echo ""

# 各記事にランダムにカテゴリーを追加
for post_id in "${POST_IDS[@]}"; do
  # 1つのカテゴリーをランダムに選択
  random_category=${CATEGORY_ARRAY[$RANDOM % ${#CATEGORY_ARRAY[@]}]}
  
  # カテゴリーを追加
  docker compose exec -T wordpress php /tmp/wp-cli.phar post term add $post_id category "$random_category" --allow-root > /dev/null 2>&1
  
  post_title=$(docker compose exec -T wordpress php /tmp/wp-cli.phar post get $post_id --field=title --allow-root)
  echo "✓ 記事 ID $post_id ($post_title) にカテゴリーを追加: $random_category"
done

echo ""
echo "完了しました！"
