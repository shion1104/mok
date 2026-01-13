<?php

/* 子テーマのfunctions.phpは、親テーマのfunctions.phpより先に読み込まれることに注意してください。 */

/**
 * GT-NET用のメタデータ設定（参照先のlayout.tsxから）
 */
add_filter('document_title_parts', function($title) {
	if (is_page_template('page-top.php')) {
		$title['title'] = 'GT-NET | パチンコ業界のリスクマネジメント';
		$title['tagline'] = 'ゴト対策からセキュリティ監査まで、現場主義のコンサルティングでホール経営を守ります。';
	}
	return $title;
}, 10, 1);

add_action('wp_head', function() {
	if (is_page_template('page-top.php')) {
		echo '<meta name="description" content="ゴト対策からセキュリティ監査まで、現場主義のコンサルティングでホール経営を守ります。" />' . "\n";
	}
}, 1);

/**
 * エディタ用のCSSを読み込む（投稿編集画面と投稿ページを統一）
 * SWELLのカスタマイザー設定を反映してエディタと投稿ページを完全に統一
 */
add_action('enqueue_block_editor_assets', function() {
	// SWELLのカスタマイザー設定を直接取得
	$customize_options = get_option('loos_customizer', []);
	$color_bg = isset($customize_options['color_bg']) && !empty($customize_options['color_bg']) 
		? $customize_options['color_bg'] 
		: '#0a0a0a';
	$color_text = isset($customize_options['color_text']) && !empty($customize_options['color_text']) 
		? $customize_options['color_text'] 
		: '#ffffff';
	$color_main = isset($customize_options['color_main']) && !empty($customize_options['color_main']) 
		? $customize_options['color_main'] 
		: '#00ffff';
	
	// エディタ用のCSSをインラインで追加（SWELLのCSSより後に読み込まれるように優先度を高く）
	wp_add_inline_style( 'swell_editor_style', "
		/* エディタ全体の背景を投稿ページと統一 */
		body.gutenberg-editor-page,
		body.gutenberg-editor-page #wpbody-content {
			background: {$color_bg} !important;
		}
		
		/* SWELLのCSS変数を確実にエディタで使用 */
		.gutenberg-editor-page :root {
			--gt-dark-bg: #0a0a0a;
			--gt-text-primary: {$color_text};
			--gt-text-secondary: #cccccc;
			--gt-cyan: {$color_main};
			--gt-cyan-light: rgba(0, 255, 255, 0.3);
			--gt-cyan-dark: rgba(0, 255, 255, 0.1);
		}
		
		/* エディタのコンテンツ背景を投稿ページと統一（SWELLのカスタマイザー設定を反映） */
		.editor-styles-wrapper {
			background: var(--color_bg, {$color_bg}) !important;
			background-color: var(--color_bg, {$color_bg}) !important;
		}
		
		/* SWELLの--color_content_bgを確実に上書き（投稿ページと同じ背景色に） */
		.gutenberg-editor-page {
			--color_content_bg: var(--color_bg, {$color_bg}) !important;
		}
		
		/* エディタのコンテンツエリア全体 */
		.block-editor-block-list__layout,
		.editor-writing-flow,
		.block-editor-block-list__block {
			background: transparent !important;
		}
		
		/* エディタ内のテキスト色をSWELLの設定に統一 */
		.editor-styles-wrapper {
			color: var(--color_text, {$color_text}) !important;
		}
		
		.editor-styles-wrapper p,
		.editor-styles-wrapper div,
		.editor-styles-wrapper span,
		.editor-styles-wrapper li {
			color: var(--color_text, {$color_text}) !important;
		}
		
		.editor-styles-wrapper h1,
		.editor-styles-wrapper h2,
		.editor-styles-wrapper h3,
		.editor-styles-wrapper h4,
		.editor-styles-wrapper h5,
		.editor-styles-wrapper h6 {
			color: var(--color_htag, var(--color_main, {$color_main})) !important;
		}
		
		/* エディタのiframe内も統一 */
		iframe[name=\"editor-canvas\"] {
			background: {$color_bg} !important;
		}
		
		iframe[name=\"editor-canvas\"] body {
			background: {$color_bg} !important;
			color: {$color_text} !important;
		}
		
		iframe[name=\"editor-canvas\"] .editor-styles-wrapper {
			background: var(--color_bg, {$color_bg}) !important;
			background-color: var(--color_bg, {$color_bg}) !important;
		}
	" );
}, 999);

