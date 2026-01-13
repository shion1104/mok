#!/bin/bash

# ローカル環境から開発環境へ、記事以外の全てのデータを同期
# 含まれる内容: テーマファイル、カスタマイズ、ユーザー情報、コメント、設定、カテゴリー・タグ、JSON/CSS/HTMLファイル

# 環境変数または引数から接続情報を取得
if [ ! -z "$1" ] && [ ! -z "$2" ]; then
    SSH_CONNECTION="$1"
    PROD_WP_PATH="$2"
elif [ ! -z "$DEV_SSH_CONNECTION" ] && [ ! -z "$DEV_WP_PATH" ]; then
    SSH_CONNECTION="$DEV_SSH_CONNECTION"
    PROD_WP_PATH="$DEV_WP_PATH"
else
    echo "エラー: SSH接続情報とWordPressパスが必要です"
    echo "使用方法: $0 [SSH接続情報] [WordPressパス]"
    echo "または環境変数: export DEV_SSH_CONNECTION=... export DEV_WP_PATH=..."
    exit 1
fi

echo "=========================================="
echo "全データ同期（記事以外）を開発環境に実行"
echo "=========================================="
echo "接続先: ${SSH_CONNECTION}"
echo "WordPressパス: ${PROD_WP_PATH}"
echo ""
echo "⚠️  投稿記事データは除外されます"
echo "⚠️  固定ページは移行されます"
echo "⚠️  ユーザーとコメントは既存データとマージされます"
echo ""

# SSH configのパス
SSH_CONFIG_PATH="$HOME/.ssh/config"
SSH_CMD="ssh -F $SSH_CONFIG_PATH"
SCP_CMD="scp -F $SSH_CONFIG_PATH"
RSYNC_SSH_CMD="ssh -F $SSH_CONFIG_PATH"

# ローカルWP-CLI
LOCAL_WP_CLI="docker compose exec -T wordpress wp --allow-root"

# ============================================
# 1. テーマファイル（CSS、JS、HTML、JSON含む）をコピー
# ============================================
echo "=========================================="
echo "1. テーマファイルをコピー中..."
echo "=========================================="

rsync -avz -e "${RSYNC_SSH_CMD}" --delete \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='.DS_Store' \
    --exclude='*.log' \
    wordpress/wp-content/themes/swell_child/ \
    "${SSH_CONNECTION}:${PROD_WP_PATH}/wp-content/themes/swell_child/"

echo "✓ テーマファイル（CSS、JavaScript、HTML、JSON含む）をコピーしました"
echo ""

# ============================================
# 2. カスタマイザー設定をコピー
# ============================================
echo "=========================================="
echo "2. カスタマイザー設定をコピー中..."
echo "=========================================="

CUSTOMIZER_DATA=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'theme_mods_swell_child';" -N -r 2>/dev/null | head -1)

if [ -n "$CUSTOMIZER_DATA" ]; then
    TEMP_CUSTOMIZER=$(mktemp)
    echo -n "$CUSTOMIZER_DATA" > "${TEMP_CUSTOMIZER}"
    
    cat > /tmp/update-customizer.php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
$serialized_data = file_get_contents('php://stdin');
if (empty($serialized_data)) exit(1);
global $wpdb;
$table_name = $wpdb->prefix . 'options';
$option_name = 'theme_mods_swell_child';
$result = $wpdb->query($wpdb->prepare(
    "UPDATE {$table_name} SET option_value = %s WHERE option_name = %s",
    $serialized_data, $option_name
));
if ($result !== false) {
    echo "SUCCESS\n";
    exit(0);
} else {
    echo "FAILED\n";
    exit(1);
}
PHP_SCRIPT

    ${SCP_CMD} /tmp/update-customizer.php "${SSH_CONNECTION}:${PROD_WP_PATH}/update-customizer.php" 2>/dev/null
    cat "${TEMP_CUSTOMIZER}" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php update-customizer.php" 2>&1 | grep -q "SUCCESS" && echo "✓ カスタマイザー設定をコピーしました" || echo "⚠ カスタマイザー設定の更新に失敗しました"
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f update-customizer.php" 2>/dev/null || true
    rm -f "${TEMP_CUSTOMIZER}" /tmp/update-customizer.php
