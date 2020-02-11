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
class wp_hide_post_Admin
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
     * Allowed post types where  widget can be displayed
     *
     * @since    1.2.2
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $post_types;

    private $post_visibility_types;

    private $page_visibility_types;
    private $info;
    private $license;
    private $item_url = 'http://scriptburn.com/downloads/wp-hide-post/';
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.2.2
     * @param      string    $wp_hide_post       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($wp_hide_post, $version, $license)
    {
        //p_d($license->is_valid(),1);
        $this->wp_hide_post = $wp_hide_post;
        $this->version      = $version;
        $this->license      = $license;
        $this->post_types   = is_object($this->license) && $this->license->is_valid() ? wphp_get_setting('wphp_gen', 'wphp_post_types') : false;
        if (!is_array($this->post_types))
        {
            $this->post_types = array('post', 'page');
        }

        return $this;
    }
    public function allowedPostTypes($post_type = null)
    {

        if (!is_null($post_type))
        {
            return in_array($post_type, $this->post_types);
        }
        return $this->post_types;
    }

    public function plugin_init1()
    {
        $post_types = scb_custom_post_types();

        foreach ($this->allowedPostTypes() as $post_type)
        {
            if ($post_type == 'page' || (!empty($post_types[$post_type]) && $post_types[$post_type]->hierarchical))
            {
                wp_hide_post()->get_loader()->add_action("manage_pages_custom_column", $this->plugin_admin, 'manage_posts_columns', 10);

            }
            else
            {
                wp_hide_post()->get_loader()->add_action("manage_{$post_type}_posts_columns", $this->plugin_admin, 'manage_posts_columns', 10);
                wp_hide_post()->get_loader()->add_action("manage_edit-{$post_type}_columns", $this->plugin_admin, 'manage_posts_columns', 10);
            }

        }
    }
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.2.2
     */
    public function enqueue_styles()
    {
        global $pagenow;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in wp_hide_post_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The wp_hide_post_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->wp_hide_post, plugin_dir_url(__FILE__) . 'css/wp-hide-post-admin.css', array(), $this->version, 'all');
        if ($pagenow == 'edit.php')
        {
            wp_enqueue_style('scb_settings', plugin_dir_url(__FILE__) . 'settings/assets/chosen.min.css');
        }

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.2.2
     */
    public function enqueue_scripts()
    {
        global $wp_scripts, $pagenow;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in wp_hide_post_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The wp_hide_post_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script('jquery');

        if (is_admin())
        {
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('jquery-ui-tabs');

            $ui       = $wp_scripts->query('jquery-ui-core');
            $protocol = is_ssl() ? 'https' : 'http';
            $url      = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.css";

            wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
        }
        wp_enqueue_script($this->wp_hide_post, plugin_dir_url(__FILE__) . 'js/wp-hide-post-admin.js', array('jquery'), $this->version, false);
        if ($pagenow == 'edit.php')
        {
            wp_enqueue_script('scb_settings', plugin_dir_url(__FILE__) . 'settings/assets/chosen.jquery.min.js', array('jquery'));
        }

    }

    /**
     * Hook to watch for the activation of 'WP low Profiler', and forbid it...
     * @return unknown_type
     */
    public function activate_lowprofiler()
    {
        $wp_hide_post_Activator = new wp_hide_post_Activator();
        p_l("called: wphp_activate_lowprofiler");
        $wp_hide_post_Activator->migrate();

        $msgbox       = __("'WP low Profiler' has been deprecated and replaced by 'WP Hide Post' which you already have active! Activation failed and plugin files cleaned up.", 'wp-hide-post');
        $err1_sorry   = __("Cannot install 'WP low Profiler' because of a conflict. Sorry for this inconvenience.", 'wp-hide-post');
        $err2_cleanup = __("The downloaded files were cleaned-up and no further action is required.", 'wp-hide-post');
        $err3_return  = __("Return to plugins page...", 'wp-hide-post');
        $return_url   = admin_url('plugins.php');

        $html = <<<HTML
${err1_sorry}<br />${err2_cleanup}<br /><a href="${$return_url}">${err3_return}</a>
<script language="javascript">window.alert("${msgbox}");</script>
HTML;
        // show the error page with the message...
        wp_die($html, 'WP low Profiler Activation Not Allowed', array('response' => '200'));
    }

    /**
     * @param $action_links
     * @param $plugin
     * @return unknown_type
     */
    public function plugin_install_action_links_wp_lowprofiler($action_links, $plugin)
    {
        p_l("called: plugin_install_action_links_wp_lowprofiler");
        if ($plugin['name'] == 'WP low Profiler')
        {
            $alt          = '<a href="' . admin_url('plugin-install.php?tab=plugin-information&amp;plugin=wp-hide-post&amp;TB_iframe=true&amp;width=600&amp;height=800') . '" class="thickbox onclick" title="WP Hide Post">' . __('Check "WP Hide Post"') . '</a>';
            $action_links = array(
                __('Deprecated'),
                $alt);
        }
        return $action_links;
    }

    /*
    Return all allowed post visibility types
     */
    public function get_post_visibility_types()
    {
        if ($this->post_visibility_types)
        {
            return $this->post_visibility_types;
        }
        $this->post_visibility_types = array(
            'post_front'    => array(
                'label'       => wphp_('Hide on the front page.'),
                'short_label' => 'Front page',
                'description' => '',
            ),
            'post_category' => array(
                'label'       => wphp_('Hide on category pages.'),
                'short_label' => 'Category pages',
                'description' => '',
            ),
            'post_tag'      => array(
                'label'       => wphp_('Hide on tag pages.'),
                'short_label' => 'Tag pages',
                'description' => '',

            ),
            'post_author'   => array(
                'label'       => wphp_('Hide on author pages.'),
                'short_label' => 'Author pages',
                'description' => '',

            ),
            'post_archive'  => array(
                'label'       => wphp_('Hide in date archives (month, day, year, etc...) '),
                'short_label' => 'Archives',
                'description' => '',

            ),
            'post_search'   => array(
                'label'       => wphp_('Hide in search results. '),
                'short_label' => 'Search results',
                'description' => '',

            ),
            'post_feed'     => array(
                'label'       => wphp_('Hide in feeds.'),
                'short_label' => 'Feeds',
                'description' => '',
            ),

            'post_recent'   => array(
                'label'        => wphp_('Hide in Wp Native Recent post widget.'),
                'short_label'  => 'Recent post',
                'description'  => '',
                'no_auto_join' => true,
            ),
            'post_rel'      => array(
                'label'       => wphp_('Remove from next previous rel link'),
                'short_label' => 'Meta rel link',
                'description' => 'Remove post from Meta rel link In Single Post page<div style="background:green;color:white;padding:5px">' . htmlentities("<link rel='prev' title='Post title previous' href='http://your-previous-post-url' />") . '</div><div  style="background:green;color:white;padding:5px">' . htmlentities("<link rel='next' title='Post title next' href='http://your-next-post-url' />") . '</div><div></div>',
            ),

        );
        $this->post_visibility_types = apply_filters('wphp_post_visibility_types', $this->post_visibility_types);

        return $this->post_visibility_types;
    }
    /*
    Return all allowed post visibility types
     */
    public function get_page_visibility_types()
    {
        if ($this->page_visibility_types)
        {
            return $this->page_visibility_types;
        }
        $this->page_visibility_types = array(

            'hide_frontpage' => array(
                'label'       => wphp_('Hide when listing pages on the front page. '),
                'short_label' => 'Front page',
                'description' => '',
            ),
            'hide_always'    => array(
                'label'       => wphp_('Hide everywhere pages are listed.'),
                'short_label' => 'Always',
                'description' => 'Will still show up in sitemap.xml if you generate one automatically',

            ),
            'nohide_search'  => array(
                'label'       => wphp_('Hide everywhere but keep in search results.'),
                'short_label' => 'Hide but keep in search',
                'description' => 'Will still show up in sitemap.xml if you generate one automatically',

            ),
        );
        $this->page_visibility_types = apply_filters('wphp_page_visibility_types', $this->page_visibility_types);

        return $this->page_visibility_types;
    }

    public function get_visibility_types($post_type)
    {
        if ($post_type == 'page')
        {
            return $this->get_page_visibility_types();
        }
        else
        {
            return $this->get_post_visibility_types();

        }
    }

    private function save_visibility($post_type, $post_id, $is_bulk = false)
    {
        if (!$this->allowedPostTypes($post_type) || !current_user_can($post_type == 'page' ? 'edit_page' : 'edit_post', $post_id))
        {
            p_l(" save_visibility $post_type");
            return;
        }
        p_l(" save_visibility  bulk=" . ($is_bulk ? 1 : 0));
        foreach ($_POST[WPHP_VISIBILITY_NAME . "_old"] as $index => $value)
        {
            if (empty($_POST[WPHP_VISIBILITY_NAME][$index]))
            {
                $_POST[WPHP_VISIBILITY_NAME][$index] = 0;
            }
            if ((int) $_POST[WPHP_VISIBILITY_NAME . "_old"][$index] != $_POST[WPHP_VISIBILITY_NAME][$index] || $is_bulk)
            {
                p_l("$post_id,$index," . $_POST[WPHP_VISIBILITY_NAME][$index]);
                $this->update_visibility(
                    $post_id,
                    $index,
                    (int) $_POST[WPHP_VISIBILITY_NAME][$index]
                );
            }

        }
    }

    /**
     *
     * @param $id
     * @return unknown_type
     */
    public function save_post($id)
    {
        p_l("save_post");
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return $id;
        }
        $item_type = get_post_type($id);

        if (empty($_POST["wphp_{$item_type}_edit_nonce"]) || !wp_verify_nonce($_POST["wphp_{$item_type}_edit_nonce"], "wphp_{$item_type}_edit_nonce"))
        {
            p_l('no verify nonce ' . "wphp_{$item_type}_edit_nonce");
            return $id;
        }

        p_l("called: wphp_save_post");

        $this->save_visibility($item_type, $id);
    }

    public function save_bulk_edit_data()
    {
        //p_d($_POST);
        p_l("save_bulk_edit_data");
        $post_type = empty($_POST['post_type']) ? '' : $_POST['post_type'];

        $post_ids = (isset($_POST['post_ids']) && !empty($_POST['post_ids'])) ? $_POST['post_ids'] : array();

        if (empty($post_ids) || !is_array($post_ids))
        {
            die();
        }
        foreach ($post_ids as $post_id)
        {

            $this->save_visibility($post_type, $post_id, true);
        }
        die();
    }

