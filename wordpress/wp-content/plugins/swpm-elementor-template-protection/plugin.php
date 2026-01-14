<?php

/**
 * Plugin Name: SWPM - Elementor Template Protection
 * Description: This plugin allows you to make any Elementor template responsive members log-in status and to any users's specific membership level (*use of Simple Membership* plugin)
 * Version:     1.0.0
 * Author:      SvilApp S.R.L.
 * Author URI:  https://svilapp.it/
 * Text Domain: swpm-elementor-template-protection
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once __DIR__ . '/includes/ElementorSimpleMembershipExtension.php';

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function (array $actions): array {
    $actions[] = '<a href="https://svilapp.it" target="_blank">' . __('Try PRO Version', 'swpm-elementor-template-protection') . '</a>';

    return $actions;
});