else
    echo "⚠ カスタマイザー設定が見つかりません"
fi
echo ""

# ============================================
# 3. ユーザー情報をコピー
# ============================================
echo "=========================================="
echo "3. ユーザー情報をコピー中..."
echo "=========================================="

TEMP_USERS=$(mktemp)
${LOCAL_WP_CLI} user list --format=json > "${TEMP_USERS}" 2>/dev/null || echo "[]" > "${TEMP_USERS}"

if [ -s "${TEMP_USERS}" ]; then
    cat > /tmp/import-users.php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
$users_json = file_get_contents('php://stdin');
$users = json_decode($users_json, true);
if (!is_array($users)) exit(1);
$imported = 0;
foreach ($users as $user_data) {
    $user_login = $user_data['user_login'];
    $existing_user = get_user_by('login', $user_login);
    if ($existing_user) {
        echo "ユーザー既存: {$user_login}\n";
        continue;
    }
    $user_email = $user_data['user_email'];
    $user_pass = wp_generate_password(20);
    $user_id = wp_create_user($user_login, $user_pass, $user_email);
    if (!is_wp_error($user_id)) {
        $user = get_user_by('id', $user_id);
        if ($user) {
            $user->set_role($user_data['roles'][0] ?? 'subscriber');
            echo "ユーザー作成: {$user_login}\n";
            $imported++;
        }
    }
}
echo "合計: {$imported}件のユーザーをインポートしました\n";
PHP_SCRIPT

    ${SCP_CMD} /tmp/import-users.php "${SSH_CONNECTION}:${PROD_WP_PATH}/import-users.php" 2>/dev/null
    cat "${TEMP_USERS}" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php import-users.php" 2>&1 | head -20
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f import-users.php" 2>/dev/null || true
    rm -f /tmp/import-users.php
else
    echo "⚠ ユーザー情報が見つかりません"
fi
rm -f "${TEMP_USERS}"
echo ""

# ============================================
# 4. 固定ページをコピー
# ============================================
echo "=========================================="
echo "4. 固定ページをコピー中..."
echo "=========================================="

TEMP_PAGES=$(mktemp)
# データベースから直接取得（公開済みと下書きのみ）
docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT ID, post_title, post_name, post_content, post_excerpt, post_status, menu_order, post_parent FROM wp_posts WHERE post_type = 'page' AND post_status IN ('publish', 'draft') AND post_name != '' ORDER BY ID;" 2>/dev/null > "${TEMP_PAGES}" || echo "" > "${TEMP_PAGES}"

if [ -s "${TEMP_PAGES}" ]; then
    cat > /tmp/import-pages.php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
$pages_data = file('php://stdin');
$imported = 0;
$updated = 0;
$skipped = 0;
$page_map = array(); // ローカルID => 開発環境ID のマッピング

// 最初の行（ヘッダー）をスキップ
$header = array_shift($pages_data);

foreach ($pages_data as $line) {
    $line = trim($line);
    if (empty($line)) continue;
    
    $fields = explode("\t", $line);
    if (count($fields) < 8) continue;
    
    list($local_id, $post_title, $post_name, $post_content, $post_excerpt, $post_status, $menu_order, $post_parent) = $fields;
    
    if (empty($post_name)) continue;
    
    // 既存のページを検索
    $existing_page = get_page_by_path($post_name);
    
    $page_args = array(
        'post_title' => $post_title,
        'post_name' => $post_name,
        'post_content' => $post_content,
        'post_excerpt' => $post_excerpt,
        'post_status' => ($post_status == 'publish') ? 'publish' : 'draft',
        'post_type' => 'page',
        'menu_order' => (int)$menu_order,
        'post_parent' => 0, // 親ページは後で設定
    );
    
    if ($existing_page) {
        $page_args['ID'] = $existing_page->ID;
        $result = wp_update_post($page_args, true);
        if (!is_wp_error($result)) {
            $page_map[$local_id] = $existing_page->ID;
            $updated++;
        } else {
            $skipped++;
        }
    } else {
        $result = wp_insert_post($page_args, true);
        if (!is_wp_error($result) && $result > 0) {
            $page_map[$local_id] = $result;
            $imported++;
        } else {
            $skipped++;
        }
    }
}