/**
 * モダン・コーポレートデザイン用CSSを読み込む
 */
add_action('wp_enqueue_scripts', function() {
	// 管理画面では読み込まない
	if (is_admin()) {
		return;
	}
	
	$timestamp = date( 'Ymdgis', filemtime( get_stylesheet_directory() . '/style.css' ) );
	wp_enqueue_style( 'child_style', get_stylesheet_directory_uri() .'/style.css', [], $timestamp );
	
	// モダン・コーポレートデザイン用CSS（全ページで読み込み）
	$modern_css_path = get_stylesheet_directory() . '/assets/css/modern-corporate.css';
	if ( file_exists( $modern_css_path ) ) {
		$modern_css_timestamp = filemtime( $modern_css_path );
		wp_enqueue_style( 
			'modern-corporate-style', 
			get_stylesheet_directory_uri() . '/assets/css/modern-corporate.css', 
			array( 'child_style' ), 
			$modern_css_timestamp 
		);
	}
	
	// ヘッダーメニュー項目削除スクリプト（全ページで読み込み）
	$remove_menu_items_js_path = get_stylesheet_directory() . '/assets/js/remove-header-menu-items.js';
	if ( file_exists( $remove_menu_items_js_path ) ) {
		$remove_menu_items_js_timestamp = filemtime( $remove_menu_items_js_path );
		wp_enqueue_script( 
			'remove-header-menu-items', 
			get_stylesheet_directory_uri() . '/assets/js/remove-header-menu-items.js', 
			array(), 
			$remove_menu_items_js_timestamp, 
			true 
		);
	}

	// トップページ専用のスタイルとスクリプト
	if ( is_page_template( 'page-top.php' ) || is_page( 'top' ) || is_front_page() ) {
		$header_footer_css_path = get_stylesheet_directory() . '/assets/css/header-footer-top.css';
		$top_css_path = get_stylesheet_directory() . '/assets/css/top-page.css';
		$top_js_path = get_stylesheet_directory() . '/assets/js/top-page.js';
		
		// ヘッダー・フッター専用スタイル
		if ( file_exists( $header_footer_css_path ) ) {
			$css_timestamp = filemtime( $header_footer_css_path );
			wp_enqueue_style( 
				'header-footer-top-style', 
				get_stylesheet_directory_uri() . '/assets/css/header-footer-top.css', 
				array( 'child_style' ), 
				$css_timestamp 
			);
		}
		
		if ( file_exists( $top_css_path ) ) {
			$css_timestamp = filemtime( $top_css_path );
			wp_enqueue_style( 
				'top-page-style', 
				get_stylesheet_directory_uri() . '/assets/css/top-page.css', 
				array( 'child_style', 'header-footer-top-style' ), 
				$css_timestamp 
			);
		}
		
		if ( file_exists( $top_js_path ) ) {
			$js_timestamp = filemtime( $top_js_path );
			wp_enqueue_script( 
				'top-page-script', 
				get_stylesheet_directory_uri() . '/assets/js/top-page.js', 
				array(), 
				$js_timestamp, 
				true 
			);
		}
	}

}, 11);

add_filter('widget_text', 'do_shortcode');
add_filter('widget_text_content', 'do_shortcode');

/**
 * ヘッダーに会員ログインボタンまたはログアウトボタンを追加
 * 検索アイコンの横の右端に配置
 * ログイン時は「ログアウト」ボタンに切り替え
 * ログアウト時は「会員ログイン」と「会員登録」ボタンを表示
 * swl_parts__gnav関数をオーバーライド
 */
