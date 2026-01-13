<?php
/**
 * 参照先のNext.jsファイルからカテゴリーとタグを同期
 * 使用方法: 
 *   1. WordPressのルートディレクトリで実行: php scripts/sync-categories-tags.php
 *   2. または、functions.phpから一時的に呼び出す
 */

// WordPressを読み込む（functions.phpから呼び出される場合）
if (!defined('ABSPATH')) {
    require_once(__DIR__ . '/../../../wp-load.php');
}

// メインカテゴリー（投稿カテゴリー）
$main_categories = array(
    'gt-news' => 'GTニュース',
    'industry' => '業界ニュース',
    'victim' => '被害速報',
    'gt-mail' => 'GTメール',
    'topics' => 'トピックス',
    'column' => 'コラム',
);

// GTニュースのサブカテゴリー（タグとして使用）
$gt_news_subcategories = array(
    'ゴト情報',
    '噂未確認',
    '防護・対策',
    '話題',
    '統計',
    'お知らせ',
);

// その他のタグ
$other_tags = array(
    '重要',
    '緊急',
    '会員限定',
);

// 動画カテゴリー（タグとして使用）
$movie_categories = array(
    'gt-movie' => 'GTムービー',
    'hankou' => '犯行動画',
    'kensyou' => '検証動画',
);

echo "=== カテゴリーとタグの同期を開始します ===\n\n";

// 1. メインカテゴリーを登録
echo "1. メインカテゴリーを登録中...\n";
foreach ($main_categories as $slug => $name) {
    $term = term_exists($slug, 'category');
    if (!$term) {
        $result = wp_insert_term($name, 'category', array('slug' => $slug));
        if (is_wp_error($result)) {
            echo "  ✗ エラー: {$name} ({$slug}) - " . $result->get_error_message() . "\n";
        } else {
            echo "  ✓ 登録: {$name} ({$slug})\n";
        }
    } else {
        echo "  - 既存: {$name} ({$slug})\n";
    }
}

// 2. GTニュースのサブカテゴリーをタグとして登録
echo "\n2. GTニュースのサブカテゴリーをタグとして登録中...\n";
foreach ($gt_news_subcategories as $tag_name) {
    $term = term_exists($tag_name, 'post_tag');
    if (!$term) {
        $result = wp_insert_term($tag_name, 'post_tag');
        if (is_wp_error($result)) {
            echo "  ✗ エラー: {$tag_name} - " . $result->get_error_message() . "\n";
        } else {
            echo "  ✓ 登録: {$tag_name}\n";
        }
    } else {
        echo "  - 既存: {$tag_name}\n";
    }
}

// 3. その他のタグを登録
echo "\n3. その他のタグを登録中...\n";
foreach ($other_tags as $tag_name) {
    $term = term_exists($tag_name, 'post_tag');
    if (!$term) {
        $result = wp_insert_term($tag_name, 'post_tag');
        if (is_wp_error($result)) {
            echo "  ✗ エラー: {$tag_name} - " . $result->get_error_message() . "\n";
        } else {
            echo "  ✓ 登録: {$tag_name}\n";
        }
    } else {
        echo "  - 既存: {$tag_name}\n";
    }
}

// 4. 動画カテゴリーをタグとして登録
echo "\n4. 動画カテゴリーをタグとして登録中...\n";
foreach ($movie_categories as $slug => $name) {
    $term = term_exists($slug, 'post_tag');
    if (!$term) {
        $result = wp_insert_term($name, 'post_tag', array('slug' => $slug));
        if (is_wp_error($result)) {
            echo "  ✗ エラー: {$name} ({$slug}) - " . $result->get_error_message() . "\n";
        } else {
            echo "  ✓ 登録: {$name} ({$slug})\n";
        }
    } else {
        echo "  - 既存: {$name} ({$slug})\n";
    }
}

// 5. 不要なカテゴリーとタグを削除（参照先にないもの）
echo "\n5. 不要なカテゴリーとタグを確認中...\n";

// 登録済みのカテゴリーを取得
$all_categories = get_categories(array('hide_empty' => false));
$registered_category_slugs = array_keys($main_categories);

foreach ($all_categories as $cat) {
    // デフォルトの「未分類」はスキップ
    if ($cat->slug === 'uncategorized' || $cat->slug === 'uncategorized-jp') {
        continue;
    }
    
    // 参照先にないカテゴリーを削除
    if (!in_array($cat->slug, $registered_category_slugs)) {
        echo "  ✗ 削除: {$cat->name} ({$cat->slug})\n";
        wp_delete_term($cat->term_id, 'category');
    }
}

// 登録済みのタグを取得
$all_tags = get_tags(array('hide_empty' => false));
$registered_tag_names = array_merge($gt_news_subcategories, $other_tags);
$registered_tag_slugs = array_keys($movie_categories);

foreach ($all_tags as $tag) {
    $should_keep = false;
    
    // GTニュースのサブカテゴリーまたはその他のタグに含まれるか
    if (in_array($tag->name, $registered_tag_names)) {
        $should_keep = true;
    }
    
    // 動画カテゴリーのスラッグに含まれるか
    if (in_array($tag->slug, $registered_tag_slugs)) {
        $should_keep = true;
    }
    
    if (!$should_keep) {
        echo "  ✗ 削除: {$tag->name} ({$tag->slug})\n";
        wp_delete_term($tag->term_id, 'post_tag');
    }
}

echo "\n=== 同期が完了しました ===\n";
