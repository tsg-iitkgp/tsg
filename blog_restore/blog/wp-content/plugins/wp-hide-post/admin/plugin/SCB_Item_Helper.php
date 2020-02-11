<?php

if (!class_exists('SCB_Item_Helper'))
{

    class SCB_Item_Helper
    {
        private $item_file_name = "";
        private $store          = "";
        private $item_arr       = array();
        private $license_key    = "";
        private $item_type      = 'plugin';
        private $license_status = '';
        private $transient_name = 'scb_lic_data';

        public function __construct($store, $item_file_name, $license_key, $item_type = 'plugin', $license_status)
        {
            global $pagenow;
            $custom_name = substr($item_type, 7);
            $is_custom   = substr($item_type, 0, 7) == 'custom_' && strlen($custom_name) > 0;
            $valid_items = array('plugin', 'theme');
            if (!in_array($item_type, $valid_items))
            {
                if (!$is_custom)
                {
                    throw new Exception('Invalid tem Type');
                    return false;
                }
            }
            if (substr($item_file_name, 0, strlen(WP_PLUGIN_DIR)) == WP_PLUGIN_DIR)
            {
                $this->item_file_name = ltrim(substr($item_file_name, strlen(WP_PLUGIN_DIR)), "/");
            }
            else
            {
                $this->item_file_name = $item_file_name;
            }
            $this->store          = $store;
            $this->item_type      = $item_type;
            $this->license_key    = $license_key;
            $this->license_status = $license_status;
            //$this->debug('license', __FUNCTION__ . "-" . __LINE__, 'init');

            if (!$is_custom)
            {
                add_action('admin_init', array($this, $item_type . '_updater'));
            }
            else
            {
                call_user_function($custom_name . "_updater");

            }
            $update_cache = get_site_transient('update_plugins');
             if (  isset($update_cache->response[$this->item_file_name]) &&  ($update_cache->response[$this->item_file_name]->upgrade_notice)  )
            {
                $upgrade_notice=$update_cache->response[$this->item_file_name]->upgrade_notice;
                $hook   = "in_plugin_update_message-" . $this->item_file_name;
                add_action($hook, function ($plugin_data, $r) use($upgrade_notice)
                {

                    echo($upgrade_notice);

                }, 20, 2);
            }

        }
        public function get_license_key()
        {
            return $this->license_key;

        }
        public function refresh_plugin_data()
        {
            $default = array('version' => "0", 'license' => "", "item_name" => '', "author" => "");

            try {
                $plugin_file = WP_PLUGIN_DIR . "/" . $this->item_file_name;
                // $this->debug('license', __FUNCTION__ . "-" . __LINE__, $plugin_file);
                if (!function_exists('get_plugin_data'))
                {
                    include_once ABSPATH . "wp-admin/includes/plugin.php";
                }
                //$this->debug('license', __FUNCTION__ . "-" . __LINE__,  $d);

                $this->item_arr[$this->item_file_name] = get_plugin_data($plugin_file);
                set_transient($this->transient_name, $this->item_arr);
                return $this->item_arr;
            }
            catch (exception $e)
            {
                return $default;
            }
        }
        public function plugin_data($key = "", $fresh = false)
        {
            if ($fresh)
            {
                $this->refresh_plugin_data();
            }
            else
            {

                $this->item_arr = get_transient($this->transient_name);
                if (!isset($this->item_arr[$this->item_file_name]))
                {
                    $ret = $this->refresh_plugin_data();
                    return $key ? $ret[$this->item_file_name][$key] : $ret[$this->item_file_name];

                }

            }
            return $key ? $this->item_arr[$this->item_file_name][$key] : $this->item_arr[$this->item_file_name];

        }
        public function refresh_theme_data()
        {
            $default = array('version' => "0", 'license' => "", "item_name" => '', "author" => "");

            try {
                $theme_file = get_theme_root() . "/" . $this->item_file_name;
                // $this->debug('license', __FUNCTION__ . "-" . __LINE__, $plugin_file);
                if (!function_exists('wp_get_theme'))
                {
                    include_once ABSPATH . "wp-admin/includes/theme.php";
                }
                //$this->debug('license', __FUNCTION__ . "-" . __LINE__,  $d);

                $this->item_arr[$this->item_file_name] = wp_get_theme($this->item_file_name);
                set_transient('wpmovies_themes', $this->item_arr);
                $return = $this->item_arr[$this->item_file_name];
            }
            catch (exception $e)
            {
                return $default;
            }
        }
        public function theme_data($key = "", $fresh = false)
        {
            if ($fresh)
            {
                $this->refresh_theme_data();
            }
            else
            {

                $this->item_arr = get_transient('wpmovies_themes');
                if (!isset($this->item_arr[$this->item_file_name]))
                {
                    $ret = $this->refresh_theme_data();
                    return $key ? $ret[$this->item_file_name][$key] : $ret[$this->item_file_name];
                }
            }
            return $key ? $this->item_arr[$this->item_file_name][$key] : $this->item_arr[$this->item_file_name];

        }

        public function plugin_updater()
        {
            
            $plugin_data = $this->plugin_data("", true);
            $plugin_conf = array(
                'version'   => $plugin_data['Version'], // current version number
                'license'   => $this->get_license_key(),
                'item_name' => $plugin_data['Name'],
                'author'    => $plugin_data['AuthorName'],
                'url'       => home_url(),
            );
            //$this->debug('license', __FUNCTION__ . "-" . __LINE__, 'plugin_updater');

            $updater = new EDD_SL_Plugin_Updater($this->store, $this->item_file_name, $plugin_conf);
        }

        public function theme_updater()
        {
            $theme_data = $this->theme_data("", true);
            $theme_conf = array(
                'theme_slug'     => $this->item_file_name,
                'remote_api_url' => $this->store,
                'version'        => $theme_data->Version, // current version number
                'license'        => $this->get_license_key(),
                'item_name'      => $theme_data->Name,
                'author'         => $theme_data->Author,
                'url'            => home_url(),
            );
            $edd_updater = new EDD_SL_Theme_Updater($theme_conf);
        }

    }

}