if ( ! function_exists( 'swl_parts__gnav' ) ) :
	function swl_parts__gnav( $args ) {
		// 検索アイコンを常に表示
		$use_search = true;
		// 会員ログインページのURL（必要に応じて変更）
		$login_url = home_url('/member-login/'); // または wp_login_url() など
		// 会員登録ページのURL
		$register_url = wp_registration_url();
		// ログアウトURL
		$logout_url = wp_logout_url(home_url('/member-login/'));
		// ログイン状態を確認
		$is_logged_in = is_user_logged_in();
		// ユーザー登録が有効かどうかを確認
		$users_can_register = get_option('users_can_register');
	?>
		<ul class="c-gnav">
			<?php
				wp_nav_menu([
					'container'       => '',
					'fallback_cb'     => ['SWELL_Theme', 'default_head_menu' ],
					'theme_location'  => 'header_menu',
					'items_wrap'      => '%3$s',
					'link_before'     => '<span class="ttl">',
					'link_after'      => '</span>',
				]);
			?>
			<?php if ( $use_search ) : ?>
				<li class="menu-item c-gnav__s">
					<button class="c-gnav__sBtn c-plainBtn" data-onclick="toggleSearch" aria-label="<?=esc_attr__( '検索ボタン', 'swell' )?>">
						<i class="icon-search"></i>
					</button>
				</li>
			<?php endif; ?>
			<?php if ( $is_logged_in ) : ?>
				<!-- ログイン中: ログアウトボタンを表示 -->
				<li class="menu-item c-gnav__login">
					<a href="<?php echo esc_url($logout_url); ?>" class="c-gnav__loginBtn c-gnav__loginBtn--logout">
						<span class="c-gnav__loginText">ログアウト</span>
					</a>
				</li>
			<?php else : ?>
				<!-- ログアウト中: 会員登録ボタンとログインボタンを表示 -->
				<?php if ( $users_can_register ) : ?>
					<li class="menu-item c-gnav__register">
						<a href="<?php echo esc_url($register_url); ?>" class="c-gnav__registerBtn">
							<span class="c-gnav__registerText">会員登録</span>
						</a>
					</li>
				<?php endif; ?>
				<li class="menu-item c-gnav__login">
					<a href="<?php echo esc_url($login_url); ?>" class="c-gnav__loginBtn">
						<span class="c-gnav__loginText">会員ログイン</span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	<?php
	}
endif;

// ショートコードをブロックエディタでも処理
add_filter('the_content', 'do_shortcode', 11);
add_filter('wp_block_content', 'do_shortcode', 11);

/**
 * Simple Membership ショートコードの追加
 * [swpm_visitor_content] : 非会員（訪問者）向けコンテンツ
 * [swpm_protected] : 会員限定コンテンツ
 */
add_shortcode('swpm_visitor_content', function($atts, $content = '') {
	// Simple Membershipプラグインが有効な場合、SwpmAuthを使用
	if (class_exists('SwpmAuth')) {
		$auth = SwpmAuth::get_instance();
		// ログインしていない訪問者のみ表示
		if (!$auth->is_logged_in()) {
			return do_shortcode($content);
		}
		return ''; // ログインしている場合は非表示
	} else {
		// プラグインが無効な場合は通常のWordPressログインチェック
		if (!is_user_logged_in()) {
			return do_shortcode($content);
		}
		return '';
	}
});

