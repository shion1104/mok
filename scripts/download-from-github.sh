#!/bin/bash

# GitHubリポジトリからファイルをダウンロードしてローカル環境を更新するスクリプト
# 使用方法: ./scripts/download-from-github.sh [ファイルパス1] [ファイルパス2] ...
# 例: ./scripts/download-from-github.sh style.css functions.php

# GitHubリポジトリ情報
GITHUB_USER="nichicoma"
GITHUB_REPO="lps"
GITHUB_BRANCH="main"
GITHUB_BASE_PATH="app/gtnet/1"

# GitHub認証トークン（プライベートリポジトリの場合）
# 環境変数 GITHUB_TOKEN を使用、またはスクリプト引数で指定可能
if [ ! -z "$GITHUB_TOKEN" ]; then
    AUTH_HEADER="Authorization: token ${GITHUB_TOKEN}"
    CURL_AUTH_OPT="-H '${AUTH_HEADER}'"
elif [ ! -z "$1" ] && [[ "$1" == "token:"* ]]; then
    GITHUB_TOKEN="${1#token:}"
    AUTH_HEADER="Authorization: token ${GITHUB_TOKEN}"
    CURL_AUTH_OPT="-H '${AUTH_HEADER}'"
    shift # トークンを引数から削除
else
    CURL_AUTH_OPT=""
fi

# ローカルのWordPressテーマパス
LOCAL_THEME_PATH="wordpress/wp-content/themes/swell_child"

# 色の定義
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=========================================="
echo "GitHubからファイルをダウンロードして更新"
echo "=========================================="
echo "リポジトリ: ${GITHUB_USER}/${GITHUB_REPO}"
echo "ブランチ: ${GITHUB_BRANCH}"
echo "パス: ${GITHUB_BASE_PATH}"
echo ""

