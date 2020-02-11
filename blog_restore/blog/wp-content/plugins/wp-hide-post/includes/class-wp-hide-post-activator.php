<?php

/**
 * Fired during plugin activation
 *
 * @link       http://scriptburn.com
 * @since      2.0
 *
 * @package    wp_hide_post
 * @subpackage wp_hide_post/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0
 * @package    wp_hide_post
 * @subpackage wp_hide_post/includes
 * @author     ScriptBurn <support@scriptburn.com>
 */
class wp_hide_post_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.2.2
     */
    public function activate()
    {
        delete_transient('wphp_notices_footer');
        delete_transient('wphp_notices_widget');

        if(!get_option('wphp_support_hash'))
        {
            add_option('wphp_support_hash',base64_encode(wp_generate_password(32,true,false)));
        }
        $this->remove_wp_low_profiler();

        require_once WPHP_PLUGIN_DIR . 'admin/class-wp-hide-post-dbupdate.php';
        $db_updater = new wp_hide_post_DB_Update();
        $db_updater->update_to_ver_2();
    }

/**
 *
 * @return unknown_type
 */
    public function remove_wp_low_profiler()
    {
        p_l("called: wphp_remove_wp_low_profiler");
        $plugin_list = get_plugins('/wp-low-profiler');
        if (isset($plugin_list['wp-low-profiler.php']))
        {
            p_l("The 'WP low Profiler' plugin is present. Cleaning it up...");
            $plugins = array('wp-low-profiler/wp-low-profiler.php');
            if (is_plugin_active('wp-low-profiler/wp-low-profiler.php'))
            {
                p_l("The 'WP low Profiler' plugin is active. Deactivating...");
                deactivate_plugins($plugins, true); // silent deactivate
            }
            p_l("Deleting plugin 'WP low Profiler'...");
            delete_plugins($plugins, '');
        }
        else
        {
            p_l("The 'WP low Profiler' plugin does not exist.");
        }

    }

}
