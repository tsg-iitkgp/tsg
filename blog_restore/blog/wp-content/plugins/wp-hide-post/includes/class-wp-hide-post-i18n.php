<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://scriptburn.com
 * @since      2.0
 *
 * @package    wp_hide_post
 * @subpackage wp_hide_post/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0
 * @package    wp_hide_post
 * @subpackage wp_hide_post/includes
 * @author     ScriptBurn <support@scriptburn.com>
 */
class wp_hide_post_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.2.2
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-hide-post',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
