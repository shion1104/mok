<?php
/**
 * Blocksy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blocksy
 */

if (version_compare(PHP_VERSION, '5.7.0', '<')) {
	require get_template_directory() . '/inc/php-fallback.php';
	return;
}

require get_template_directory() . '/inc/init.php';

// ローカル環境の余白をなくす
add_action('wp_head', function() {
	if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
		echo '<style>
			:root {
				--theme-container-edge-spacing: 100vw !important;
			}
			body {
				margin: 0 !important;
				padding: 0 !important;
			}
			#main-container {
				margin: 0 !important;
				padding: 0 !important;
			}
			.ct-container-fluid {
				width: 100vw !important;
			}
		</style>';
	}
}, 999);