add_shortcode('swpm_protected', function($atts, $content = '') {
	// Simple Membershipプラグインが有効かチェック
	if (!class_exists('SwpmAuth')) {
	// プラグインが無効な場合は通常のWordPressログインチェック
	if (is_user_logged_in()) {
		return do_shortcode($content);
	}
	$login_url = home_url('/member-login/');
	$message = sprintf(
		'<div class="swpm-protected-content-msg">
			<div class="swpm-protected-content-msg__icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="currentColor"/>
				</svg>
			</div>
			<div class="swpm-protected-content-msg__content">
				<div class="swpm-protected-content-msg__title">会員限定コンテンツ</div>
				<div class="swpm-protected-content-msg__text">
					このコンテンツは会員限定です。<a href="%s" class="swpm-protected-login-link">ログイン</a>してご覧ください。
				</div>
			</div>
		</div>',
		esc_url($login_url)
	);
	return $message;
}

$auth = SwpmAuth::get_instance();

// ログインしていない場合
if (!$auth->is_logged_in()) {
	$login_url = home_url('/member-login/');
	$message = sprintf(
		'<div class="swpm-protected-content-msg">
			<div class="swpm-protected-content-msg__icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="currentColor"/>
				</svg>
			</div>
			<div class="swpm-protected-content-msg__content">
				<div class="swpm-protected-content-msg__title">会員限定コンテンツ</div>
				<div class="swpm-protected-content-msg__text">
					このコンテンツは会員限定です。<a href="%s" class="swpm-protected-login-link">ログイン</a>してご覧ください。
				</div>
			</div>
		</div>',
		esc_url($login_url)
	);
	return $message;
}
	
	// アカウントが期限切れの場合
	if ($auth->is_expired_account()) {
		$renewal_link = class_exists('SwpmMiscUtils') ? SwpmMiscUtils::get_renewal_link() : '';
		$message = sprintf(
			'<div class="swpm-protected-content-msg swpm-account-expired">
			あなたのアカウントは期限切れです。続きを読むにはアカウントを更新してください。%s
			</div>',
			$renewal_link ? $renewal_link : ''
		);
		return $message;
	}
	
	// ログインしている会員にはコンテンツを表示
	return do_shortcode($content);
});

/**
 * [swpm_logged_in] ショートコードも追加（会員ログイン時のみ表示）
 */
add_shortcode('swpm_logged_in', function($atts, $content = '') {
	// Simple Membershipプラグインが有効かチェック
	if (!class_exists('SwpmAuth')) {
		// プラグインが無効な場合は通常のWordPressログインチェック
		if (is_user_logged_in()) {
			return do_shortcode($content);
		}
		return '';
	}
	
	$auth = SwpmAuth::get_instance();
	
	// ログインしていて、アカウントが有効な場合のみ表示
	if ($auth->is_logged_in() && !$auth->is_expired_account()) {
		return do_shortcode($content);
	}
	
	return ''; // ログインしていない、または期限切れの場合は非表示
});

/**
 * メニュー項目削除用の共通キーワード配列
 * ヘッダーメニューから会員向け項目を除外するためのキーワード
 */
function swell_child_get_exclude_keywords() {
	return array(
		// パスワード関連
		'reset', 'リセット', 'パスワードのリセット', 'password', 'password-reset', 'reset-password',
		// プロフィール関連
		'profile', 'プロフィール', 'member-profile', 'user-profile',
		// 登録関連
		'register', '登録', 'wp-register', 'registration',
		// Join関連
		'join', 'JOIN', 'Join', 'join-us', 'joinus',
		// Thank You関連
		'thank', 'thank you', 'thankyou', 'THANK', 'THANKYOU', 'THANK YOU',
		'Thank', 'ThankYou', 'Thank You', 'thank-you',
		// メンバー関連（メニュー項目として。ボタンは別途表示）
		'member-area', 'member-profile', 'members', 'メンバーエリア',
		// MITUS関連
		'mitus', 'MITUS', 'Mitus'
	);
}

/**
 * ヘッダーメニューから会員向け項目を完全に削除する関数
 * 管理画面から実行可能
 */
function swell_child_delete_excluded_menu_items() {
	// 管理者のみ実行可能
	if (!current_user_can('manage_options')) {
		return false;
	}
	
	$exclude_keywords = swell_child_get_exclude_keywords();
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
		return false;
	}
	
	$menu_items = wp_get_nav_menu_items($header_menu_id);
	$deleted_count = 0;
	
	if ($menu_items) {
		foreach ($menu_items as $item) {
			$should_delete = false;
			
			// 空の項目をチェック
			if (empty($item->title) || empty($item->url) || $item->url === '#') {
				$should_delete = true;
			}
			
			// MITUS関連をチェック
			if (!$should_delete) {
				$item_url_lower = strtolower($item->url);
				if (strpos($item_url_lower, '/mitus') !== false || strpos($item_url_lower, 'mitus') !== false) {
					$should_delete = true;
				}
			}
			
			// 会員向けページパスをチェック
			if (!$should_delete) {
				$item_url_lower = strtolower($item->url);
				foreach ($exclude_paths as $path) {
					if (strpos($item_url_lower, $path) !== false) {
						$should_delete = true;
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
						break;
					}
				}
			}
			
			// 削除実行
			if ($should_delete) {
				wp_delete_post($item->ID, true);
				$deleted_count++;
			}
		}
	}
	
	return $deleted_count;
}

