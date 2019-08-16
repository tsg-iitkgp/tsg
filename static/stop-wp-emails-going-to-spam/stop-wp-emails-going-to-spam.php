<?php

/**
 *
 *
 *
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 *
 * Plugin Name:       Stop WP Emails Going to Spam
 * Plugin URI:        http://fullworks.net/wordpress-plugins/stop-wp-emails-going-to-spam/
 * Description:       When using PHP mail - WordPress generated emails often go to spam, this plugin gives you some simple tools to help stop that
 * Version:           1.1.2
 * Author:            Fullworks
 * Author URI:        http://fullworks.net/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       stop-wp-emails-going-to-spam
 * Domain Path:       /languages
 *
 * @package stop-wp-emails-going-to-spam
 */

namespace Stop_Wp_Emails_Going_To_Spam;

use \Stop_Wp_Emails_Going_To_Spam\Includes\Core;
use \Stop_Wp_Emails_Going_To_Spam\Includes\Freemius_Config;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
if (!function_exists('Stop_Wp_Emails_Going_To_Spam\run_Stop_Wp_Emails_Going_To_Spam')) {
    define('STOP_WP_EMAILS_GOING_TO_SPAM_PLUGIN_DIR', plugin_dir_path(__FILE__));
    define('STOP_WP_EMAILS_GOING_TO_SPAM_PLUGIN_VERSION', '1.1.2');

// Include the autoloader so we can dynamically include the classes.
    require_once STOP_WP_EMAILS_GOING_TO_SPAM_PLUGIN_DIR . 'includes/autoloader.php';

    /**
     * Begins execution of the plugin.
     */
    function run_Stop_Wp_Emails_Going_To_Spam()
    {
        /**
         *  Load freemius SDK
         */
        $freemius = new Freemius_Config();
        $freemius = $freemius->init();
        // Signal that SDK was initiated.
        do_action('stop_wp_emails_going_to_spam_fs_loaded');

	    /**
	     * The code that runs during plugin activation.
	     * This action is documented in includes/class-activator.php
	     */
	    register_activation_hook(__FILE__, array('\Stop_Wp_Emails_Going_To_Spam\Includes\Activator', 'activate'));



	    /**
	     * The code that runs during plugin uninstall.
	     * This action is documented in includes/class-uninstall.php
	     * * use freemius hook
	     *
	     * @var \Freemius $freemius freemius SDK.
	     */
	    $freemius->add_action('after_uninstall', array('\Stop_Wp_Emails_Going_To_Spam\Includes\Uninstall', 'uninstall'));

        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        $plugin = new Core($freemius);
        $plugin->run();
    }

    run_Stop_Wp_Emails_Going_To_Spam();
}  else {
    die( __( 'Cannot execute as the plugin already exists', 'stop-wp-emails-going-to-spam' ) );
}

