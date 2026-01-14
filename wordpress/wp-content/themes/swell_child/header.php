<?php
/**
 * GT-NET カスタムヘッダー
 * GT-NETホームページの場合はカスタムヘッダーを表示
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// GT-NETホームページの場合はカスタムヘッダーを使用
if (is_front_page() || is_page_template('front-page.php')) {
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?> <?php SWELL_Theme::root_attrs(); ?>>
	<head>
	<meta charset="utf-8">
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, viewport-fit=cover">
	<?php wp_head(); ?>
	</head>
	<body>
	<?php if ( function_exists( 'wp_body_open' ) ) wp_body_open(); ?>
	<div id="body_wrap" <?php body_class(); ?> <?php SWELL_Theme::body_attrs(); ?>>
	<?php
	// GT-NET カスタムヘッダー
	get_template_part('parts/header/header_contents');
	?>
	<div id="content" class="l-content">
	<?php
	// Barba用 wrapper
	$SETTING = SWELL_Theme::get_setting();
	if ( SWELL_Theme::is_use( 'pjax' ) ) {
		echo '<div data-barba="container" data-barba-namespace="home">';
	}
	return;
}

// 通常のページは親テーマのヘッダーを使用
require_once get_template_directory() . '/header.php';