# 引数チェック
if [ $# -eq 0 ]; then
    echo -e "${RED}エラー: ダウンロードするファイルパスを指定してください${NC}"
    echo ""
    echo "使用方法:"
    echo "  $0 [ファイルパス1] [ファイルパス2] ..."
    echo ""
    echo "例:"
    echo "  $0 style.css"
    echo "  $0 assets/css/top-page.css assets/js/top-page.js"
    echo "  $0 functions.php style.css assets/css/global-animations.css"
    echo ""
    exit 1
fi

# バックアップディレクトリを作成
BACKUP_DIR="exports/github-backup-$(date +%Y%m%d_%H%M%S)"
mkdir -p "${BACKUP_DIR}"

echo "バックアップディレクトリ: ${BACKUP_DIR}"
echo ""

# ダウンロード成功/失敗のカウント
SUCCESS_COUNT=0
FAILED_COUNT=0
FAILED_FILES=()

# 各ファイルをダウンロード
for FILE_PATH in "$@"; do
    # ファイル名を取得（パスの最後の部分）
    FILE_NAME=$(basename "${FILE_PATH}")
    
    # ローカルの保存先パスを決定
    # ファイルパスにディレクトリが含まれている場合、その構造を維持
    if [[ "${FILE_PATH}" == *"/"* ]]; then
        # ディレクトリ構造を維持
        LOCAL_DIR="${LOCAL_THEME_PATH}/$(dirname "${FILE_PATH}")"
        LOCAL_FILE="${LOCAL_THEME_PATH}/${FILE_PATH}"
    else
        # ルートファイル（例: style.css, functions.php）
        LOCAL_DIR="${LOCAL_THEME_PATH}"
        LOCAL_FILE="${LOCAL_THEME_PATH}/${FILE_PATH}"
    fi
    
    # GitHubのraw URLを構築
    GITHUB_URL="https://raw.githubusercontent.com/${GITHUB_USER}/${GITHUB_REPO}/${GITHUB_BRANCH}/${GITHUB_BASE_PATH}/${FILE_PATH}"
    
    echo "----------------------------------------"
    echo "ファイル: ${FILE_PATH}"
    echo "GitHub URL: ${GITHUB_URL}"
    echo "ローカル保存先: ${LOCAL_FILE}"
    
    # 既存ファイルのバックアップ
    if [ -f "${LOCAL_FILE}" ]; then
        BACKUP_FILE="${BACKUP_DIR}/${FILE_PATH}"
        mkdir -p "$(dirname "${BACKUP_FILE}")"
        cp "${LOCAL_FILE}" "${BACKUP_FILE}"
        echo "✓ 既存ファイルをバックアップしました: ${BACKUP_FILE}"
    fi
    
    # ローカルディレクトリを作成（存在しない場合）
    mkdir -p "${LOCAL_DIR}"
    
    # GitHubからファイルをダウンロード
    if [ ! -z "${CURL_AUTH_OPT}" ]; then
        # 認証付きでダウンロード（プライベートリポジトリ用）
        HTTP_CODE=$(eval "curl -s ${CURL_AUTH_OPT} -o '${LOCAL_FILE}' -w '%{http_code}' -L '${GITHUB_URL}'" 2>/dev/null)
    else
        # 公開リポジトリとしてダウンロード
        HTTP_CODE=$(curl -s -o "${LOCAL_FILE}" -w "%{http_code}" -L "${GITHUB_URL}" 2>/dev/null)
    fi
    
    if [ "${HTTP_CODE}" = "200" ]; then
        # ファイルサイズを確認（空でないことを確認）
        FILE_SIZE=$(stat -f%z "${LOCAL_FILE}" 2>/dev/null || stat -c%s "${LOCAL_FILE}" 2>/dev/null || echo "0")
        
        if [ "${FILE_SIZE}" -gt 0 ]; then
            echo -e "${GREEN}✓ ダウンロード成功 (${FILE_SIZE} bytes)${NC}"
            SUCCESS_COUNT=$((SUCCESS_COUNT + 1))
        else
            echo -e "${YELLOW}⚠ ファイルが空です${NC}"
            FAILED_COUNT=$((FAILED_COUNT + 1))
            FAILED_FILES+=("${FILE_PATH}")
            # 空ファイルを削除
            rm -f "${LOCAL_FILE}"
        fi
    else
        echo -e "${RED}✗ ダウンロード失敗 (HTTP ${HTTP_CODE})${NC}"
        FAILED_COUNT=$((FAILED_COUNT + 1))
        FAILED_FILES+=("${FILE_PATH}")
        # 失敗したファイルを削除
        rm -f "${LOCAL_FILE}"
        
        # バックアップから復元（存在する場合）
        BACKUP_FILE="${BACKUP_DIR}/${FILE_PATH}"
        if [ -f "${BACKUP_FILE}" ]; then
            cp "${BACKUP_FILE}" "${LOCAL_FILE}"
            echo "  既存ファイルを復元しました"
        fi
    fi
    echo ""
done

# 結果サマリー
echo "=========================================="
echo "ダウンロード結果"
echo "=========================================="
echo -e "${GREEN}成功: ${SUCCESS_COUNT} ファイル${NC}"
echo -e "${RED}失敗: ${FAILED_COUNT} ファイル${NC}"

if [ ${FAILED_COUNT} -gt 0 ]; then
    echo ""
    echo "失敗したファイル:"
    for FAILED_FILE in "${FAILED_FILES[@]}"; do
        echo "  - ${FAILED_FILE}"
    done
    echo ""
    echo "考えられる原因:"
    echo "  1. ファイルパスが間違っている"
    echo "  2. GitHubリポジトリがプライベート（認証が必要）"
    echo "  3. ファイルが存在しない"
    echo "  4. ネットワークエラー"
fi

if [ ${SUCCESS_COUNT} -gt 0 ]; then
    echo ""
    echo "バックアップ場所: ${BACKUP_DIR}"
    echo ""
    echo "⚠️  ファイルが更新されました。変更内容を確認してください。"
fi

echo ""
