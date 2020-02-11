<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://scriptburn.com
 * @since      2.0
 *
 * @package    wp_hide_post
 * @subpackage wp_hide_post/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wp_hide_post
 * @subpackage wp_hide_post/admin
 * @author     ScriptBurn <support@scriptburn.com>
 */
class wp_hide_post_DB_Update
{

    /**
     * The ID of this plugin.
     *
     * @since    1.2.2
     * @access   private
     * @var      string    $wp_hide_post    The ID of this plugin.
     */
    private $wp_hide_post;

    /**
     * The version of this plugin.
     *
     * @since    1.2.2
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initiates db update process
     *
     * @since    1.2.2
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    public function db_update()
    {
        p_l("db_update");
        // no PHP timeout for running updates
        //set_time_limit(0);

        $db_updated = false;
        // this is the current database schema version number

        $current_db_ver = (int) get_option('wphp_db_ver');

        // this is the target version that we need to reach
        $target_db_ver = WPHP_DB_VER;

        // error_log("$current_db_ver < $target_db_ver");
        //$loop;
        while ($current_db_ver < $target_db_ver)
        {

            // increment the current db_ver by one
            $current_db_ver++;

            // each db version will require a separate update function
            $func = "update_to_ver_{$current_db_ver}";
            p_l("calling $func");
            if (method_exists($this, $func))
            {
                $ret =  call_user_func_array(array($this, $func), array());
                if ($ret && !$db_updated)
                {
                    $db_updated = true;
                }
            }

            // update the option in the database, so that this process can always
            // pick up where it left off

            update_option('wphp_db_ver', $current_db_ver);
        }
        return $db_updated;
    }

    /**
     * Migrate to the new database schema and clean up old schema...
     * Should run only once in the lifetime of the plugin...
     * @return unknown_type
     */

    public function update_to_ver_1()
    {
        global $wpdb;
        p_l("called:update_to_ver_1");
        /* When I first released this plugin, I was young and crazy and didn't know about the postmeta table.
         * With time I became wiser and wiser and decided to migrate the implementation to rely on postmeta.
         * I hope it was not a bad idea...
         */
        global $wpdb;
        global $table_prefix;
        $dbname = $wpdb->get_var("SELECT database()");
        if (!$dbname)
        {
            return;
        }

        $legacy_table_name   = "${table_prefix}lowprofiler_posts";
        $legacy_table_exists = $wpdb->get_var("SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '$legacy_table_name';");
        if ($legacy_table_exists)
        {
            p_l("Migrating legacy table...");
            // move everything to the postmeta table
            $existing = $wpdb->get_results("SELECT wplp_post_id, wplp_flag, wplp_value from $legacy_table_name", ARRAY_N);
            // scan them one by one and insert the corresponding fields in the postmeta table
            $count = 0;
            foreach ($existing as $existing_array)
            {
                $wplp_post_id = $existing_array[0];
                $wplp_flag    = $existing_array[1];
                $wplp_value   = $existing_array[2];
                if ($wplp_flag == 'home')
                {
                    $wplp_flag = 'front';
                }

                if ($wplp_value == 'home')
                {
                    $wplp_value = 'front';
                }

                if ($wplp_flag != 'page')
                {
                    $wpdb->query("INSERT INTO " . WPHP_TABLE_NAME . "(post_id, meta_key, meta_value) VALUES($wplp_post_id, '_wplp_post_$wplp_flag', '1')");
                }
                else
                {
                    $wpdb->query("INSERT INTO " . WPHP_TABLE_NAME . "(post_id, meta_key, meta_value) VALUES($wplp_post_id, '_wplp_page_flags', $wplp_value)");
                }
                ++$count;
            }
            p_l("$count entries migrated from legacy table.");
            // delete the old table
            $wpdb->query("TRUNCATE TABLE $legacy_table_name");
            $wpdb->query("DROP TABLE $legacy_table_name");
            p_l("Legacy table deleted.");
        }
        return true;
    }
    public function update_to_ver_2()
    {
        p_l("in update_to_ver_2");
        global $wpdb;
        $results = $wpdb->get_results("select * from $wpdb->postmeta where meta_key='_wplp_page_flags' ", ARRAY_A);
        if ($results)
        {
            foreach ($results as $result)
            {
                //p_l($result);
                if ($result['meta_value'] == 'all')
                {
                    p_l("all");
                    $meta_old = get_post_meta($result['post_id'], '_wplp_page_search', true);

                    if ($meta_old)
                    {
                        //Hide everywhere pages are listed.
                        p_l("always");
                        $new_meta_name = WPHP_META_VALUE_PREFIX . 'hide_always';

                        update_post_meta($result['post_id'], $new_meta_name, '1');

                    }
                    else
                    {
                        //Hide everywhere pages are listed. but Keep in search results.
                        p_l("always no search");
                        $new_meta_name = WPHP_META_VALUE_PREFIX . 'nohide_search';
                        update_post_meta($result['post_id'], $new_meta_name, '1');

                    }
                }
                elseif ($result['meta_value'] == 'front')
                {
                    p_l("front");

                    $new_meta_name = WPHP_META_VALUE_PREFIX . 'hide_frontpage';

                    update_post_meta($result['post_id'], $new_meta_name, '1');

                }

            }
        }
    }
}