/**
 * 管理画面にメニュー項目削除ボタンを追加
 */
add_action('admin_menu', function() {
	add_submenu_page(
		'themes.php',
		'ヘッダーメニュー整理',
		'ヘッダーメニュー整理',
		'manage_options',
		'swell-child-menu-cleanup',
		'swell_child_menu_cleanup_page'
	);
});

/**
 * テーマ有効化時または管理画面読み込み時に自動的に会員向けメニュー項目を削除
 * 初回のみ実行（オプションで制御）
 */
add_action('admin_init', function() {
	// 自動削除が有効かチェック（オプションで制御）
	$auto_delete_enabled = get_option('swell_child_auto_delete_menu_items', false);
	
	// 既に実行済みかチェック
	$already_executed = get_option('swell_child_menu_items_deleted', false);
	
	// 自動削除が有効で、まだ実行されていない場合のみ実行
	if ($auto_delete_enabled && !$already_executed) {
		$deleted_count = swell_child_delete_excluded_menu_items();
		if ($deleted_count > 0) {
			update_option('swell_child_menu_items_deleted', true);
			// 管理者に通知
			add_action('admin_notices', function() use ($deleted_count) {
				echo '<div class="notice notice-success is-dismissible"><p>';
				echo esc_html($deleted_count) . '件の会員向けメニュー項目を自動的に削除しました。';
				echo '</p></div>';
			});
		}
	}
});

/**
 * メニュー整理ページ
 */
