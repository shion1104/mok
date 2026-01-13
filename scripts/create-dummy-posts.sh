#!/bin/bash
set -e

# ダミー記事作成スクリプト
# 使用方法: ./scripts/create-dummy-posts.sh [記事数] [作成者ID]

POST_COUNT=${1:-10}  # デフォルト10記事
AUTHOR_ID=${2:-1}    # デフォルトはID 1（管理者）

echo "ダミー記事を ${POST_COUNT} 件作成します（作成者ID: ${AUTHOR_ID}）"
echo ""

# WP-CLIのインストール確認とセットアップ
echo "WP-CLIの確認中..."
WP_CLI_CMD="docker compose exec -T wordpress wp"
if ! docker compose exec -T wordpress which wp > /dev/null 2>&1; then
  echo "WP-CLIが見つかりません。一時的にダウンロードして使用します..."
  WP_CLI_CMD="docker compose exec -T wordpress php /tmp/wp-cli.phar"
  
  # WP-CLIをダウンロード
  if ! docker compose exec -T wordpress test -f /tmp/wp-cli.phar; then
    echo "WP-CLIをダウンロード中..."
    docker compose exec -T wordpress php -r "copy('https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar', '/tmp/wp-cli.phar');"
    docker compose exec -T wordpress chmod +x /tmp/wp-cli.phar
  fi
fi

# 既存のカテゴリーを取得
echo "カテゴリーを取得中..."
CATEGORIES=$(docker compose exec -T wordpress php /tmp/wp-cli.phar term list category --format=csv --fields=slug --allow-root 2>/dev/null | tail -n +2 || echo "")
if [ -z "$CATEGORIES" ]; then
  # WP-CLIがインストールされていない場合のフォールバック
  if docker compose exec -T wordpress which wp > /dev/null 2>&1; then
    CATEGORIES=$(docker compose exec -T wordpress wp term list category --format=csv --fields=slug --allow-root 2>/dev/null | tail -n +2 || echo "")
  fi
fi

# カテゴリー配列を作成
CATEGORY_ARRAY=()
if [ ! -z "$CATEGORIES" ]; then
  while IFS= read -r line; do
    if [ ! -z "$line" ]; then
      CATEGORY_ARRAY+=("$line")
    fi
  done <<< "$CATEGORIES"
fi

if [ ${#CATEGORY_ARRAY[@]} -gt 0 ]; then
  echo "利用可能なカテゴリー: ${CATEGORY_ARRAY[@]}"
else
  echo "警告: カテゴリーが見つかりませんでした"
fi
echo ""

# Dockerコンテナ内でWP-CLIを実行
for i in $(seq 1 $POST_COUNT); do
  echo "[$i/$POST_COUNT] 記事を作成中..."
  
  # 記事を作成
  POST_ID=$($WP_CLI_CMD post create \
    --post_title="ダミー記事 $i" \
    --post_content="これはテスト用のダミー記事です。記事番号: $i

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

この記事は自動生成されたテスト用のコンテンツです。" \
    --post_status=publish \
    --post_author=$AUTHOR_ID \
    --allow-root \
    --porcelain 2>/dev/null)
  
  if [ $? -eq 0 ] && [ ! -z "$POST_ID" ]; then
    # カテゴリーを追加（ランダムに1つ選択）
    if [ ${#CATEGORY_ARRAY[@]} -gt 0 ]; then
      RANDOM_CATEGORY=${CATEGORY_ARRAY[$RANDOM % ${#CATEGORY_ARRAY[@]}]}
      $WP_CLI_CMD post term add $POST_ID category "$RANDOM_CATEGORY" --allow-root > /dev/null 2>&1
      echo "✓ 記事 $i (ID: $POST_ID) を作成しました [カテゴリー: $RANDOM_CATEGORY]"
    else
      echo "✓ 記事 $i (ID: $POST_ID) を作成しました"
    fi
  else
    echo "✗ 記事 $i の作成に失敗しました"
  fi
done

echo ""
echo "完了しました！"
echo ""
echo "作成された記事を確認するには:"
if docker compose exec -T wordpress which wp > /dev/null 2>&1; then
  echo "  docker compose exec wordpress wp post list"
else
  echo "  docker compose exec wordpress php /tmp/wp-cli.phar post list"
fi
