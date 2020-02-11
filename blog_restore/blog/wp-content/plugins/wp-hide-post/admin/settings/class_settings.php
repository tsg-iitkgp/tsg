<?php

if (!class_exists('wphp_settings'))
{

    class wphp_settings
    {
        private $pages, $settings_api, $asignment_fields = array(), $license_fields = array(), $asignment_section,
        $core_section, $tabs                             = array()

        , $settings = array(), $info_section, $info_fields, $all_tabs;
        private $options;
        private static $instance;
        public $test;
        public static function instance($options = array())
        {
            if (!self::$instance)
            {
                self::$instance = new wphp_settings($options);

            }
            return self::$instance;
        }
        public function __construct($options = array())
        {
            $this->options = $options;
            $this->setup();
            add_action('scb_download_sysinfo', array($this, 'tools_sysinfo_download'));

        }
        public function options($name = null)
        {
            if (!is_null($name))
            {
                if (isset($this->options[$name]))
                {
                    return $this->options[$name];
                }
                else
                {
                    return '';
                }
            }
            else
            {
                return $this->options;
            }
        }
        public function api()
        {
            return $this->settings_api;
        }
        public function setup()
        {
            $this->settings_api = new wphp_settingsAPICustom();
            add_action('init', array($this, 'init'));

            add_action('admin_init', array($this, 'admin_init'), 11);

            add_action('wp_ajax_manage_license', array($this, 'manage_license'));

        }
        public function register_tab($tabs)
        {
            $tabs = is_array($tabs) && isset($tabs[0]) ? $tabs : array($tabs);

            foreach ($tabs as $item => $tab)
            {
                if (!(isset($tab['id']) && $tab['id'] && isset($tab['title']) && $tab['title']))
                {
                    unset($tabs[$item]);
                    continue;
                }
                if (isset($this->tabs[$tab['id']]))
                {
                    unset($tabs[$item]);
                }
                else
                {

                    $tab['title']           = isset($tab['title']) ? $tab['title'] : '';
                    $tab['label']           = isset($tab['label']) ? $tab['label'] : '';
                    $tab['label']           = $tab['label'] ? $tab['label'] : $tab['title'];
                    $tab['label']           = $tab['label'] ? $tab['label'] : $tab['id'];
                    $tab['options']         = isset($tab['options']) ? $tab['options'] : array();
                    $this->tabs[$tab['id']] = array('id' => $tab['id'], 'title' => $tab['title'], 'label' => $tab['label'], 'options' => $tab['options']);
                }
            }
            return $tabs;
        }
        public function register_assignment_field($settings)
        {
            if (!$this->pages)
            {
                $pages = get_pages(array('sort_order' => 'desc',
                    'sort_column'                         => 'post_date'));
                foreach ($pages as $page)
                {
                    $this->pages[$page->ID] = $page->post_title . "(" . $page->ID . ")";

                }
            }
            $settings = is_array($settings) && isset($settings[0]) ? $settings : array($settings);
            foreach ($settings as $key => $setting)
            {
                $settings[$key]['type']    = 'select';
                $settings[$key]['options'] = $this->pages;
            }
            $settings               = $this->register_setting_field('wpm_assign', $settings, true);
            $this->asignment_fields = array_merge($this->asignment_fields, $settings);
            return $settings;
        }
        public function register_setting_field($tab, $settings, $return = false)
        {
            $setting_temp = array();
            $settings     = is_array($settings) && isset($settings[0]) ? $settings : array($settings);

            foreach ($settings as $item => $setting)
            {
                if (!(isset($setting['name']) && $setting['label']))
                {
                    unset($settings[$item]);
                    continue;
                }

                $key = array_search($setting['name'], array_column(isset($this->settings[$tab]) && is_array($this->settings[$tab]) ? $this->settings[$tab] : array(), 'name'));

                if ($key !== false)
                {
                    unset($settings[$key]);
                }
                else
                {
                    $setting_temp[] = $setting;

                }
            }

            if ($return)
            {
                return $setting_temp;
            }
            else
            {
                $this->settings[$tab] = array_merge(
                    isset($this->settings[$tab]) && is_array(@$this->settings[$tab]) ? $this->settings[$tab] : array(), $setting_temp);
            }

            return $setting_temp;
        }
        public function init()
        {
            global $wpmovies_var;
            $licenses             = array();
            $this->license_fields = array();

            $licenses = apply_filters('scb_license_items', $licenses);

            foreach ($licenses as $license)
            {

                $license['options']                   = isset($license['options']) ? $license['options'] : array();
                $this->license_fields[$license['id']] = array(
                    'name'              => $license['id'],
                    'label'             => $license['label'],
                    'desc'              => 'dd',
                    'type'              => 'text',
                    'default'           => '',
                    'options'           => isset($license['options']) ? $license['options'] : array(),
                    'callback'          => array($this, 'callback_license_text_box'),
                    'sanitize_callback' => function ()
                    {
                        return '';
                    },
                );
                if (function_exists('scb_license_manager'))
                {
                    $license['options']['license_file']   = isset($license['file']) ? $license['file'] : null;
                    $license['options']['license_folder'] = isset($license['folder']) ? $license['folder'] : null;

                    scb_license_manager()->add(
                        $license['id'],
                        isset($license['options']['store_url']) ? $license['options']['store_url'] : null,
                        $license['type'],
                        $license['name'],
                        null,
                        $license['options']
                    );

                }
            }

        }
        public function callback_info_page()
        {

            $info = scb_systems_info();
            echo ('<textarea readonly="readonly" onclick="this.focus(); this.select()" id="system-info-textarea" name="scb-sysinfo">' . $info . "</textarea>");

        }

        public function callback_support_page()
        {

            echo ("support");

        }

        public function callback_license_text_box($args)
        {

            $license_key  = '';
            $license_info = '';

            if (function_exists('scb_license_manager'))
            {
                $obj_license = scb_get_license($args['id']);

                if (!is_null($obj_license))
                {

                    $license_key  = $obj_license->get_license_key();
                    $license_info = $obj_license->extendedInfo();
                }
            }
            $license_info['license'] = isset($license_info['license']) ? $license_info['license'] : 'unknown';
            $status                  = @$license_info['license'] == 'valid' ? 1 : 0;

            $value = esc_attr($this->settings_api->get_option($args['id'], $args['section'], $args['std']));
            // p_d( $value );
            $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $type = isset($args['type']) ? $args['type'] : 'text';

            $key_input_id = '#scb_lic_txt_' . $args['id'];
            $html         = sprintf('<input type="%1$s" class="%2$s-text" id="scb_lic_txt_%4$s" name="%3$s[%4$s]" value="%5$s" />',
                $type, $size, $args['section'], $args['id'], $license_key);

            $html .= sprintf('<input data-key="%1$s"  data-status="%2$s" data-id="%3$s" id="scb_lic_btn_%3$s" value="%4$s" type="submit" class="scb_lic_class button"  name="license_action" >', $key_input_id, $status, $args['id'], ($status ? 'Deactivate' : 'Activate'));
            $license_info['license_info'] = isset($license_info['license_info']) ? $license_info['license_info'] : '';
            $license_info['error_info']   = isset($license_info['error_info']) ? $license_info['error_info'] : '';
            $html .= sprintf('<p class="license_status %2$s" id="scb_lic_status_%1$s" >%3$s</p>', $args['id'], ($status ? 'license_status_valid' : 'license_status_invalid'), $license_info['license_info'] . ($license_info['error_info'] ? " -" . $license_info['error_info'] . " " : ''));

            $license_text = $obj_license->options('license_text');

            if (isset($license_text[$license_info['license']]))
            {
                $html .= "<p>" . $license_text[$license_info['license']] . "</p>";
            }

            echo $html;
        }
        public function admin_init()
        {

            //set the settings

            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());
            //initialize settings
            $this->settings_api->admin_init();

            if (isset($_POST['option_page']) && isset($this->all_tabs[$_POST['option_page']]) && isset($this->all_tabs[@$_POST['option_page']]['options']['form']['pre_handler']) && is_callable($this->all_tabs[@$_POST['option_page']]['options']['form']['pre_handler']))
            {
                $ret = call_user_func_array($this->all_tabs[$_POST['option_page']]['options']['form']['pre_handler'], array($_POST));
            }
        }

        public function get_settings_sections()
        {
            $th                             = $this;
            $this->core_sections['license'] = array(
                'id'      => 'scb_lic',
                'title'   => __('Licenses', 'scb_plugin'),
                'options' => array('no_submit' => true),

            );

            $this->core_sections['info'] = array(
                'id'       => 'scb_info',
                'title'    => __('System Info', 'scb_plugin'),
                'callback' => array($this, 'callback_info_page'),
                'options'  => array(
                    'no_submit' => false,
                    'button'    => array('text' => 'Download Systme info file'),
                    'form'      => array('pre_handler' => function () use ($th)
                    {
                        do_action('scb_download_sysinfo', $th->options['id'] . "_sysinfo.txt");
                    }),
                ),
            );

            $this->core_sections['support'] = array(
                'id'       => 'scb_support',
                'label'    => __('Support', 'scb_plugin'),
                'callback' => is_callable($this->options('support_callback')) ? $this->options('support_callback') : array($this, 'callback_support_page'),
                'options'  => array(
                    'no_submit' => true,
                ),
            );

            $this->tabs     = apply_filters('scb_setting_tabs', $this->tabs);
            $this->all_tabs = array_merge(
                array('scb_lic' => $this->core_sections['license']),
                $this->tabs,
                array('scb_info' => $this->core_sections['info']),
                array('scb_support' => $this->core_sections['support'])
            );
            return $this->all_tabs;

        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        public function get_settings_fields()
        {

            $this->settings                                        = apply_filters('scb_setting_fields', $this->settings);
            $this->settings[$this->core_sections['license']['id']] = $this->license_fields;
            // $this->settings[$this->core_sections['info']['id']]    = $this->info_fields;

            $this->settings[$this->asignment_section['id']] = $this->asignment_fields;

            return $this->settings;

        }
        public function plugin_page()
        {
            //p_n($_POST);
            //p_d($this->all_tabs);
            if (isset($_POST['option_page']) && isset($this->all_tabs[$_POST['option_page']]) && is_callable($this->all_tabs[$_POST['option_page']]['options']['form']['post_handler']))
            {
                $ret = call_user_func_array($this->all_tabs[$_POST['option_page']]['options']['form']['post_handler'], array($_POST));
            }
            echo '<div class="wrap">';
            $this->settings_api->show_navigation();
            settings_errors(null, false, true);
            $this->settings_api->show_forms("options.php", $this->options);
            $this->script();
            echo '</div>';
        }

        /**
         * Generates a System Info download file
         *
         * @since       2.0
         * @return      void
         */

        public function tools_sysinfo_download($filename)
        {

            if (!current_user_can('manage_options'))
            {
                return;
            }
            @ob_end_clean();
            nocache_headers();

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            //  die(   wp_strip_all_tags($_POST['scb-sysinfo']);
            die(wp_strip_all_tags($_POST['scb-sysinfo']));
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        public function get_pages()
        {
            $pages         = get_pages();
            $pages_options = array();
            if ($pages)
            {
                foreach ($pages as $page)
                {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }
            return $pages_options;
        }
        public function manage_license()
        {

            try
            {
                $response['status']  = 0;
                $response['error']   = 'Unknown Error';
                $response['lic_id']  = @$_REQUEST['id'];
                $response['lic_key'] = @$_REQUEST['key'];

                $obj_license = scb_get_license($response['lic_id']);

                if (!$obj_license)
                {
                    $response['error'] = 'Invalid Plugin Or theme(1)';
                }
                elseif (!$response['lic_key'])
                {
                    $response['error'] = 'Invalid Plugin Or theme(2)';
                }
                else
                {
                    $obj_license->set_license_key($response['lic_key']);

                    if (@$_REQUEST['status'])
                    {
                        $obj_license->deactivate_license();
                    }
                    else
                    {
                        $obj_license->activate_license();
                    }
                    $response['error']  = "";
                    $response['status'] = 1;
                    $license_info       = $obj_license->extendedInfo();

                    $license_info['license'] = isset($license_info['license']) ? $license_info['license'] : 'unknown';

                    $response['lic_status'] = $license_info['license'] == 'valid' ? 1 : 0;

                    $license_info = $obj_license->extendedInfo();

                    $response['lic_msg'][] = $license_info['license_info'] . ($license_info['error_info'] ? " -" . $license_info['error_info'] . " " : '');

                    $license_text = $obj_license->options('license_text');

                    if (isset($license_text[$license_info['license']]))
                    {
                        $response['lic_msg'][] = "<br/>" . $license_text[$license_info['license']];
                    }
                    $response['lic_msg'] = implode("\n", $response['lic_msg']);
                    $response['lic_btn'] = ($response['lic_status'] ? 'Deactivate' : 'Activate');

                }
            }
            catch (Exception $e)
            {
                $response['status'] = 0;
                $response['error']  = $e->getMessage();
            }
            wp_send_json($response);
        }
        public function script()
        {
            ?>
            <style type="text/css">
    .scb-sett-select
    {
    }
    .scb-sett-select-chosen{
    width: 300px;
    }
    .chosen-container-multi .chosen-choices {
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border-color: #dfdfdf;
    background-image: none;
    }
    .chosen-container .search-field input {
    width: 90%!important;
    }
    .chosen-container-multi .chosen-choices input {
    margin: 2px;
    height: 27px!important;
    border-color: #dfdfdf;
    }
    .chosen-container .search-field {
    float: none!important;

}
#system-info-textarea {
    background: 0 0;
    font-family: Menlo,Monaco,monospace;
    display: block;
    overflow: auto;
    white-space: pre;
    width: 100%;
    height: 400px;
}
</style>

        <script>
        var scb_setting_tabs_options={};
        <?php foreach ($this->all_tabs as $tab_id => $tab)
            {
                if (isset($tab['options']) && is_array($tab['options']) && count($tab['options']))
                {
                    echo ("scb_setting_tabs_options['$tab_id'] = " . json_encode($tab['options']) . "\n");
                }
            }
            // (isset($tab['options'])?json_encode($tab)?'')
            ?>
            jQuery(document).ready(function($)

{



    $('.scb_lic_class').on('click', function(event)
    {
        event.preventDefault();
        var data = {
            action: 'manage_license',
            id: $(this).data('id'),
            key: $($(this).data('key')).val(),
            status: $(this).data('status'),
        };
        $('#scb_lic_btn_' + $(this).data('id')).data('label', $('#scb_lic_btn_' + $(this).data('id')).val());
        $('#scb_lic_btn_' + $(this).data('id')).val('Please Wait..');
        $('#scb_lic_btn_' + $(this).data('id')).attr('disabled', true);
        $.post(ajaxurl, data, function(response)
        {
            if (!response.status)
            {
                showModalMsg(wphp_texts.error, response.error,
                {});
                $('#scb_lic_btn_' + response.lic_id).val($('#scb_lic_btn_' + response.lic_id).data('label'));
                $('#scb_lic_btn_' + response.lic_id).attr('disabled', false)
            }
            else
            {
                $('#scb_lic_txt_' + response.lic_id).data('status', response.lic_status);
                $('#scb_lic_btn_' + response.lic_id).val(response.lic_btn);
                $('#scb_lic_btn_' + response.lic_id).attr('disabled', false);
                $('#scb_lic_status_' + response.lic_id).html(response.lic_msg);
            }
            $('#scb_lic_status_' + response.lic_id).removeClass().addClass("license_status " + (response.lic_status ? 'license_status_valid' : 'license_status_invalid'));
            $('#scb_lic_btn_' + response.lic_id).data('status', response.lic_status);
        }).fail(function(xhr, err)
        {
            id = $(this)[0].data.split('&')[1].split("=")[1];
            $('#scb_lic_btn_' + id).val($('#scb_lic_btn_' + id).data('label'));
            $('#scb_lic_btn_' + id).attr('disabled', false)
            showModalMsg(wphp_texts.error, formatErrorMessage(xhr, err));
        })
    });
});
        </script>

        <style type="text/css">
    .license_status_valid{color:green;}
     .license_status_invalid{color:red;}


  div.description {
    margin-top: 4px;
    margin-bottom: 0;
    font-size: 13px;
    font-style: italic;
     margin: 2px 0 5px;
    color: #666;
}

        </style>
        <?php

        }

    }

}