// 親子関係を設定
rewind(STDIN);
$pages_data = file('php://stdin');
array_shift($pages_data); // ヘッダーをスキップ

foreach ($pages_data as $line) {
    $line = trim($line);
    if (empty($line)) continue;
    
    $fields = explode("\t", $line);
    if (count($fields) < 8) continue;
    
    list($local_id, $post_title, $post_name, $post_content, $post_excerpt, $post_status, $menu_order, $post_parent) = $fields;
    
    if (empty($post_parent) || $post_parent == 0) continue;
    if (!isset($page_map[$local_id]) || !isset($page_map[$post_parent])) continue;
    
    wp_update_post(array(
        'ID' => $page_map[$local_id],
        'post_parent' => $page_map[$post_parent]
    ));
}

echo "インポート: {$imported}件、更新: {$updated}件、スキップ: {$skipped}件\n";
PHP_SCRIPT

    ${SCP_CMD} /tmp/import-pages.php "${SSH_CONNECTION}:${PROD_WP_PATH}/import-pages.php" 2>/dev/null
    ${SCP_CMD} "${TEMP_PAGES}" "${SSH_CONNECTION}:${PROD_WP_PATH}/pages-import.txt" 2>/dev/null
    cat "${TEMP_PAGES}" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php import-pages.php < pages-import.txt" 2>&1 | head -5
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f import-pages.php pages-import.txt" 2>/dev/null || true
    rm -f /tmp/import-pages.php
else
    echo "⚠ 固定ページが見つかりません"
fi
rm -f "${TEMP_PAGES}"
echo "✓ 固定ページをコピーしました"
echo ""

# 固定ページ同期後、TOPページIDを取得してフロントページ設定を更新
# 注意: ローカル環境のページID（105）をそのまま使うのではなく、開発環境で実際に存在するTOPページIDを使用する
echo "   フロントページ設定を更新中（開発環境のTOPページIDを取得）..."
cat > /tmp/set-front-page.php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');

// TOPページを複数の方法で検索
$top_page = null;

// 方法1: スラッグ「top」で検索
$top_page = get_page_by_path('top');
if (!$top_page) {
    // 方法2: タイトル「TOP」で検索
    $pages = get_pages(array(
        'post_title' => 'TOP',
        'post_status' => 'publish',
        'number' => 1
    ));
    if (!empty($pages)) {
        $top_page = $pages[0];
    }
}
if (!$top_page) {
    // 方法3: タイトル「トップページ」で検索
    $pages = get_pages(array(
        'post_title' => 'トップページ',
        'post_status' => 'publish',
        'number' => 1
    ));
    if (!empty($pages)) {
        $top_page = $pages[0];
    }
}
if (!$top_page) {
    // 方法4: 最初の公開済みページを使用
    $pages = get_pages(array(
        'post_status' => 'publish',
        'number' => 1,
        'sort_order' => 'ASC',
        'sort_column' => 'ID'
    ));
    if (!empty($pages)) {
        $top_page = $pages[0];
    }
}
if (!$top_page) {
    // 方法5: TOPページを作成
    $top_page_id = wp_insert_post(array(
        'post_title' => 'TOP',
        'post_name' => 'top',
        'post_status' => 'publish',
        'post_type' => 'page'
    ), true);
    if (!is_wp_error($top_page_id)) {
        $top_page = get_post($top_page_id);
    }
}

if ($top_page && $top_page->ID > 0) {
    // フロントページ設定を更新（開発環境の実際のページIDを使用）
    $old_page_on_front = get_option('page_on_front');
    update_option('show_on_front', 'page');
    update_option('page_on_front', $top_page->ID);
    
    // ページIDが変更された場合のみフラッシュ
    if ($old_page_on_front != $top_page->ID) {
        flush_rewrite_rules(true); // ハードフラッシュで.htaccessも更新
    }
    
    echo "✓ フロントページを設定しました（ページID: {$top_page->ID}, スラッグ: {$top_page->post_name}）\n";
} else {
    echo "⚠ フロントページの設定に失敗しました\n";
}
PHP_SCRIPT