/**
 *
 * @param $id
 * @param $lp_flag
 * @param $lp_value
 * @return unknown_type
 */
    public function update_visibility($id, $lp_flag, $lp_value)
    {

        if ($lp_value)
        {
            update_post_meta($id, WPHP_META_VALUE_PREFIX . $lp_flag, $lp_value);
        }
        else
        {
            delete_post_meta($id, WPHP_META_VALUE_PREFIX . $lp_flag);
        }
    }

/**
 *
 * @param $item_type
 * @param $id
 * @param $lp_flag
 * @return unknown_type
 */
    public function unset_low_profile($item_type, $id, $lp_flag)
    {
        p_l("called: unset_low_profile");
        global $wpdb;
        // Delete the flag from the database table
        $wpdb->query("DELETE FROM " . WPHP_TABLE_NAME . " WHERE post_id = $id AND meta_key = '$lp_flag'");
    }

/**
 *
 * @param $item_type
 * @param $id
 * @param $lp_flag
 * @param $lp_value
 * @return unknown_type
 */
    public function set_low_profile($item_type, $id, $lp_flag, $lp_value)
    {
        p_l("called: set_low_profile");
        global $wpdb;
        // Ensure No Duplicates!
        update_post_meta($id, $lp_flag, $lp_value);
        return;
        $check = $wpdb->get_var("SELECT count(*) FROM " . WPHP_TABLE_NAME . " WHERE post_id = $id AND meta_key='$lp_flag'");
        if (!$check)
        {
            $wpdb->query("INSERT INTO " . WPHP_TABLE_NAME . "(post_id, meta_key, meta_value) VALUES($id, '$lp_flag', '$lp_value')");
        }
        elseif ($item_type == 'page' && $lp_flag == WPHP_META_VALUE_PREFIX . "page_flags")
        {
            $wpdb->query("UPDATE " . WPHP_TABLE_NAME . " set meta_value = '$lp_value' WHERE post_id = $id and meta_key = '$lp_flag'");
        }
    }
