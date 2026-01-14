<?php
/**
 * GT-NET カスタムフッター
 * GT-NETホームページの場合はカスタムフッターを表示
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// GT-NETホームページの場合はカスタムフッターを使用
if (is_front_page() || is_page_template('front-page.php')) {
	// Barba用 wrapper の終了
	$SETTING = SWELL_Theme::get_setting();
	if ( SWELL_Theme::is_use( 'pjax' ) ) {
		echo '</div>'; // End : Barba[data-barba="container"]
	}
	?>
	</div><!-- /#content -->
	<?php
	// GT-NET カスタムフッター
	get_template_part('parts/footer/footer_contents');
	?>
	</div><!-- /#body_wrap -->
	<?php
	wp_footer();
	?>
	</body>
	</html>
	<?php
	return;
}

// 通常のページは親テーマのフッターを使用
require_once get_template_directory() . '/footer.php';
