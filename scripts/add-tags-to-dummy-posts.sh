#!/bin/bash
set -e

# ダミー記事にタグを追加するスクリプト

echo "ダミー記事にタグを追加します..."
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

# 既存のタグを取得
TAGS=$(docker compose exec -T wordpress php /tmp/wp-cli.phar term list post_tag --format=csv --fields=slug --allow-root | tail -n +2)
TAG_ARRAY=($TAGS)

if [ ${#TAG_ARRAY[@]} -eq 0 ]; then
  echo "エラー: タグが見つかりませんでした"
  exit 1
fi

echo "利用可能なタグ: ${TAG_ARRAY[@]}"
echo ""

# ダミー記事のIDを取得（最近作成された「ダミー記事」というタイトルを含む記事）
DUMMY_POSTS=$(docker compose exec -T wordpress php /tmp/wp-cli.phar post list --format=ids --post_title__like="ダミー記事" --allow-root)

if [ -z "$DUMMY_POSTS" ]; then
  echo "エラー: ダミー記事が見つかりませんでした"
  exit 1
fi

POST_IDS=($DUMMY_POSTS)
echo "対象記事数: ${#POST_IDS[@]}件"
echo ""

# 各記事にランダムにタグを追加
for post_id in "${POST_IDS[@]}"; do
  # 1-3個のタグをランダムに選択
  num_tags=$((RANDOM % 3 + 1))
  selected_tags=()
  
  # タグをランダムに選択（重複なし）
  while [ ${#selected_tags[@]} -lt $num_tags ]; do
    random_tag=${TAG_ARRAY[$RANDOM % ${#TAG_ARRAY[@]}]}
    # 重複チェック
    if [[ ! " ${selected_tags[@]} " =~ " ${random_tag} " ]]; then
      selected_tags+=($random_tag)
    fi
  done
  
  # タグを追加
  for tag in "${selected_tags[@]}"; do
    docker compose exec -T wordpress php /tmp/wp-cli.phar post term add $post_id post_tag "$tag" --allow-root > /dev/null 2>&1
  done
  
  post_title=$(docker compose exec -T wordpress php /tmp/wp-cli.phar post get $post_id --field=title --allow-root)
  echo "✓ 記事 ID $post_id ($post_title) にタグを追加: ${selected_tags[@]}"
done

echo ""
echo "完了しました！"
