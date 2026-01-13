<?php
/**
 * ヘッダーメニューから会員向け項目を完全に削除するスクリプト
 * 
 * 使用方法:
 * 1. WordPress管理画面にログイン
 * 2. このファイルをブラウザで直接実行: /wp-content/themes/swell_child/remove-menu-items.php
 * または
 * WP-CLIで実行: wp eval-file wp-content/themes/swell_child/remove-menu-items.php
 */

// WordPressを読み込む
require_once('../../../wp-load.php');

// 管理者のみ実行可能
if (!current_user_can('manage_options')) {
	die('このスクリプトは管理者のみ実行可能です。');
}

// 削除するキーワード
$exclude_keywords = array(
	'reset', 'リセット', 'パスワードのリセット', 'password', 'password-reset', 'reset-password',
	'profile', 'プロフィール', 'member-profile', 'user-profile',
	'register', '登録', 'wp-register', 'registration',
	'join', 'JOIN', 'Join', 'join-us', 'joinus',
	'thank', 'thank you', 'thankyou', 'THANK', 'THANKYOU', 'THANK YOU',
	'Thank', 'ThankYou', 'Thank You', 'thank-you',
	'member-area', 'member-profile', 'members', 'メンバーエリア',
	'mitus', 'MITUS', 'Mitus'
);

// 削除する会員向けページパス
$exclude_paths = array(
	'/member-login/', '/member-area/', '/member-profile/', '/profile/',
	'/password-reset/', '/reset-password/', '/wp-login.php',
	'/wp-register.php', '/register/', '/thank-you/', '/thankyou/',
	'/join/', '/join-us/', '/member/', '/members/'
);

// ヘッダーメニューを取得
$menu_locations = get_nav_menu_locations();
$header_menu_id = isset($menu_locations['header_menu']) ? $menu_locations['header_menu'] : null;

if (!$header_menu_id) {
	echo "ヘッダーメニューが見つかりません。\n";
	echo "WordPress管理画面の「外観 → メニュー」でヘッダーメニューを設定してください。\n";
	exit;
}

$menu_items = wp_get_nav_menu_items($header_menu_id);
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
			$deleted_items[] = array(
				'title' => $item->title,
				'url' => $item->url,
				'reason' => $reason
			);
			wp_delete_post($item->ID, true);
			$deleted_count++;
		}
	}
}

// 結果を表示
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>メニュー項目削除結果</title>
	<style>
		body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
		.success { color: #28a745; }
		.info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; }
		table { width: 100%; border-collapse: collapse; margin: 20px 0; }
		th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
		th { background: #f5f5f5; }
	</style>
</head>
<body>
	<h1>ヘッダーメニュー項目削除結果</h1>
	
	<?php if ($deleted_count > 0): ?>
		<div class="info">
			<p class="success"><strong><?php echo $deleted_count; ?>件</strong>のメニュー項目を削除しました。</p>
		</div>
		
		<h2>削除された項目</h2>
		<table>
			<thead>
				<tr>
					<th>タイトル</th>
					<th>URL</th>
					<th>削除理由</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($deleted_items as $item): ?>
					<tr>
						<td><?php echo esc_html($item['title']); ?></td>
						<td><?php echo esc_html($item['url']); ?></td>
						<td><?php echo esc_html($item['reason']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="info">
			<p>削除対象のメニュー項目は見つかりませんでした。</p>
		</div>
	<?php endif; ?>
	
	<p><a href="<?php echo admin_url('nav-menus.php'); ?>">メニュー管理画面に戻る</a></p>
</body>
</html>