/**
 *
 * @param $post_id
 * @return unknown_type
 */
    public function delete_post($post_id)
    {
        p_l("called: wphp_delete_post");
        global $wpdb;
        // Delete all post flags from the database table
        $wpdb->query("DELETE FROM " . WPHP_TABLE_NAME . " WHERE post_id = $post_id and meta_key like '" . WPHP_META_VALUE_PREFIX . "%'");
    }

// ad our metabox to  page
    public function add_meta_boxes($postType)
    {
        if (in_array($postType, $this->post_types))
        {
            $post_type = get_post_type_object($postType);
            if ($post_type)
            {
                add_meta_box('hidepostdivpost', sprintf(wphp_('%1$s Visibility'), ucwords($post_type->labels->singular_name)), array($this, 'metabox_post_edit'), $postType);
            }
        }
        elseif ($postType == 'page')
        {
            add_meta_box('hidepostdivpage', __('Page Visibility', 'wp-hide-post'), array($this, 'metabox_page_edit'), 'page');
        }
    }

// return the visibility options set for a page or post

    public function get_visibility_type_values($post_type, $post_id)
    {
        global $wpdb;
        $post_id = (int) $post_id;

        if ($post_type == 'page')
        {
            $visibility_types = $this->get_page_visibility_types();
        }
        else
        {
            $visibility_types = $this->get_post_visibility_types();
        }
        if (!$post_id)
        {
            return $visibility_types;
        }

        //p_d("SELECT meta_key from " . WPHP_TABLE_NAME . " where post_id = $id and meta_key like '". WPHP_META_VALUE_PREFIX ."%'");
        $flags = $wpdb->get_results("SELECT meta_key from " . WPHP_TABLE_NAME . " where post_id = $post_id and meta_key like '" . WPHP_META_VALUE_PREFIX . "%'", ARRAY_N);
        if ($flags)
        {
            foreach ($flags as $flag_array)
            {
                $flag = $flag_array[0];
                // remove the prefix _wplp_

                $flag = substr($flag, strlen(WPHP_META_VALUE_PREFIX));

                if (isset($visibility_types[$flag]))
                {
                    $visibility_types[$flag]['value'] = 1;
                }
            }
        }
        return $visibility_types;
    }