${SCP_CMD} /tmp/set-front-page.php "${SSH_CONNECTION}:${PROD_WP_PATH}/set-front-page.php" 2>/dev/null
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php set-front-page.php" 2>&1 | head -5
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f set-front-page.php" 2>/dev/null || true
rm -f /tmp/set-front-page.php
echo ""


# ============================================
# 5. コメントをコピー（記事は除外）
# ============================================
echo "=========================================="
echo "5. コメントをコピー中（記事は除外）..."
echo "=========================================="

TEMP_COMMENTS=$(mktemp)
${LOCAL_WP_CLI} comment list --format=json --fields=id,comment_post_ID,comment_author,comment_author_email,comment_author_url,comment_author_IP,comment_date,comment_date_gmt,comment_content,comment_karma,comment_approved,comment_agent,comment_type,comment_parent,user_id > "${TEMP_COMMENTS}" 2>/dev/null || echo "[]" > "${TEMP_COMMENTS}"

if [ -s "${TEMP_COMMENTS}" ]; then
    echo "   （コメントは既存の記事IDと一致しない場合はスキップされます）"
    # コメントのインポートは複雑なため、WP-CLIのコメントインポート機能を使用
    # 注意: 記事IDが一致しない場合はコメントは作成されません
    ${SCP_CMD} "${TEMP_COMMENTS}" "${SSH_CONNECTION}:${PROD_WP_PATH}/comments-import.json" 2>/dev/null
    
    cat > /tmp/import-comments.php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
$comments_json = file_get_contents('comments-import.json');
$comments = json_decode($comments_json, true);
if (!is_array($comments)) exit(1);
$imported = 0;
$skipped = 0;
foreach ($comments as $comment_data) {
    $post_id = $comment_data['comment_post_ID'];
    if (!get_post($post_id)) {
        $skipped++;
        continue;
    }
    $comment_id = wp_insert_comment(array(
        'comment_post_ID' => $post_id,
        'comment_author' => $comment_data['comment_author'],
        'comment_author_email' => $comment_data['comment_author_email'],
        'comment_author_url' => $comment_data['comment_author_url'],
        'comment_content' => $comment_data['comment_content'],
        'comment_date' => $comment_data['comment_date'],
        'comment_date_gmt' => $comment_data['comment_date_gmt'],
        'comment_approved' => $comment_data['comment_approved'],
        'comment_parent' => $comment_data['comment_parent'] ?: 0,
        'user_id' => $comment_data['user_id'] ?: 0,
        'comment_type' => $comment_data['comment_type'] ?: 'comment',
    ));
    if ($comment_id) $imported++;
    else $skipped++;
}
echo "インポート: {$imported}件、スキップ: {$skipped}件\n";
PHP_SCRIPT

    ${SCP_CMD} /tmp/import-comments.php "${SSH_CONNECTION}:${PROD_WP_PATH}/import-comments.php" 2>/dev/null
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php import-comments.php" 2>&1
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f import-comments.php comments-import.json" 2>/dev/null || true
    rm -f /tmp/import-comments.php
else
    echo "⚠ コメントが見つかりません"
fi
rm -f "${TEMP_COMMENTS}"
echo ""

# ============================================
# 6. カテゴリーとタグをコピー（記事は除外）
# ============================================
echo "=========================================="
echo "6. カテゴリーとタグをコピー中..."
echo "=========================================="

# カテゴリーをエクスポート・インポート
TEMP_CATEGORIES=$(mktemp)
${LOCAL_WP_CLI} term list category --format=json --fields=term_id,name,slug,description,parent > "${TEMP_CATEGORIES}" 2>/dev/null || echo "[]" > "${TEMP_CATEGORIES}"

if [ -s "${TEMP_CATEGORIES}" ]; then
    cat "${TEMP_CATEGORIES}" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
