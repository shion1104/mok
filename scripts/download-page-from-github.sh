#!/bin/bash

# GitHubリポジトリからpage.tsxと関連ファイルをダウンロードしてWordPressテーマに変換
# 使用方法: ./scripts/download-page-from-github.sh [GITHUB_TOKEN]

GITHUB_USER="nichicoma"
GITHUB_REPO="lps"
GITHUB_BRANCH="main"
GITHUB_BASE_PATH="app/gtnet/1"

LOCAL_THEME_PATH="wordpress/wp-content/themes/swell_child"
BACKUP_DIR="exports/github-$(date +%Y%m%d_%H%M%S)"
mkdir -p "${BACKUP_DIR}"

# GitHub認証トークン
if [ ! -z "$1" ]; then
    GITHUB_TOKEN="$1"
    AUTH_HEADER="Authorization: token ${GITHUB_TOKEN}"
    CURL_AUTH="-H '${AUTH_HEADER}'"
else
    CURL_AUTH=""
fi

echo "=========================================="
echo "GitHubからpage.tsxをダウンロード"
echo "=========================================="
echo "リポジトリ: ${GITHUB_USER}/${GITHUB_REPO}"
echo "パス: ${GITHUB_BASE_PATH}"
echo ""

# page.tsxをダウンロード
PAGE_TSX_URL="https://raw.githubusercontent.com/${GITHUB_USER}/${GITHUB_REPO}/${GITHUB_BRANCH}/${GITHUB_BASE_PATH}/page.tsx"
TEMP_PAGE_TSX="${BACKUP_DIR}/page.tsx"

echo "page.tsxをダウンロード中..."
if [ ! -z "${CURL_AUTH}" ]; then
    HTTP_CODE=$(eval "curl -s ${CURL_AUTH} -o '${TEMP_PAGE_TSX}' -w '%{http_code}' -L '${PAGE_TSX_URL}'" 2>/dev/null)
else
    HTTP_CODE=$(curl -s -o "${TEMP_PAGE_TSX}" -w "%{http_code}" -L "${PAGE_TSX_URL}" 2>/dev/null)
fi

if [ "${HTTP_CODE}" = "200" ] && [ -s "${TEMP_PAGE_TSX}" ]; then
    echo "✓ page.tsxをダウンロードしました"
    echo ""
    echo "ファイルの内容（最初の100行）:"
    head -100 "${TEMP_PAGE_TSX}"
    echo ""
    echo "..."
    echo ""
    echo "ファイルサイズ: $(stat -f%z "${TEMP_PAGE_TSX}" 2>/dev/null || stat -c%s "${TEMP_PAGE_TSX}" 2>/dev/null) bytes"
    echo "保存先: ${TEMP_PAGE_TSX}"
else
    echo "✗ page.tsxのダウンロードに失敗しました (HTTP ${HTTP_CODE})"
    echo ""
    echo "考えられる原因:"
    echo "  1. リポジトリがプライベート（認証トークンが必要）"
    echo "  2. ファイルパスが間違っている"
    echo "  3. ブランチ名が間違っている"
    echo ""
    echo "認証トークンを指定して再実行:"
    echo "  $0 <GITHUB_TOKEN>"
    exit 1
fi

# 関連ファイルもダウンロードを試みる
echo ""
echo "関連ファイルを確認中..."

RELATED_FILES=(
    "components/Header.tsx"
    "components/Carousel.tsx"
    "components/Sidebar.tsx"
    "components/Footer.tsx"
    "components/NewsFeed.tsx"
    "components/BusinessSection.tsx"
)

for REL_FILE in "${RELATED_FILES[@]}"; do
    REL_URL="https://raw.githubusercontent.com/${GITHUB_USER}/${GITHUB_REPO}/${GITHUB_BRANCH}/${GITHUB_BASE_PATH}/${REL_FILE}"
    REL_DEST="${BACKUP_DIR}/${REL_FILE}"
    mkdir -p "$(dirname "${REL_DEST}")"
    
    if [ ! -z "${CURL_AUTH}" ]; then
        HTTP_CODE=$(eval "curl -s ${CURL_AUTH} -o '${REL_DEST}' -w '%{http_code}' -L '${REL_URL}'" 2>/dev/null)
    else
        HTTP_CODE=$(curl -s -o "${REL_DEST}" -w "%{http_code}" -L "${REL_URL}" 2>/dev/null)
    fi
    
    if [ "${HTTP_CODE}" = "200" ] && [ -s "${REL_DEST}" ]; then
        echo "✓ ${REL_FILE}をダウンロードしました"
    else
        echo "  ${REL_FILE}は見つかりませんでした"
    fi
done

echo ""
echo "=========================================="
echo "ダウンロード完了"
echo "=========================================="
echo "ダウンロードしたファイル:"
echo "  ${BACKUP_DIR}/"
ls -lh "${BACKUP_DIR}" | grep -v "^total" | awk '{print "    " $9 " (" $5 ")"}'
echo ""
echo "次のステップ:"
echo "  1. ダウンロードしたファイルを確認"
echo "  2. page.tsxの内容を基にWordPressテーマを実装"
echo "  3. コンポーネントをPHPテンプレートに変換"