/**
 *
 * @return unknown_type
 */
    public function metabox_edit($post_id, $post_type, $meta_box = true)
    {
        p_l("called: metabox_edit $post_id, $post_type");

        //wp_nonce_field(plugin_basename(__FILE__), "wphp_{$post_type}_edit_nonce");

        $pre[] = '<input type="hidden" id="wphp_' . $post_type . '_edit_nonce" name="wphp_' . $post_type . '_edit_nonce" value="' . wp_create_nonce("wphp_{$post_type}_edit_nonce") . '" />';
        if ($post_type !== 'page')
        {
            $pre[] = '<script>(function($)
{
    $(document).ready(function()
    {
        $(".wphp_checkall").change(function()
        {
            $(".wphp_checkall").parent().parent().parent().find(".wphp_multicheck").prop("checked", $(this).prop("checked"));
        });
    });
})(jQuery);</script>';
        }

        $pre[] = '<div style="padding:10px;background:#e5e5e5"> <label for="wphp_checkallcheck" style="font-weight:bold" >
    <input type="checkbox" class="wphp_checkall"   id="wphp_checkallcheck"  />
    &nbsp;
   Check All

</label>
</div>';

        $tmpl = '<div style="padding:10px;padding-bottom:0px"> <label for="%1$s_new_%2$s" class="selectit">
    <input type="checkbox" id="%1$s_new_%2$s" name="%1$s[%2$s]" value="%3$s"  %4$s class="wphp_multicheck"/>
    &nbsp;
    %5$s

