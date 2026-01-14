<?php
/**
 * GT-NET カスタムヘッダー
 * モックプレビューのデザインに合わせたヘッダー
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$is_front_page = is_front_page() || is_page_template('front-page.php');
?>
<header id="gtnet-header" class="gtnet-header fixed w-full z-50 transition-all duration-300 gtnet-header-transparent">
	<div class="container mx-auto px-6 flex justify-between items-center">
		<!-- ロゴ -->
		<a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gtnet-logo">
			<?php
			// ロゴのパスを確認（WordPressカスタマイザー → テーマ内logo.png の優先順）
			$logo_url = '';
			
			// まずWordPressのカスタマイザーからロゴを取得
			$custom_logo_id = get_theme_mod('custom_logo');
			if ($custom_logo_id) {
				$logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
			}
			
			// カスタマイザーにロゴがない場合、子テーマ直下の logo.png を使用
			if (!$logo_url) {
				$theme_logo_path = get_stylesheet_directory() . '/logo.png';
				if (file_exists($theme_logo_path)) {
					$logo_url = get_stylesheet_directory_uri() . '/logo.png';
				}
			}
			
			if ($logo_url) {
				echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="h-10 md:h-12 w-auto transition-all gtnet-logo-img" />';
			} else {
				echo '<span class="text-xl md:text-2xl font-bold">' . esc_html(get_bloginfo('name')) . '</span>';
			}
			?>
		</a>

		<!-- PCナビゲーション -->
		<nav class="hidden md:flex items-center gap-6 gtnet-nav">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="text-sm font-bold hover:text-blue-500 transition-colors gtnet-nav-link">ホーム</a>
			<a href="<?php echo esc_url(home_url('/services/')); ?>" class="text-sm font-bold hover:text-blue-500 transition-colors gtnet-nav-link">事業紹介</a>
			<a href="<?php echo esc_url(home_url('/archives/?category=column')); ?>" class="text-sm font-bold hover:text-blue-500 transition-colors gtnet-nav-link">コラム</a>
			<a href="<?php echo esc_url(wp_login_url()); ?>" class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all gtnet-login-btn">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
				</svg>
				会員ログイン
			</a>
			<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/20">お問い合わせ</a>
		</nav>

		<!-- モバイルメニューボタン -->
		<button class="md:hidden gtnet-mobile-menu-btn gtnet-nav-link" aria-label="メニューを開く">
			<svg class="w-6 h-6 gtnet-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
			</svg>
			<svg class="w-6 h-6 gtnet-close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>
	</div>
</header>

<!-- モバイルメニュー -->
<div id="gtnet-mobile-menu" class="gtnet-mobile-menu fixed inset-0 bg-slate-900 text-white z-[60] p-8 flex flex-col gap-6 hidden">
	<button class="self-end gtnet-mobile-close" aria-label="メニューを閉じる">
		<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
		</svg>
	</button>
	<nav class="flex flex-col gap-6 text-3xl font-black">
		<a href="<?php echo esc_url(home_url('/')); ?>" class="gtnet-mobile-link">ホーム</a>
		<a href="<?php echo esc_url(home_url('/services/')); ?>" class="gtnet-mobile-link">事業紹介</a>
		<a href="<?php echo esc_url(home_url('/archives/?category=column')); ?>" class="gtnet-mobile-link">コラム</a>
		<a href="<?php echo esc_url(home_url('/contact/')); ?>" class="text-blue-400 gtnet-mobile-link">お問い合わせ</a>
	</nav>
	<div class="mt-auto pt-8 border-t border-white/10">
		<a href="<?php echo esc_url(wp_login_url()); ?>" class="flex items-center gap-3 bg-white/10 px-6 py-4 rounded-2xl text-lg font-bold hover:bg-white/20 transition-all gtnet-mobile-link">
			<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
			</svg>
			会員ログイン
		</a>
	</div>
</div>
