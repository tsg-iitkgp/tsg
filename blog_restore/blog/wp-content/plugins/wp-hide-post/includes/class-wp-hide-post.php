<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://scriptburn.com
 * @since      2.0
 *
 * @package    wp_hide_post
 * @subpackage wp_hide_post/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0
 * @package    wp_hide_post
 * @subpackage wp_hide_post/includes
 * @author     ScriptBurn <support@scriptburn.com>
 */
class wp_hide_post
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.2.2
     * @access   protected
     * @var      wp_hide_post_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.2.2
     * @access   protected
     * @var      string    $wp_hide_post    The string used to uniquely identify this plugin.
     */
    protected $wp_hide_post;

    /**
     * The current version of the plugin.
     *
     * @since    1.2.2
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Holds the single instance of setting manage class
     *
     * @since    1.2.2
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $settingManager;
    /**
     * Holds the single instance of this class
     *
     * @since    1.2.2
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    private static $instance;
    private $plugin_admin;

    const id   = 'wp-hide-post';
    const name = 'WP Hide Post';

    private $info;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.2.2
     */

    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {

        $this->define_globals();
        $this->load_dependencies();
        $this->init();

        $this->plugin_admin = new wp_hide_post_Admin(
            $this->get_wp_hide_post(),
            $this->get_version(), $this->license());

        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - wp_hide_post_Loader. Orchestrates the hooks of the plugin.
     * - wp_hide_post_i18n. Defines internationalization functionality.
     * - wp_hide_post_Admin. Defines all hooks for the admin area.
     * - wp_hide_post_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.2.2
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * This file contains usfull functions to be used in global scope througout the plugin
         */
        require_once WPHP_PLUGIN_DIR . 'includes/helpers.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once WPHP_PLUGIN_DIR . 'includes/class-wp-hide-post-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once WPHP_PLUGIN_DIR . 'includes/class-wp-hide-post-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once WPHP_PLUGIN_DIR . 'admin/class-wp-hide-post-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once WPHP_PLUGIN_DIR . 'public/class-wp-hide-post-public.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once WPHP_PLUGIN_DIR . 'public/class-wp-hide-post-public.php';
        require_once WPHP_PLUGIN_DIR . 'admin/license/autoload.php';

        require_once WPHP_PLUGIN_DIR . 'admin/settings/autoload.php';
        require_once WPHP_PLUGIN_DIR . 'admin/conditions.php';
        require_once WPHP_PLUGIN_DIR . 'admin/plugin/autoload.php';

        $this->loader = new wp_hide_post_Loader();

    }
    public function settingManager()
    {
        return $this->settingManager;
    }
    public function pluginAdmin()
    {
        return $this->plugin_admin;
    }
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the wp_hide_post_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.2.2
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new wp_hide_post_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.2.2
     * @access   private
     */
    private function define_admin_hooks()
    {
        $this->loader->add_filter('scb_license_items', $this->plugin_admin, 'register_plugin', 10);
        $this->loader->add_action('admin_init', $this->plugin_admin, 'register_setting_page');

        //add our styles
        $this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles');

        // add our scripts
        $this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts');

        // watch when wp-low-profiler get activated
        $this->loader->add_action('activate_wp-low-profiler/wp-low-profiler.php', $this->plugin_admin, 'activate_lowprofiler');

        // mark wp-low-profiler as depriciated
        $this->loader->add_action('plugin_install_action_links', $this->plugin_admin, 'plugin_install_action_links_wp_lowprofiler', 10, 2);

        $this->loader->add_action('save_post', $this->plugin_admin, 'save_post');
        $this->loader->add_action('delete_post', $this->plugin_admin, 'delete_post');

        $this->loader->add_action('add_meta_boxes', $this->plugin_admin, 'add_meta_boxes');
        $this->loader->add_action('admin_menu', $this->plugin_admin, 'admin_menu');
        if (wphp_is_demo())
        {
            $this->loader->add_filter('init', $this->plugin_admin, 'create_post_type', 10);
        }
        // loop through all allowed post types as saved in setting section of the plugin where we  this plugin can work

        foreach ($this->plugin_admin->allowedPostTypes() as $post_type)
        {
            // add our custom column to posts list
            $this->loader->add_filter("manage_{$post_type}_posts_columns", $this->plugin_admin, 'manage_posts_columns', 10);
            // some plugins still using this old filter
            $this->loader->add_filter("manage_edit-{$post_type}_columns", $this->plugin_admin, 'manage_posts_columns', 10);

        }

        // add this in case a custom post type is hierarchical type   this works for page
        $this->loader->add_action("manage_pages_custom_column", $this->plugin_admin, 'render_custom_column_data', 10, 2);

        //renders custom column data for posts
        $this->loader->add_action("manage_posts_custom_column", $this->plugin_admin, 'render_custom_column_data', 10, 2);

        // add our custom filter selectbox in admin post list box
        $this->loader->add_action("restrict_manage_posts", $this->plugin_admin, 'restrict_manage_posts');

        // Filter the posts acording to selected filter in post list select box
        $this->loader->add_filter('posts_join_paged', $this->plugin_admin, 'query_posts_join_custom_filter', 10, 2);

        //render our  quick edit box
        $this->loader->add_action('quick_edit_custom_box', $this->plugin_admin, 'display_custom_quickedit', 10, 2);

        //render our  bulk edit box
        $this->loader->add_action('bulk_edit_custom_box', $this->plugin_admin, 'display_custom_bulkedit', 10, 2);

        // print our available visibility type items in footer js
        // which we will use when sending our data to server in bulk edit save
        $this->loader->add_action('admin_footer', $this->plugin_admin, 'admin_footer');

        //Save bulk edit data
        $this->loader->add_action('wp_ajax_save_bulk_edit_data', $this->plugin_admin, 'save_bulk_edit_data');

        //Our custom action to add fotter message in widget
        $this->loader->add_action('wsa_global_footer', $this->plugin_admin, 'wsa_footer');

        //Check if we need to update database
        $this->loader->add_action('plugins_loaded', $this->plugin_admin, 'maybe_update', 1);

        //returns system info data for support reques
        add_action('plugins_loaded', function ()
        {
            if (isset($_REQUEST['support_info']))
            {
                if (isset($_REQUEST['hash']) && get_option('wphp_support_hash') == $_REQUEST['hash'])
                {
                    echo (scb_systems_info("</br>"));
                }

                wp_die();
            }
        }, 1);

        //$theme_item = new SCB_Item_Helper($this->store, $this->item_file_name, $this->get_license_key(), $this->item_type);

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.2.2
     * @access   private
     */
    private function define_public_hooks()
    {
        if (is_admin())
        {
            return;
        }
        $plugin_public = new wp_hide_post_Public($this->get_wp_hide_post(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        //add where clauss in main filter query
        $this->loader->add_filter('posts_where_paged', $plugin_public, 'query_posts_where', 10, 2);

        //add join clauss in main filter query
        $this->loader->add_filter('posts_join_paged', $plugin_public, 'query_posts_join', 10, 2);

        $this->loader->add_filter('widget_posts_args', $plugin_public, 'widget_posts_args');

        // only for testing purpose , Enables hidepost enabled post type to appear everywhere
        if (wphp_is_demo())
        {

            //$this->loader->add_action('pre_get_posts', $plugin_public, 'test_enable_allposts_everywhere');

        }
        // used to add join clause to remove next and previous rel link of post from single page
        $this->loader->add_filter('get_next_post_join', $plugin_public, 'post_excluded_terms_join_rel', 10, 1);
        $this->loader->add_filter('get_previous_post_join', $plugin_public, 'post_excluded_terms_join_rel', 10, 1);

        // used to add where clause to remove next and previous rel link of post from single page
        $this->loader->add_filter('get_next_post_where', $plugin_public, 'query_posts_where_rel_exclude', 10, 1);
        $this->loader->add_filter('get_previous_post_where', $plugin_public, 'query_posts_where_rel_exclude', 10, 1);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.2.2
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_wp_hide_post()
    {
        return $this->info('id');
    }

    public function info($name)
    {
        if (defined('static::' . $name))
        {
            return constant('self::' . $name);
        }
        elseif (isset($this->info[$name]))
        {
            return $this->info[$name];
        }
        else
        {
            return "";

        }
        return defined('static::' . $name) ? constant('self::' . $name) : '';
    }
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    wp_hide_post_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return WPHP_VER;
    }

    public function setting_menu_page()
    {
        return $this->get_wp_hide_post() . '_settings_api';
    }
    public function license()
    {
        return SCB_LicenseManager::getInstance()->item('wp-hide-post-pro');
    }
    public function init()
    {
        p_l("memory_get_usage1-".memory_get_usage(false));
        $this->settingManager = wphp_settings::instance(array
            (
                'id'                => $this->info('id'),
                'page_title'        => $this->info('title') . " settings",
                'setting_page_name' => $this->setting_menu_page(),
                'support_callback'  => function ()
            {
                p_l("memory_get_usage2-".memory_get_usage(false));
                    $obj_license   = scb_get_license('wp-hide-post-pro');
                    $info          = array();
                    $info['price'] = "0";
                    if (is_object($obj_license))
                {
                        $info        = $obj_license->extendedInfo();
                        $info['key'] = $obj_license->get_license_key();

                    }

                    $output        = array();
                    $info['price'] = 1;
                    if ($info['price'] > 0)
                {

                        $output[] = '<h2>For WP hide post Pro users</h2>';
                        if (@$info['key'] && @$info['license'] == 'valid')
                    {
                            $subject = ("WP hide post Pro Support Request");
                            $body    = "";
                            $body .= "License:" . $info['key'] . "%0D%0A%0D%0A";
                            $body .= 'Systems Info:' . home_url() . "?support_info" . "%0D%0A%0D%0A";
                            $body .= 'Hash:' . get_option('wphp_support_hash') . "%0D%0A%0D%0A%0D%0A%0D%0A";
                            $body .= "Enter your message here ";
                            $output[] = sprintf('<a href="mailto:support@scriptburn.com?subject=%1$s&body=%2$s">Click here </a>', $subject, $body);
                        }
                    else
                    {
                            $output[] = "Sorry you don't have WP hide post Pro license";
                        }
                        $output[] = '<h2>For WP hide post Lite users</h2>';
                    }
                    $output[] = 'Please submit your questions  <a target="_blank" href="https://github.com/scriptburn/wp-hide-post">Here</a>';
                    echo (implode("\n", $output));
                                    p_l("memory_get_usage3-".memory_get_usage(false));

                },
            ));
        if ((defined('WPHP_PRO') && WPHP_PRO) || $this->info('id') == 'wp-hide-post-pro')
        {

            scb_license_manager()->add(
                'wp-hide-post-pro',
                null,
                'plugin',
                'WP Hide Post Pro',
                null,
                array(
                    'license_text' => array('invalid' => 'Get your WP Hide Post Pro license from here <a href="http://scriptburn.com/wphp" target="_blank">here</a>'),
                    'store_url'    => "http://scriptburn.com",
                    'file'         => $this->info('file'),

                )
            );
        }
    }
    public function define_globals()
    {

        $this->info['dir'] = WPHP_PLUGIN_DIR;
        $this->info['url'] = WPHP_PLUGIN_URL;

        $this->info['title']    = __('WP Hide Post', 'scb_plugin');
        $this->info['file']     = WPHP_PLUGIN_FILE;
        $this->info['basename'] = plugin_basename($this->info['file']);
    }
}
function wp_hide_post()
{
    return wp_hide_post::getInstance();
}