</label>
%8$s
<input type="hidden" name="%1$s_old[%2$s]" value="%6$s"/>
<input type="hidden" name="%1$s_name[%2$s]" value="%7$s"/></div>';

        $html  = array();
        $index = 0;
        foreach ($this->get_visibility_type_values($post_type, $post_id) as $type => $detail)
        {
            $detail['value'] = empty($detail['value']) ? 0 : (int) $detail['value'];

            $html[] = sprintf($tmpl,
                WPHP_VISIBILITY_NAME,
                $type,
                1,
                $detail['value'] ? 'checked' : '',
                $detail['label'],
                $detail['value'],
                $type,
                empty($detail['description']) ? '' : sprintf('<p style="    padding-left: 30px;" class="description">%1$s</p>', $detail['description'])
            );
            $index++;
        }
        return (implode("\n", $pre) . implode("", $html) . ($meta_box ? $this->default_info('widget') : ''));

    }

    public function metabox_post_edit($post)
    {
        echo ($this->metabox_edit($post->ID, $post->post_type));
    }
    public function metabox_page_edit($post)
    {
        echo ($this->metabox_edit($post->ID, $post->post_type));
    }

    public function create_post_type()
    {
        return;
        register_post_type('acme_product',
            array(
                'labels'      => array(
                    'name'          => __('Products'),
                    'singular_name' => __('Product'),
                ),
                'public'      => true,
                'has_archive' => true,
            )
        );

        register_post_type('acme_item',
            array(
                'labels'      => array(
                    'name'          => __('Items'),
                    'singular_name' => __('Item'),
                ),
                'public'      => true,
                'has_archive' => true,
            )
        );

        register_post_type('acme_test',
            array(
                'labels'      => array(
                    'name'          => __('Tests'),
                    'singular_name' => __('Test'),
                ),
                'public'      => true,
                'has_archive' => true,
            )
        );
    }
    public function register_setting_page()
    {

        $valid = is_object($this->license) && $this->license->is_valid();
        wp_hide_post()->settingManager()->register_tab(array('id' => 'wphp_gen', 'title' => 'General'));
        $options = array();
        foreach ((array) scb_custom_post_types() as $type => $detail)
        {

            $detail = (array) $detail;

            $options[$type] = array('text' => $detail['labels']->name);
            if (!$valid && !$detail['_builtin'])
            {
                $options[$type]['extra'] = 'disabled';
                $options[$type]['text'] .= " -- For WP hide post Pro users only";
            }
        }
        $pro = '<div><a target="_blank"  style="color:red" href="http://scriptburn.com/wphp">For WP hide post Pro Users only</a></div>';
        wp_hide_post()->settingManager()->register_setting_field('wphp_gen', array(
            array('name'  => 'wphp_post_types',
                'label'       => wphp_('Allowed custom post types'),
                'desc'        => wphp_('Allow WP hide post widget in these custom post types'),
                'type'        => 'multi',
                'options'     => $options,
                'placeholder' => 'Select custom post type',
            ),
            array('name' => 'show_in_quickedit',
                'label'      => wphp_('Enable quick edit?'),
                'desc'       => wphp_('Display WP hide post widget in quick edit?') . ($valid ? '' : $pro),
                'type'       => 'yesno',
                'default'    => 1,
                'disabled'   => $valid ? '' : 'disabled',
            ),
            array('name' => 'show_in_bulkedit',
                'label'      => wphp_('Enable bulk edit?'),
                'desc'       => wphp_('Display WP hide post widget in bulk edit?') . ($valid ? '' : $pro),
                'type'       => 'yesno',
                'default'    => 1,
                'disabled'   => $valid ? '' : 'disabled',
            ),
        ));
    }

    public function admin_menu()
    {

        add_submenu_page('options-general.php', wphp_('WP Hide Post'), wphp_('WP Hide Post'), 'manage_options', wp_hide_post()->setting_menu_page(), array(wp_hide_post()->settingManager(), 'plugin_page'));
    }
    public function register_plugin($licenses)
    {
        $licenses[] = array('id' => 'wp-hide-post-pro',
            'type'                   => 'plugin',
            'name'                   => 'WP Hide Post Pro',
            'label'                  => 'WP hide Post Plugin',
            'options'                => array(
                'license_text' => array('invalid' => 'Get your WP Hide Post Pro license from here <a href="http://scriptburn.com/wphp" target="_blank">here</a>'),
                'store_url'    => "http://scriptburn.com",
                'file'         => wp_hide_post()->info('file'),

            ),
        );

        return $licenses;
    }
    public function admin_footer()
    {

        ?>
        <script>
            var wphp_hide_on_data=[];
            wphp_hide_on_data['visibility_name'] = '<?php echo (WPHP_VISIBILITY_NAME); ?>';
             wphp_hide_on_data['visibility_types']=[]
             <?php

        foreach (array('post', 'page') as $post_type)
        {
            echo (" wphp_hide_on_data['visibility_types']['$post_type']=[];\n");

            foreach ($this->get_visibility_types($post_type) as $visibility_type => $data)
            {
                echo (" wphp_hide_on_data['visibility_types']['$post_type']['$visibility_type']='" . (empty($data['value']) ? '0' : (int) $data) . "';\n");
            }
        }
        ?>
        </script>
        <?php
}
/* quick edit box */
// add our custom column to post list box show we can display where the post is hidden
    public function manage_posts_columns($columns)
    {

        $columns['wphp_hide_on'] = 'Hidden On';
        return $columns;
    }

