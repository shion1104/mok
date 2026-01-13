<?php
/**
 * ヘッダーメニューから会員向け項目を完全に削除するスクリプト（即座に実行）
 * 
 * 実行方法:
 * php delete-menu-items-now.php
 * または
 * WordPressのルートディレクトリから: php wp-content/themes/swell_child/delete-menu-items-now.php
 */

// WordPressを読み込む
require_once(__DIR__ . '/../../../wp-load.php');

// CLI実行時は権限チェックをスキップ
$is_cli = php_sapi_name() === 'cli';
if (!$is_cli) {
	// 管理者のみ実行可能
	if (!current_user_can('manage_options')) {
		die('このスクリプトは管理者のみ実行可能です。');
	}
}

// functions.phpから関数を読み込む
require_once(__DIR__ . '/functions.php');

// 削除するキーワード（functions.phpの関数を使用）
$exclude_keywords = swell_child_get_exclude_keywords();

// 削除する会員向けページパス
$exclude_paths = array(
	'/member-login/', '/member-area/', '/member-profile/', '/profile/',
	'/password-reset/', '/reset-password/', '/wp-login.php',
	'/wp-register.php', '/register/', '/thank-you/', '/thankyou/',
	'/join/', '/join-us/', '/member/', '/members/'
);

// すべてのメニューを取得してチェック
$menu_locations = get_nav_menu_locations();
$menus_to_check = array();

// ヘッダーメニューを優先的にチェック
if (isset($menu_locations['header_menu']) && $menu_locations['header_menu']) {
	$menus_to_check['header_menu'] = $menu_locations['header_menu'];
}

// すべてのメニューもチェック（ヘッダーメニューが見つからない場合）
$all_menus = wp_get_nav_menus();
foreach ($all_menus as $menu) {
	if (!in_array($menu->term_id, $menus_to_check)) {
		$menus_to_check[$menu->slug] = $menu->term_id;
	}
}

if (empty($menus_to_check)) {
	echo "メニューが見つかりません。\n";
	echo "WordPress管理画面の「外観 → メニュー」でメニューを設定してください。\n";
	exit;
}

// すべてのメニューから削除対象を探す
$all_menu_items = array();
foreach ($menus_to_check as $menu_name => $menu_id) {
	$items = wp_get_nav_menu_items($menu_id);
	if ($items) {
		foreach ($items as $item) {
			$item->menu_name = $menu_name;
			$all_menu_items[] = $item;
		}
	}
}

$menu_items = $all_menu_items;
$deleted_count = 0;
$deleted_items = array();

if ($menu_items) {
	foreach ($menu_items as $item) {
		$should_delete = false;
		$reason = '';
		
		// 空の項目をチェック
		if (empty($item->title) || empty($item->url) || $item->url === '#') {
			$should_delete = true;
			$reason = '空の項目';
		}
		
		// MITUS関連をチェック
		if (!$should_delete) {
			$item_url_lower = strtolower($item->url);
			if (strpos($item_url_lower, '/mitus') !== false || strpos($item_url_lower, 'mitus') !== false) {
				$should_delete = true;
				$reason = 'MITUS関連';
			}
		}
		
		// 会員向けページパスをチェック
		if (!$should_delete) {
			$item_url_lower = strtolower($item->url);
			foreach ($exclude_paths as $path) {
				if (strpos($item_url_lower, $path) !== false) {
					$should_delete = true;
					$reason = '会員向けページ: ' . $path;
					break;
				}
			}
		}
		
		// キーワードをチェック
		if (!$should_delete) {
			$item_url_lower = strtolower($item->url);
			$item_title_lower = strtolower($item->title);
			foreach ($exclude_keywords as $keyword) {
				$keyword_lower = strtolower($keyword);
				if (strpos($item_url_lower, $keyword_lower) !== false || strpos($item_title_lower, $keyword_lower) !== false) {
					$should_delete = true;
					$reason = 'キーワード: ' . $keyword;
					break;
				}
			}
		}
		
		// 削除実行
		if ($should_delete) {
			$menu_name = isset($item->menu_name) ? $item->menu_name : 'unknown';
			$deleted_items[] = array(
				'title' => $item->title,
				'url' => $item->url,
				'menu' => $menu_name,
				'reason' => $reason
			);
			wp_delete_post($item->ID, true);
			$deleted_count++;
		}
	}
}

// 結果を表示
echo "========================================\n";
echo "ヘッダーメニュー項目削除結果\n";
echo "========================================\n\n";

if ($deleted_count > 0) {
	echo "✓ {$deleted_count}件のメニュー項目を削除しました。\n\n";
	echo "削除された項目:\n";
	echo str_repeat("-", 100) . "\n";
	printf("%-25s %-35s %-15s %s\n", "タイトル", "URL", "メニュー", "削除理由");
	echo str_repeat("-", 100) . "\n";
	foreach ($deleted_items as $item) {
		$title = mb_substr($item['title'], 0, 23);
		$url = mb_substr($item['url'], 0, 33);
		$menu = mb_substr($item['menu'], 0, 13);
		printf("%-25s %-35s %-15s %s\n", $title, $url, $menu, $item['reason']);
	}
} else {
	echo "削除対象のメニュー項目は見つかりませんでした。\n";
}

echo "\n";