\$categories_json = file_get_contents('php://stdin');
\$categories = json_decode(\$categories_json, true);
if (is_array(\$categories)) {
    foreach (\$categories as \$cat) {
        if (!term_exists(\$cat['slug'], 'category')) {
            wp_insert_term(\$cat['name'], 'category', array(
                'slug' => \$cat['slug'],
                'description' => \$cat['description'] ?? '',
                'parent' => \$cat['parent'] ?? 0
            ));
        }
    }
    echo 'カテゴリーをインポートしました\n';
}
PHP_SCRIPT
" 2>&1 | head -5
fi
rm -f "${TEMP_CATEGORIES}"

# タグをエクスポート・インポート
TEMP_TAGS=$(mktemp)
${LOCAL_WP_CLI} term list post_tag --format=json --fields=term_id,name,slug,description > "${TEMP_TAGS}" 2>/dev/null || echo "[]" > "${TEMP_TAGS}"

if [ -s "${TEMP_TAGS}" ]; then
    cat "${TEMP_TAGS}" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
\$tags_json = file_get_contents('php://stdin');
\$tags = json_decode(\$tags_json, true);
if (is_array(\$tags)) {
    foreach (\$tags as \$tag) {
        if (!term_exists(\$tag['slug'], 'post_tag')) {
            wp_insert_term(\$tag['name'], 'post_tag', array(
                'slug' => \$tag['slug'],
                'description' => \$tag['description'] ?? ''
            ));
        }
    }
    echo 'タグをインポートしました\n';
}
PHP_SCRIPT
" 2>&1 | head -5
fi
rm -f "${TEMP_TAGS}"
echo "✓ カテゴリーとタグをコピーしました"
echo ""

# ============================================
# 7. 全ての設定オプションをコピー（記事関連を除く）
# ============================================
echo "=========================================="
echo "7. 全ての設定オプションをコピー中..."
echo "=========================================="

TEMP_ALL_OPTIONS=$(mktemp)
# page_on_frontとshow_on_frontは除外（固定ページ同期後に開発環境のTOPページIDで設定するため）
docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_name, option_value FROM wp_options WHERE option_name NOT LIKE '%_transient%' AND option_name NOT LIKE '%_site_transient%' AND option_name NOT IN ('_posts', '_comments', '_users', 'active_plugins', 'cron', 'page_on_front', 'show_on_front') AND (option_name LIKE 'theme_mods%' OR option_name LIKE 'loos_%' OR option_name LIKE 'swell_%' OR option_name LIKE 'widget_%' OR option_name = 'sidebars_widgets' OR option_name LIKE 'nav_menu%' OR option_name IN ('page_for_posts', 'stylesheet', 'template', 'blogname', 'blogdescription', 'permalink_structure', 'category_base', 'tag_base'));" 2>/dev/null | grep -v "option_name" > "${TEMP_ALL_OPTIONS}"

if [ -s "${TEMP_ALL_OPTIONS}" ]; then
    ${SCP_CMD} "${TEMP_ALL_OPTIONS}" "${SSH_CONNECTION}:${PROD_WP_PATH}/temp-all-options.txt" 2>/dev/null
    
    cat > /tmp/import-options.sh << 'OPTIONS_SCRIPT_END'
#!/bin/bash
WP_PATH="$1"
cd "$WP_PATH" || exit 1
    while IFS=$'\t' read -r opt_name opt_value; do
        if [ -n "$opt_name" ] && [ -n "$opt_value" ] && [ "$opt_name" != "option_name" ]; then
            # 記事関連のオプションはスキップ
            if [[ "$opt_name" =~ (post_|posts_per_page|rss_|default_post) ]] && [[ ! "$opt_name" =~ (page_for_posts) ]]; then
                continue
            fi
            # page_on_frontとshow_on_frontは除外（固定ページ同期後に設定するため）
            if [ "$opt_name" = "page_on_front" ] || [ "$opt_name" = "show_on_front" ]; then
                continue
            fi
            echo "$opt_value" | wp option update "$opt_name" --format=json --allow-root 2>/dev/null || true
        fi
    done < temp-all-options.txt