// display data of our custom column
    public function render_custom_column_data($column, $post_id)
    {
        static $nonce_data;
        if ($column != 'wphp_hide_on')
        {
            return;
        }
        $current_v = isset($_GET['wphp_hidden_on']) ? $_GET['wphp_hidden_on'] : array();
        $current_v = is_array($current_v) ? $current_v : array($current_v);
        $post_type = get_post_type($post_id);

        // we will store nonce in this variable
        // we will only fill this variable if this function is called first time
        // only in first call this variable will have array data
        $items = array();
        if (!$nonce_data)
        {

            $nonce_data = array(
                'nonce_field'     => "wphp_{$post_type}_edit_nonce",
                'nonce_value'     => wp_create_nonce("wphp_{$post_type}_edit_nonce"),
                'visibility_name' => WPHP_VISIBILITY_NAME,
            );
            $nonce_data = sprintf('<input type="hidden" class="wphp_hide_on_data" value="%1$s" />', urlencode(json_encode($nonce_data)));
        }
        switch ($column)
        {
            case 'wphp_hide_on':

                $values = $this->get_visibility_type_values($post_type, $post_id);
                $data   = array();
                foreach ($values as $visibility_type => $item)
                {
                    if (isset($item['value']))
                    {
                        if ($item['value'])
                        {
                            $items[$visibility_type] = sprintf('<span %1$s>%2$s</span>',
                                in_array($visibility_type, $current_v) ? 'class="visibility_item"' : '',
                                (isset($item['short_label']) ? $item['short_label'] : $visibility_type)
                            );
                        }
                        $data[$visibility_type] = empty($item['value']) ? 0 : (int) $item['value'];
                    }
                }
                $items = array(implode(" , ", $items));

                $items[] = sprintf('<input type="hidden" class="wphp_hidden_on" value="%2$s" />', $post_id, urlencode(json_encode($data)));

                $items[] = $nonce_data;

                echo implode("\n", $items);
                break;
        }

    }
    public function display_custom_bulkedit($column_name, $post_type)
    {
        if (!wphp_get_setting('wphp_gen', 'show_in_bulkedit'))
        {
            return;
        }
        $this->display_custom_quickedit($column_name, $post_type);
    }
    //render our  quick edit and bulk edit box
    public function display_custom_quickedit($column_name, $post_type)
    {
        $valid = is_object($this->license) && $this->license->is_valid();

        if (!wphp_get_setting('wphp_gen', 'show_in_quickedit'))
        {
            return;
        }

        $pre = array();
        if ($column_name != 'wphp_hide_on')
        {

            return;
        }

        if (!$this->allowedPostTypes($post_type))
        {

            return;
        }

        static $printNonce;
        // p_l("display_custom_quickedit $printNonce");
        if (!$printNonce)
        {
            $printNonce = wp_create_nonce("wphp_{$post_type}_edit_nonce");
            $printNonce = '<input type="hidden" id="wphp_' . $post_type . '_edit_nonce" name="wphp_' . $post_type . '_edit_nonce" value="' . $printNonce . '" />';
            $pre[]      = $printNonce;
        }

        $tmpl = '<div style="padding:10px;padding-bottom:0px"> <label for="%1$s_new_%2$s" class="selectit">
    <input type="checkbox" id="%1$s_new_%2$s" name="%1$s[%2$s]" value="%3$s"  %4$s class="wphp_multicheck"  %9$s/>
    &nbsp;
    %5$s
</label>%8$s
<input type="hidden" name="%1$s_old[%2$s]" value="%6$s"  %9$s/>
<input type="hidden" name="%1$s_name[%2$s]" value="%7$s" %9$s/></div>';

        $html       = array();
        $index      = 0;
        $allChecked = false;

        foreach ($this->get_visibility_types($post_type) as $type => $detail)
        {
            $detail['value'] = empty($detail['value']) ? 0 : (int) $detail['value'];

            $html[] = sprintf($tmpl,
                WPHP_VISIBILITY_NAME,
                $type,
                1,
                $detail['value'] ? 'checked' : '',
                $detail['label'],
                $detail['value'],
                $type,
                empty($detail['description']) ? '' : sprintf('<p style="    padding-left: 30px;" class="description">%1$s</p>', $detail['description']),
                $valid ? '' : 'disabled'
            );
            $allChecked = $detail['value'];
            $index++;
        }
        $pro = '<div style="padding:10px;padding-bottom:0px"><a target="_blank"  style="color:red" href="http://scriptburn.com/wphp">For WP hide post Pro Users only</a></div>';
        if ($post_type !== 'page')
        {
            $pre[] = '<script>(function($)
{
    $(document).ready(function()
    {
        $(".wphp_checkall").change(function()
        {
            $(".wphp_checkall").parent().parent().parent().find(".wphp_multicheck").prop("checked", $(this).prop("checked"));
        });
    });
})(jQuery);</script>';

            $pre[] = '<div style="padding:10px;background:#e5e5e5"> <label for="wphp_checkallcheck" style="font-weight:bold" >
    <input ' . ($valid ? '' : 'disabled') . ' type="checkbox" id="wphp_checkallcheck"  class="wphp_checkall" ' . ($allChecked ? 'checked' : '') . '   />
    &nbsp;
   Check All

</label>
</div>';
        }
        $post_type_obj = get_post_type_object($post_type);

        $title = sprintf(wphp_('%1$s Visibility'), ucwords($post_type_obj->labels->singular_name));
        $html  = implode("\n", $pre) . implode("", $html) . ($valid ? '' : $pro);
        require WPHP_PLUGIN_DIR . 'admin/partials/quick-edit-display.php';

    }