function swell_child_menu_cleanup_page() {
	if (isset($_POST['delete_menu_items']) && check_admin_referer('swell_child_delete_menu_items')) {
		$deleted_count = swell_child_delete_excluded_menu_items();
		if ($deleted_count !== false) {
			echo '<div class="notice notice-success"><p>' . esc_html($deleted_count) . '件のメニュー項目を削除しました。</p></div>';
		} else {
			echo '<div class="notice notice-error"><p>メニュー項目の削除に失敗しました。</p></div>';
		}
	}
	
	// 削除対象の項目を確認
	$exclude_keywords = swell_child_get_exclude_keywords();
	$exclude_paths = array(
		'/member-login/', '/member-area/', '/member-profile/', '/profile/',
		'/password-reset/', '/reset-password/', '/wp-login.php',
		'/wp-register.php', '/register/', '/thank-you/', '/thankyou/',
		'/join/', '/join-us/', '/member/', '/members/'
	);
	
	$menu_locations = get_nav_menu_locations();
	$header_menu_id = isset($menu_locations['header_menu']) ? $menu_locations['header_menu'] : null;
	$menu_items = $header_menu_id ? wp_get_nav_menu_items($header_menu_id) : array();
	
	$items_to_delete = array();
	if ($menu_items) {
		foreach ($menu_items as $item) {
			$should_delete = false;
			$reason = '';
			
			if (empty($item->title) || empty($item->url) || $item->url === '#') {
				$should_delete = true;
				$reason = '空の項目';
			} elseif (strpos(strtolower($item->url), '/mitus') !== false || strpos(strtolower($item->url), 'mitus') !== false) {
				$should_delete = true;
				$reason = 'MITUS関連';
			} else {
				$item_url_lower = strtolower($item->url);
				foreach ($exclude_paths as $path) {
					if (strpos($item_url_lower, $path) !== false) {
						$should_delete = true;
						$reason = '会員向けページ: ' . $path;
						break;
					}
				}
				if (!$should_delete) {
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
			}
			
			if ($should_delete) {
				$items_to_delete[] = array(
					'title' => $item->title,
					'url' => $item->url,
					'reason' => $reason
				);
			}
		}
	}
	?>
	<div class="wrap">
		<h1>ヘッダーメニュー整理</h1>
		<p>ヘッダーメニューから会員向け項目を完全に削除します。</p>
		
		<?php if (!empty($items_to_delete)): ?>
			<div class="card">
				<h2>削除対象の項目（<?php echo count($items_to_delete); ?>件）</h2>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th>タイトル</th>
							<th>URL</th>
							<th>削除理由</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items_to_delete as $item): ?>
							<tr>
								<td><?php echo esc_html($item['title']); ?></td>
								<td><?php echo esc_html($item['url']); ?></td>
								<td><?php echo esc_html($item['reason']); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				
				<form method="post" style="margin-top: 20px;">
					<?php wp_nonce_field('swell_child_delete_menu_items'); ?>
					<input type="submit" name="delete_menu_items" class="button button-primary" value="上記の項目を削除する" onclick="return confirm('本当に削除しますか？この操作は取り消せません。');">
				</form>
			</div>
		<?php else: ?>
			<div class="notice notice-info">
				<p>削除対象のメニュー項目は見つかりませんでした。</p>
			</div>
		<?php endif; ?>
		
		<div class="card" style="margin-top: 20px;">
			<h2>削除対象の条件</h2>
			<ul>
				<li>空のメニュー項目（タイトルまたはURLが空）</li>
				<li>MITUS関連の項目</li>
				<li>会員向けページパスを含む項目</li>
				<li>会員向けキーワードを含む項目</li>
			</ul>
			
			<?php if (empty($items_to_delete)): ?>
				<p style="margin-top: 15px;"><strong>✓ 削除対象のメニュー項目は見つかりませんでした。既に整理済みです。</strong></p>
			<?php else: ?>
				<p style="margin-top: 15px;"><strong>⚠️ 上記の項目を削除することをお勧めします。</strong></p>
			<?php endif; ?>
			
			<p><a href="<?php echo admin_url('nav-menus.php'); ?>">メニュー管理画面に戻る</a></p>
		</div>
		
		<div class="card" style="margin-top: 20px;">
			<h2>自動削除設定</h2>
			<?php
			$auto_delete_enabled = get_option('swell_child_auto_delete_menu_items', false);
			if (isset($_POST['toggle_auto_delete']) && check_admin_referer('swell_child_toggle_auto_delete')) {
				$auto_delete_enabled = !$auto_delete_enabled;
				update_option('swell_child_auto_delete_menu_items', $auto_delete_enabled);
				if ($auto_delete_enabled) {
					// 自動削除を有効にした場合、即座に実行
					$deleted_count = swell_child_delete_excluded_menu_items();
					if ($deleted_count > 0) {
						update_option('swell_child_menu_items_deleted', true);
						echo '<div class="notice notice-success"><p>' . esc_html($deleted_count) . '件のメニュー項目を削除しました。</p></div>';
					}
				}
			}
			?>
			<form method="post">
				<?php wp_nonce_field('swell_child_toggle_auto_delete'); ?>
				<p>
					<label>
						<input type="checkbox" name="toggle_auto_delete" value="1" <?php checked($auto_delete_enabled, true); ?>>
						会員向けメニュー項目を自動的に削除する（推奨）
					</label>
				</p>
				<p class="description">
					このオプションを有効にすると、会員向けメニュー項目が自動的に削除されます。
					初回のみ実行され、その後は手動で削除する必要があります。
				</p>
				<?php if ($auto_delete_enabled): ?>
					<p><input type="submit" class="button" value="自動削除を無効化"></p>
				<?php else: ?>
					<p><input type="submit" class="button button-primary" value="自動削除を有効化して実行"></p>
				<?php endif; ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * 会員ログインボタンかどうかをチェック
 */
function swell_child_is_member_login_button($item_url, $item_title) {
	return (
		strpos($item_url, '/member-login/') !== false && 
		(strpos($item_title, '会員ログイン') !== false || 
		 strpos($item_title, 'ログアウト') !== false ||
		 strpos($item_title, 'member-login') !== false)
	);
}

/**
 * 会員登録ボタンかどうかをチェック
 */
function swell_child_is_register_button($item_url, $item_title) {
	return (
		strpos($item_url, 'wp-register.php') !== false ||
		strpos($item_url, 'wp-login.php?action=register') !== false ||
		(strpos($item_url, '/register') !== false && strpos($item_title, '会員登録') !== false)
	);
}

/**
 * ヘッダーメニューから特定の項目を完全に削除する
 * パスワードのリセット、Thank You、Join Us、登録、リセット、プロフィール、MITUS
 * ヘッダーメニュー（header_menu）のみに適用
 * 一般ユーザー向けメニュー項目のみを表示
 */
add_filter('wp_nav_menu_objects', function($items, $args) {
	// ヘッダーメニュー（header_menu）のみに適用
	if (empty($items) || !is_array($items)) {
		return $items;
	}
	
	// テーマロケーションがheader_menuでない場合はそのまま返す
	if (isset($args->theme_location) && $args->theme_location !== 'header_menu') {
		return $items;
	}
	
	$exclude_keywords = swell_child_get_exclude_keywords();
	
	// 各メニュー項目をチェック
	foreach ($items as $key => $item) {
		if (!isset($item->url) || !isset($item->title)) {
			// 空のメニュー項目を削除
			unset($items[$key]);
			continue;
		}
		
		// 空のタイトルやURLの項目を削除
		$item_title = trim($item->title);
		$item_url = trim($item->url);
		if (empty($item_title) || empty($item_url) || $item_url === '#') {
			unset($items[$key]);
			continue;
		}
		
		$item_url_lower = strtolower($item_url);
		$item_title_lower = strtolower($item_title);
		
		// MITUSへのリンクを完全に削除
		if (strpos($item_url_lower, '/mitus') !== false || strpos($item_url_lower, 'mitus') !== false) {
			unset($items[$key]);
			continue;
		}
		
		// 会員向けページを削除（会員ログインボタンはswl_parts__gnavで別途表示）
		$member_pages = array(
			'/member-login/', '/member-area/', '/member-profile/', '/profile/',
			'/password-reset/', '/reset-password/', '/wp-login.php',
			'/wp-register.php', '/register/', '/thank-you/', '/thankyou/',
			'/join/', '/join-us/', '/member/', '/members/'
		);
		$is_member_page = false;
		foreach ($member_pages as $member_page) {
			if (strpos($item_url_lower, $member_page) !== false) {
				$is_member_page = true;
				break;
			}
		}
		if ($is_member_page) {
			unset($items[$key]);
			continue;
		}
		
		// キーワードが含まれているかチェック
		foreach ($exclude_keywords as $keyword) {
			$keyword_lower = strtolower($keyword);
			if (strpos($item_url_lower, $keyword_lower) !== false || strpos($item_title_lower, $keyword_lower) !== false) {
				// 該当する項目を削除
				unset($items[$key]);
				break;
			}
		}
	}
	
	// 配列のインデックスを再設定
	return array_values($items);
}, 999, 2);

/**
 * walker_nav_menu_start_elフィルターでメニュー項目を削除
 * ヘッダーメニュー（header_menu）のみに適用
 * より確実にメニュー項目を削除する
 */
add_filter('walker_nav_menu_start_el', function($item_output, $item, $depth, $args) {
	// ヘッダーメニュー（header_menu）のみに適用
	if (isset($args->theme_location) && $args->theme_location !== 'header_menu') {
		return $item_output;
	}
	
	if (!isset($item->url) || !isset($item->title)) {
		return '';
	}
	
	// 空のタイトルやURLの項目を削除
	$item_title = trim($item->title);
	$item_url = trim($item->url);
	if (empty($item_title) || empty($item_url) || $item_url === '#') {
		return '';
	}
	
	$item_url_lower = strtolower($item_url);
	$item_title_lower = strtolower($item_title);
	
	// MITUSへのリンクを完全に削除
	if (strpos($item_url_lower, '/mitus') !== false || strpos($item_url_lower, 'mitus') !== false) {
		return '';
	}
	
	// 会員向けページを削除
	$member_pages = array(
		'/member-login/', '/member-area/', '/member-profile/', '/profile/',
		'/password-reset/', '/reset-password/', '/wp-login.php',
		'/wp-register.php', '/register/', '/thank-you/', '/thankyou/',
		'/join/', '/join-us/', '/member/', '/members/'
	);
	foreach ($member_pages as $member_page) {
		if (strpos($item_url_lower, $member_page) !== false) {
			return '';
		}
	}
	
	$exclude_keywords = swell_child_get_exclude_keywords();
	
	// キーワードが含まれているかチェック
	foreach ($exclude_keywords as $keyword) {
		$keyword_lower = strtolower($keyword);
		if (strpos($item_url_lower, $keyword_lower) !== false || strpos($item_title_lower, $keyword_lower) !== false) {
			// 該当する項目を空文字列で返して削除
			return '';
		}
	}
	
	return $item_output;
}, 999, 4);

/**
 * カスタマイザーでのウィジェットエラー修正
 * sidebars_widgetsオプションが文字列の場合、配列に変換する（統合版）
 */
function swell_child_convert_widgets_to_array($value) {
	if (is_string($value) && !empty($value)) {
		$decoded = json_decode($value, true);
		if (is_array($decoded)) {
			return $decoded;
		}
		return array();
	}
	return $value;
}
add_filter('option_sidebars_widgets', 'swell_child_convert_widgets_to_array', 1);
add_filter('sidebars_widgets', 'swell_child_convert_widgets_to_array', 1);

/**
 * ログイン後のリダイレクト先を設定
 * 存在しないページへのリダイレクトを防ぐため、ホームページにリダイレクト
 * ログイン成功メッセージのクエリパラメータを追加
 */
add_filter('login_redirect', function($redirect_to, $requested_redirect_to, $user) {
	$final_redirect = $redirect_to;
	
	// リダイレクト先が存在しないページの場合はホームページにリダイレクト
	if (!empty($requested_redirect_to)) {
		// URLが存在するか確認
		$parsed_url = parse_url($requested_redirect_to);
		if (!empty($parsed_url['path'])) {
			$path = trim($parsed_url['path'], '/');
			// member-areaなど存在しないページへのリダイレクトを防止
			if ($path === 'member-area' || strpos($path, 'member-area') !== false) {
				$final_redirect = home_url('/');
			} else {
				// ページが存在するか確認
				$page = get_page_by_path($path);
				if (!$page || $page->post_status !== 'publish') {
					$final_redirect = home_url('/');
				}
			}
		}
	}
	// デフォルトはホームページにリダイレクト
	if (empty($final_redirect) || $final_redirect === admin_url()) {
		$final_redirect = home_url('/');
	}
	
	// ログイン成功メッセージのクエリパラメータを追加
	if (strpos($final_redirect, '?') !== false) {
		$final_redirect .= '&login=success';
	} else {
		$final_redirect .= '?login=success';
	}
	
	return $final_redirect;
}, 10, 3);

/**
 * ログアウト後のリダイレクト先を会員ログインページに設定
 */
add_filter('logout_redirect', function($redirect_to, $requested_redirect_to, $user) {
	// 会員ログインページのURLを取得
	$login_page = get_page_by_path('member-login');
	if ($login_page && $login_page->post_status === 'publish') {
		return get_permalink($login_page->ID);
	}
	// 会員ログインページが存在しない場合はデフォルトのログインページへ
	return home_url('/member-login/');
}, 10, 3);

/**
 * ログイン成功メッセージを表示
 * ページ上部に通知バナーを表示
 */
add_action('wp_footer', function() {
	if (is_user_logged_in() && isset($_GET['login']) && $_GET['login'] === 'success') {
		$current_user = wp_get_current_user();
		?>
		<div id="login-success-notice" class="login-success-notice">
			<div class="login-success-notice__inner">
				<span class="login-success-notice__icon">✓</span>
				<span class="login-success-notice__message">
					<?php echo esc_html($current_user->display_name ?: $current_user->user_login); ?>さん、ログインしました。
				</span>
				<button class="login-success-notice__close" onclick="this.parentElement.parentElement.remove()">×</button>
			</div>
		</div>
		<script>
			// 5秒後に自動的に閉じる
			setTimeout(function() {
				const notice = document.getElementById('login-success-notice');
				if (notice) {
					notice.style.opacity = '0';
					notice.style.transform = 'translateY(-100%)';
					setTimeout(function() {
						notice.remove();
					}, 300);
				}
			}, 5000);
		</script>
		<?php
	}
});