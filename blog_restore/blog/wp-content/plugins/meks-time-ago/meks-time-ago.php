<?php
/*
Plugin Name: Meks Time Ago
Plugin URI: https://mekshq.com
Description: Automatically change your post date display to "time ago" format like "1 hour ago", "3 weeks ago", "2 months ago" etc...
Version: 1.1.5
Author: Meks
Author URI: https://mekshq.com
Text Domain: meks-time-ago
Domain Path: /languages
*/

/* Prevent Direct access */
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/* Define BaseName */
if ( !defined( 'MEKS_TA_BASENAME' ) )
	define( 'MEKS_TA_BASENAME', plugin_basename( __FILE__ ) );

/* Define internal path */
if ( !defined( 'MEKS_TA_PATH' ) )
	define( 'MEKS_TA_PATH', plugin_dir_path( __FILE__ ) );

/* Define internal version for possible update changes */
define( 'MEKS_TA_VER', '1.1.5' );

/* Load Up the text domain */
function meks_ta_load_textdomain() {
	load_plugin_textdomain( 'meks-time-ago', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'meks_ta_load_textdomain' );

/* Check if we're running compatible software */
if ( version_compare( PHP_VERSION, '5.2', '<' ) && version_compare( WP_VERSION, '3.7', '<' ) ) {
	if ( is_admin() ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
		wp_die( __( 'Meks Time Ago plugin requires WordPress 3.8 and PHP 5.3 or greater. The plugin has now disabled itself', 'meks-time-ago' ) );
	}
}

/* Let's load up our plugin */

function meks_ta_admin_init() {
	require_once MEKS_TA_PATH.'includes/class.backend.php';
	new Meks_TA_Admin();
}

function meks_ta_frontend_init() {
	require_once MEKS_TA_PATH.'includes/class.frontend.php';
	new Meks_TA_Frontend();
}

if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX) ) {

    add_action('loop_start', 'meks_ta_frontend_init', 50);

} else {

    add_action('plugins_loaded', 'meks_ta_admin_init', 15);

}

?>