rm -f temp-all-options.txt
echo "設定オプションを更新しました"
OPTIONS_SCRIPT_END

    chmod +x /tmp/import-options.sh
    ${SCP_CMD} /tmp/import-options.sh "${SSH_CONNECTION}:${PROD_WP_PATH}/import-options.sh" 2>/dev/null
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && bash import-options.sh ${PROD_WP_PATH}" 2>&1 | tail -1
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f import-options.sh" 2>/dev/null || true
    rm -f /tmp/import-options.sh
else
    echo "⚠ 設定オプションが見つかりません"
fi
rm -f "${TEMP_ALL_OPTIONS}"
echo ""

# ============================================
# 8. ウィジェットとメニューをコピー
# ============================================
echo "=========================================="
echo "8. ウィジェットとメニューをコピー中..."
echo "=========================================="

# ウィジェット設定
TEMP_WIDGETS=$(mktemp)
docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_name, option_value FROM wp_options WHERE option_name LIKE 'widget_%' OR option_name = 'sidebars_widgets';" 2>/dev/null | grep -v "option_name" > "${TEMP_WIDGETS}"

if [ -s "${TEMP_WIDGETS}" ]; then
    while IFS=$'\t' read -r opt_name opt_value; do
        if [ -n "$opt_name" ] && [ -n "$opt_value" ]; then
            echo -n "$opt_value" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp option update '${opt_name}' --format=json --allow-root" 2>/dev/null || true
        fi
    done < "${TEMP_WIDGETS}"
    echo "✓ ウィジェット設定をコピーしました"
fi
rm -f "${TEMP_WIDGETS}"

# メニュー設定
TEMP_MENUS=$(mktemp)
${LOCAL_WP_CLI} menu list --format=json > "${TEMP_MENUS}" 2>/dev/null || echo "[]" > "${TEMP_MENUS}"

if [ -s "${TEMP_MENUS}" ]; then
    cat "${TEMP_MENUS}" | ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');
\$menus_json = file_get_contents('php://stdin');
\$menus = json_decode(\$menus_json, true);
if (is_array(\$menus)) {
    foreach (\$menus as \$menu) {
        \$menu_id = wp_create_nav_menu(\$menu['name']);
        if (is_wp_error(\$menu_id)) continue;
        \$menu_items = wp_get_nav_menu_items(\$menu['term_id']);
        if (is_array(\$menu_items)) {
            foreach (\$menu_items as \$item) {
                wp_update_nav_menu_item(\$menu_id, 0, array(
                    'menu-item-title' => \$item->title,
                    'menu-item-url' => \$item->url,
                    'menu-item-status' => 'publish'
                ));
            }
        }
    }
    echo 'メニューをインポートしました\n';
}
PHP_SCRIPT
" 2>&1 | head -1
fi
rm -f "${TEMP_MENUS}"

echo "✓ ウィジェットとメニューをコピーしました"
echo ""

# ============================================
# 9. 表示設定とテーマの有効化
# ============================================
echo "=========================================="
echo "9. 表示設定とテーマを有効化中..."
echo "=========================================="

SHOW_ON_FRONT=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'show_on_front';" -N -r 2>/dev/null | head -1)
PAGE_ON_FRONT=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'page_on_front';" -N -r 2>/dev/null | head -1)
STYLESHEET=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'stylesheet';" -N -r 2>/dev/null | head -1)

# ローカル環境のサイトアドレス設定を取得
LOCAL_SITEURL=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'siteurl';" -N -r 2>/dev/null | head -1)
LOCAL_HOME=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'home';" -N -r 2>/dev/null | head -1)
LOCAL_PERMALINK=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT option_value FROM wp_options WHERE option_name = 'permalink_structure';" -N -r 2>/dev/null | head -1)

# ローカル環境のフロントページのスラッグを取得
FRONT_PAGE_SLUG=""
if [ -n "${PAGE_ON_FRONT}" ] && [ "${PAGE_ON_FRONT}" != "0" ]; then
    FRONT_PAGE_SLUG=$(docker compose exec -T db mysql -uwordpress -pwordpress wordpress -e "SELECT post_name FROM wp_posts WHERE ID = ${PAGE_ON_FRONT} AND post_type = 'page';" -N -r 2>/dev/null | head -1)