// store the data in js var which will be used in click event of quick edit link
    // and set the correct data in our quick edit box
    public function post_row_actions_insert_data_as_js1($actions, $post)
    {
        static $js_var;
        if (!$this->allowedPostTypes($post->post_type))
        {
            return;
        }
        $values = array();
        if (!$js_var)
        {
            echo ("\n<script>\n var wphp_hide_on_data=[]; \n
                wphp_hide_on_data['visibility_name']='" . WPHP_VISIBILITY_NAME . "';\n
                wphp_hide_on_data['nonce_field']='" . "wphp_{$post->post_type}_edit_nonce" . "';\n
                wphp_hide_on_data['nonce_value']='" . wp_create_nonce("wphp_{$post->post_type}_edit_nonce") . "';\n
                wphp_hide_on_data['visibility_values']=[];\n

             </script>\n");
            $js_var = true;
        }
        foreach ($this->get_visibility_type_values($post->post_type, $post->ID) as $visibility_type => $detail)
        {
            if (isset($detail['value']))
            {
                $values[$visibility_type] = (int) $detail['value'];
            }
        }

        if (count($values))
        {
            echo ("<script>\n
                wphp_hide_on_data['visibility_values'][" . $post->ID . "]=JSON.parse('" . json_encode($values) . "');\n
                </script>\n");
        }
        return $actions;
        $nonce                           = wp_create_nonce('myfield_' . $post->ID);
        $myfielvalue                     = get_post_meta($post->ID, 'myfield', true);
        $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';
        $actions['inline hide-if-no-js'] .= esc_attr(__('Edit this item inline')) . '"';
        $actions['inline hide-if-no-js'] .= " onclick=\"set_wphp_hide_on_value('{$post->ID}')\" >";
        $actions['inline hide-if-no-js'] .= __('Quick Edit1');
        $actions['inline hide-if-no-js'] .= '</a>';
        return $actions;
    }

// add our custom filter selectbox in admin post list box
    public function restrict_manage_posts()
    {

        $type = 'post';
        if (isset($_GET['post_type']))
        {
            $type = $_GET['post_type'];
        }

        if (!$this->allowedPostTypes($type))
        {
            return;
        }
        $values = $this->get_visibility_types($type);

        $values['all'] = array('short_label' => 'All Hidden');

        ?>
        <select name="wphp_hidden_on[]" class=" scb-sett-select-chosen chosen-select" multiple data-placeholder="Select Hidden option">
         <?php
$current_v = isset($_GET['wphp_hidden_on']) ? $_GET['wphp_hidden_on'] : array();
        $current_v = is_array($current_v) ? $current_v : array($current_v);

        foreach ($values as $visibility_type => $item)
        {
            printf
                (
                '<option value="%1$s"%2$s >%3$s</option>',
                $visibility_type,
                in_array($visibility_type, $current_v) ? ' selected="selected"' : '',
                isset($item['short_label']) ? $item['short_label'] : $visibility_type
            );
        }
        ?>
        </select>
        <?php

    }

// Filter the posts acording to selected filter in post list select box
    public function query_posts_join_custom_filter($join, $wp_query)
    {

        global $wpdb, $pagenow;

        $type = 'post';
        if (isset($_GET['post_type']))
        {
            $type = $_GET['post_type'];
        }

        if (!$this->allowedPostTypes($type))
        {
            return $join;
        }

        if (!(is_admin() && $pagenow == 'edit.php' && isset($_GET['wphp_hidden_on']) && $_GET['wphp_hidden_on'] != ''))
        {
            return $join;
        }

        $current_v = isset($_GET['wphp_hidden_on']) ? $_GET['wphp_hidden_on'] : array();
        $current_v = is_array($current_v) ? $current_v : array($current_v);

        $join .= ' inner JOIN ' . WPHP_TABLE_NAME . ' wphptbl ON ' . WP_POSTS_TABLE_NAME . ".ID = wphptbl.post_id  ";

        if (in_array('all', $current_v))
        {
            $join .= sprintf(' AND wphptbl.meta_key   like  "%1$s%%"', WPHP_META_VALUE_PREFIX);
        }
        else
        {

            foreach ($current_v as $key => $v)
            {
                $current_v[$key] = esc_sql(WPHP_META_VALUE_PREFIX . $v);
            }
            $join .= " AND wphptbl.meta_key   in ('" . implode("','", $current_v) . "')  ";

        }
        return $join;

    }
    public function default_info($page)
    {
        $info = get_transient('wphp_notices_' . $page);

        if (!$info)
        {

            $obj_license    = scb_get_license('wp-hide-post-pro');
            $args['action'] = 'wphp_footer';
            $args['page']   = $page;
            if (is_object($obj_license))
            {
                $info = $obj_license->extendedInfo();
                $args = array_merge($args, array(
                    'license'    => $info['license'],
                    'price'      => isset($info['price']) ? $info['price'] : 0,
                    'payment_id' => isset($info['payment_id']) ? $info['payment_id'] : '',

                ));
            }
            $args['price'] = isset($args['price']) ? $args['price'] : 0;
            $url           = "http://scriptburn.com/wp-admin/admin-ajax.php?" . http_build_query($args);
            $response      = wp_remote_get($url, array('decompress' => false));

            if (is_array($response) && !empty($response['body']))
            {

                $info = @json_decode($response['body']);
                if (is_object($info))
                {
                    $info = property_exists($info, 'response') ? $info->response : $this->default_footer($args['price']);
                }
                else
                {
                    $info = $response['body'];
                }

                set_transient('wphp_notices_' . $page, $info, 86400);
            }
            elseif ($response == "0")
            {
                $info = "";
            }
            else
            {

                $info = $this->default_footer($args['price']);
            }
        }
        return $info;

    }
    public function default_footer($price)
    {
        $info = '<hr/>
<div>
    <div style="float:left">
        <a href="http://scriptburn.com/wp-hide-post/#comments">
            %1$s
        </a>
    </div>
    <div style="float:right">
        <a href="http://wordpress.org/extend/plugins/wp-hide-post/">
            %2$s
        </a>
    </div>
</div><div style="clear:both">' . $price ? '' : '
<div style="text-align:center">
    <a href="http://scriptburn.com/wp-hide-post">
        <img src="http://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_pp_142x27.png"/>
    </a>
</div>
<div style="clear:both">
</div>
';
        return sprintf($info,
            wphp_("Leave feedback and report bugs..."),
            wphp_("Give 'WP Hide Post' a good rating...")
        );

    }
    public function wsa_footer()
    {

        echo $this->default_info('footer');
    }

    public function maybe_update()
    {

        // bail if this plugin data doesn't need updating
        // delete_option('wpmovies_db_ver');
        $db_updated = (int) (empty($_REQUEST['db_updated']) ? 0 : $_REQUEST['db_updated']);
        if ($db_updated)
        {
            return;
        }
        $wphp_db_ver = (int) get_option('wphp_db_ver');
        // error_log($wpmovies_db_ver . ">=" . self::WPMOVIES_DB_VER);
        if ($wphp_db_ver >= WPHP_DB_VER)
        {
            return;
        }

        require_once WPHP_PLUGIN_DIR . 'admin/class-wp-hide-post-dbupdate.php';
        $db_updater = new wp_hide_post_DB_Update();
        $ret        = $db_updater->db_update();

        if ($ret)
        {
            set_transient('wphp_db_updated', 1, 30);
        }
    }

}
