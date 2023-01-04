<?php

/**
 * Plugin Name:       Shoe Size Converter
 * Description:       Converts variable attribute shoe sizes to user selected regional size
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            WC Bessinger
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ss-conv
 */

defined('ABSPATH') || exit();

// Plugins loaded
add_action('plugins_loaded', function () {

    // constants
    define('SS_CONV_PATH', plugin_dir_path(__FILE__));
    define('SS_CONV_URL', plugin_dir_url(__FILE__));

    // core class
    include SS_CONV_PATH . 'inc/class_ss_conv.php';
});