fi

if [ -n "${SHOW_ON_FRONT}" ]; then
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp option update show_on_front '${SHOW_ON_FRONT}' --allow-root" 2>/dev/null || true
    
    # デフォルトスラッグを設定
    if [ -z "${FRONT_PAGE_SLUG}" ] || [ "${FRONT_PAGE_SLUG}" = "" ]; then
        FRONT_PAGE_SLUG="top"
    fi
    
    echo "   ローカル環境のフロントページスラッグ: ${FRONT_PAGE_SLUG}"
    echo "   開発環境でTOPページを検索中..."
    
    # 開発環境でTOPページを複数の方法で探す
    DEV_PAGE_ID=""
    
    # 方法1: スラッグで検索
    if [ -n "${FRONT_PAGE_SLUG}" ] && [ "${FRONT_PAGE_SLUG}" != "" ]; then
        DEV_PAGE_ID=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp post list --post_type=page --name=${FRONT_PAGE_SLUG} --format=ids --allow-root 2>/dev/null" 2>&1 | grep -E "^[0-9]+$" | head -1 | tr -d '\r\n')
    fi
    
    # 方法2: タイトル「TOP」で検索
    if [ -z "${DEV_PAGE_ID}" ] || [ "${DEV_PAGE_ID}" = "" ]; then
        DEV_PAGE_ID=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp post list --post_type=page --post_title='TOP' --format=ids --allow-root 2>/dev/null" 2>&1 | grep -E "^[0-9]+$" | head -1 | tr -d '\r\n')
    fi
    
    # 方法3: タイトル「トップページ」で検索
    if [ -z "${DEV_PAGE_ID}" ] || [ "${DEV_PAGE_ID}" = "" ]; then
        DEV_PAGE_ID=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp post list --post_type=page --post_title='トップページ' --format=ids --allow-root 2>/dev/null" 2>&1 | grep -E "^[0-9]+$" | head -1 | tr -d '\r\n')
    fi
    
    # 方法4: 最初の公開済みページを使用
    if [ -z "${DEV_PAGE_ID}" ] || [ "${DEV_PAGE_ID}" = "" ]; then
        DEV_PAGE_ID=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp post list --post_type=page --post_status=publish --format=ids --allow-root 2>/dev/null" 2>&1 | grep -E "^[0-9]+$" | head -1 | tr -d '\r\n')
    fi
    
    # ページが見つからない場合は作成
    if [ -z "${DEV_PAGE_ID}" ] || [ "${DEV_PAGE_ID}" = "" ]; then
        echo "   フロントページ「${FRONT_PAGE_SLUG}」が見つかりません。作成します..."
        CREATE_OUTPUT=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp post create --post_type=page --post_title='TOP' --post_name='${FRONT_PAGE_SLUG}' --post_status=publish --allow-root 2>&1")
        echo "$CREATE_OUTPUT" | head -5
        
        # 作成したページのIDを取得（複数の方法で試行）
        DEV_PAGE_ID=$(echo "$CREATE_OUTPUT" | grep -oE "Created post [0-9]+" | grep -oE "[0-9]+" | head -1 | tr -d '\r\n')
        if [ -z "${DEV_PAGE_ID}" ]; then
            DEV_PAGE_ID=$(echo "$CREATE_OUTPUT" | grep -oE "post [0-9]+" | grep -oE "[0-9]+" | head -1 | tr -d '\r\n')
        fi
        if [ -z "${DEV_PAGE_ID}" ]; then
            DEV_PAGE_ID=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp post list --post_type=page --name=${FRONT_PAGE_SLUG} --format=ids --allow-root 2>/dev/null" 2>&1 | grep -E "^[0-9]+$" | head -1 | tr -d '\r\n')
        fi
    fi
    
    # ページIDが取得できた場合は設定を更新
    if [ -n "${DEV_PAGE_ID}" ] && [ "${DEV_PAGE_ID}" != "" ] && [ "${DEV_PAGE_ID}" != "0" ]; then
        echo "   取得したTOPページID: ${DEV_PAGE_ID}"
        UPDATE_OUTPUT=$(${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp option update page_on_front '${DEV_PAGE_ID}' --allow-root 2>&1")
        if echo "$UPDATE_OUTPUT" | grep -q "Success\|updated"; then
            echo "✓ フロントページを設定しました（ページID: ${DEV_PAGE_ID}）"
        else
            echo "$UPDATE_OUTPUT" | head -3
            echo "✓ フロントページ設定を試行しました（ページID: ${DEV_PAGE_ID}）"
        fi
    else
        echo "⚠ フロントページIDが取得できませんでした。後で手動で設定してください。"
    fi
fi

# サイトアドレス設定を更新（開発環境用に適切なURLに変更）
echo "   サイトアドレス設定を更新中..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp option update siteurl 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root" 2>/dev/null || true
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp option update home 'https://nichicoma.sakura.ne.jp/moriwp' --allow-root" 2>/dev/null || true
echo "✓ サイトアドレスを設定しました"

# パーマリンク設定を更新
if [ -n "${LOCAL_PERMALINK}" ]; then
    echo "   パーマリンク設定を更新中..."
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp option update permalink_structure '${LOCAL_PERMALINK}' --allow-root" 2>/dev/null || true
    echo "✓ パーマリンク設定を更新しました"
fi

if [ -n "${STYLESHEET}" ]; then
    ${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp theme activate ${STYLESHEET} --allow-root" 2>/dev/null || true
    echo "✓ テーマを有効化しました"
fi

# パーマリンクをフラッシュ（ハードフラッシュで.htaccessも更新）
# 注意: set-front-page.phpで既にフラッシュしているので、ここではスキップしても良いが念のため実行
echo "   パーマリンクをフラッシュ中（念のため再実行）..."
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && wp rewrite flush --hard --allow-root" 2>/dev/null || true

echo "✓ 表示設定をコピーしました"
echo ""

# フロントページ設定の最終確認（PHPスクリプトで確認）
echo "   フロントページ設定の最終確認..."
cat > /tmp/check-front-page.php << 'PHP_SCRIPT'
<?php
require_once('wp-load.php');

$show_on_front = get_option('show_on_front');
$page_on_front = get_option('page_on_front');

echo "show_on_front: {$show_on_front}\n";
echo "page_on_front: {$page_on_front}\n";

if ($show_on_front === 'page' && !empty($page_on_front)) {
    $page = get_post($page_on_front);
    if ($page) {
        echo "✓ フロントページ設定確認: ページID={$page->ID}, タイトル={$page->post_title}, スラッグ={$page->post_name}\n";
    } else {
        echo "⚠ フロントページID ({$page_on_front}) のページが見つかりません\n";
    }
} else {
    echo "⚠ フロントページが設定されていません\n";
}
PHP_SCRIPT

${SCP_CMD} /tmp/check-front-page.php "${SSH_CONNECTION}:${PROD_WP_PATH}/check-front-page.php" 2>/dev/null
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && php check-front-page.php" 2>&1 | head -5
${SSH_CMD} "${SSH_CONNECTION}" "cd ${PROD_WP_PATH} && rm -f check-front-page.php" 2>/dev/null || true
rm -f /tmp/check-front-page.php
echo ""

# ============================================
# 完了
# ============================================
echo "=========================================="
echo "全データ同期が完了しました！"
echo "=========================================="
echo ""
echo "コピーされた内容:"
echo "✓ テーマファイル（CSS、JavaScript、HTML、JSON含む）"
echo "✓ カスタマイザー設定"
echo "✓ ユーザー情報"
echo "✓ 固定ページ"
echo "✓ コメント（既存記事に紐づくもののみ）"
echo "✓ カテゴリーとタグ"
echo "✓ 全ての設定オプション（記事関連を除く）"
echo "✓ ウィジェット設定"
echo "✓ メニュー設定"
echo "✓ 表示設定"
echo ""
echo "⚠️  投稿記事データは除外されました（固定ページは含まれます）"
echo ""
echo "開発環境で確認してください:"
echo "https://nichicoma.sakura.ne.jp/moriwp/"
echo ""
