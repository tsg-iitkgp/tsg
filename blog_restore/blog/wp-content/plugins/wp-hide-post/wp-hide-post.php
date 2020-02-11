<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.scriptburn.com
 * @since             1.0.0
 * @package           wp_hide_post
 *
 * @wordpress-plugin
 * Plugin Name:       WP Hide Post
 * Plugin URI:        http://scriptburn.com/wp-hide-post
 * Description:       Control the visibility of items on your blog by making posts/pages hidden on some parts , while still visible in other parts and search engines.
 * Version:           2.0.10
 * Author:            scriptburn.com
 * Author URI:        http://www.scriptburn.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp_hide_post
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC'))
{
    die;
}
global $table_prefix;
define('WPHP_VER', "2.0.10");
define('WPHP_DB_VER', "2");

define('WPHP_PLUGIN_DIR', __DIR__ . "/");
define('WPHP_PLUGIN_FILE',__FILE__);

define('WPHP_PLUGIN_URL',plugin_dir_url(WPHP_PLUGIN_FILE));


if (!defined('WPHP_TABLE_NAME'))
{
    define('WPHP_TABLE_NAME', "${table_prefix}postmeta");
}

if (!defined('WP_POSTS_TABLE_NAME'))
{
    define('WP_POSTS_TABLE_NAME', "${table_prefix}posts");
}

if (!defined('WPHP_DEBUG'))
{
    define('WPHP_DEBUG', defined('WP_DEBUG') && WP_DEBUG ? 1 : 0);
}

if (!defined('WPHP_META_VALUE_PREFIX'))
{
    define('WPHP_META_VALUE_PREFIX', '_wplp_');
}
if (!defined('WPHP_VISIBILITY_NAME'))
{
    define('WPHP_VISIBILITY_NAME', 'wphp_visibility_type');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-hide-post-activator.php
 */
function activate_wp_hide_post()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-hide-post-activator.php';
    $wphp = new wp_hide_post_Activator();
    $wphp->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-hide-post-deactivator.php
 */
function deactivate_wp_hide_post()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-hide-post-deactivator.php';
    $wphp = new wp_hide_post_Deactivator();
    $wphp->deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_hide_post');
register_deactivation_hook(__FILE__, 'deactivate_wp_hide_post');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-hide-post.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.2.2
 */
function run_wp_hide_post()
{

    $plugin = wp_hide_post::getInstance() ;
    $plugin->run();

}
run_wp_hide_post();
