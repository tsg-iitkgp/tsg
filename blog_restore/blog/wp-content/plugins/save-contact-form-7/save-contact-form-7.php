<?php
/*

  Plugin name: Save Contact Form 7
  Plugin URI: http://nimblechapps.com
  Description: A simple plugin to save contact form data to db.
  Author: Nimblechapps
  Author URI: http://nimblechapps.com
  Version: 2.0
 */
//function to check dependencies for Contact Form 7 Plugin

if (!defined('ABSPATH')) {
    exit;
}
define('SAVE_CF7_ADMIN_MENU', 'save_contact_form_7'); // define menu slug name
add_action('wp_head', 'nimble_scf7_ajaxurl');

function nimble_scf7_ajaxurl() {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
}

if (is_admin()) {

// registering hooks for activation and deactivation of plugin
    function nimble_cf7_required() {
        $url = network_admin_url('plugin-install.php?tab=search&type=term&s=Contact+Form+7&plugin-search-input=Search+Plugins');
        echo '
    <div class="error">
        <p>The <a href="' . $url . '">Contact Form 7 Plugin</a> is required.</p>
    </div>
    ';
    }

    function nimble_check_required() {
        global $wpdb;

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
            add_action('admin_notices', 'nimble_cf7_required');
        }
        // create lookup table
        $sql_lookup_create = "CREATE TABLE IF NOT EXISTS  SaveContactForm7_lookup (`lookup_id` int(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,`CFDBA_tbl_name` VARCHAR (255) NULL, `CF7_created_title` VARCHAR(100) NOT NULL, `CF7_created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `CF7_version` varchar(10) NOT NULL, `CF7_form_id` int(8) NOT NULL, `CF7_from_wpposts_or_tbl` VARCHAR(100) NOT NULL , `CF7_removed_flag` ENUM('YES','NO') DEFAULT 'NO' NOT NULL ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ";
        $wpdb->query($sql_lookup_create);
    }

    add_action('plugins_loaded', 'nimble_check_required');

    function nimble_deactivation() { /* function to deactivation hook */
    }

    function nimble_deactivate_self() {
        deactivate_plugins(plugin_basename(__FILE__));
    }

// registering hooks for activation and deactivation of plugin
    register_activation_hook(__FILE__, 'nimble_check_required');
    register_deactivation_hook(__FILE__, 'nimble_deactivation');

    /*     * **************************** */
//function to load scripts and styles

    if (!function_exists('nimble_scripts')) {

        function nimble_scripts($hook) {

            if ($hook != 'toplevel_page_' . SAVE_CF7_ADMIN_MENU) {
                return;
            }

//register css for datatables and bootstrap
            wp_register_style('nimble_dt_process_circle_style', plugin_dir_url(__FILE__) . 'assets/DataTables/media/css/dataTables.customLoader.circle.css');
            wp_register_style('nimble_dt_process_walker_style', plugin_dir_url(__FILE__) . 'assets/DataTables/media/css/dataTables.customLoader.walker.css');
            wp_register_style('nimble_custom_style', plugin_dir_url(__FILE__) . 'assets/css/nimble_custom.css');

            wp_register_style('nimble_dt_buttons_min_style', plugin_dir_url(__FILE__) . 'assets/DataTables/media/css/buttons.dataTables.min.css');

            wp_enqueue_style('nimble_dt_process_circle_style');
            wp_enqueue_style('nimble_dt_process_walker_style');
            wp_enqueue_style('nimble_custom_style');
            wp_enqueue_style('nimble_dt_buttons_min_style');

//enque scripts for datatables and bootstrap
            // wp_enqueue_script('nimble_jq_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/jquery.js', array('jquery'));
            wp_enqueue_script('nimble_dt_min_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/jquery.dataTables.min.js', array('jquery'));
            wp_enqueue_script('nimble_dt_bootstrap_responsive_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/dataTables.responsive.min.js', array('jquery'));
            wp_enqueue_script('nimble_dt_btns_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/dataTables.buttons.min.js', array('jquery'));
            wp_enqueue_script('nimble_dt_jszip_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/jszip.min.js', array('jquery'));

            wp_enqueue_script('nimble_dt_pdfmake_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/pdfmake.min.js', array('jquery'));
            wp_enqueue_script('nimble_dt_vfs_fonts_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/vfs_fonts.js', array('jquery'));

            wp_enqueue_script('nimble_dt_btnhtml5_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/buttons.html5.min.js', array('jquery'));
//            wp_enqueue_script('nimble_dt_btnhtml5_1_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/buttons.html5.min_1.js', array('jquery'));

            wp_enqueue_script('nimble_dt_btn_print_script', plugin_dir_url(__FILE__) . 'assets/DataTables/media/js/buttons/buttons.print.min.js', array('jquery'));

//enque external script for datatable ajax call 
            wp_enqueue_script('ajaxloadjs', plugin_dir_url(__FILE__) . 'assets/js/nimble_ajax_request.js', array('jquery'));
            wp_localize_script('ajaxloadjs', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')), array('jquery'));
        }

        add_action('admin_enqueue_scripts', 'nimble_scripts');
    }
// function to add admin menu for plugin
    if (!function_exists("nimble_menu")) {

        function nimble_menu() {
            $page_title = 'Save Contact Form 7';
            $menu_title = 'Save CF7';
            $capability = 'manage_options';
            $menu_slug = SAVE_CF7_ADMIN_MENU;
            $function = 'nimble_populate_page';
            $icon_url = plugins_url('save-contact-form-7/assets/images/icon.png');
            $position = 99;
            //create new top-level menu
            add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
            add_submenu_page(
                    $menu_slug, // admin page slug
                    'Save Contact Form 7 settings', // page title
                    'Settings', // menu title
                    'manage_options', // capability required to see the page
                    'nimble_settings', // admin page slug, e.g. options-general.php?page=wporg_options
                    'nimble_settings_page');
        }

        add_action('admin_menu', 'nimble_menu');
    }

    /*     * ****************************************** */
//function to create seperate admin menu page for plugin
    /*     * **************************************** */
    if (!function_exists('nimble_populate_page')) {

        function nimble_populate_page() {
            global $wpdb;
            ?>
            <div>
                <div class="main_heading"><h3> Save Contact Form 7 </h3></div>
                <div id="nimble_cf7_names_main">
                    <select name="nimble_cf7_names" id="nimble_cf7_names" >
                        <option selected="selected" disabled="disabled">Select a form</option>
                        <?php
                        $nimble_cf7_names = nimble_get_cf7_name();
                        $cf7_form_names = array();
                        foreach ($nimble_cf7_names as $nimble_cf_name) {
                            //$tbl = $wpdb->get_results("SHOW TABLES LIKE '" . $nimble_cf_name['CFDBA_table'] . "'");
                            $tbl = $wpdb->query($wpdb->prepare("SHOW TABLES LIKE '%s'", $nimble_cf_name['CFDBA_table']));
                            if ($wpdb->num_rows == 1 && !empty($tbl)) {
                                if ($nimble_cf_name['CF7_version'] > '2.4.6' && $nimble_cf_name['CF7_from_wpposts_or_tbl'] == $wpdb->prefix . "posts") {
                                    $post_exists = $wpdb->query($wpdb->prepare("select * from " . $wpdb->prefix . "posts where ID = %d", (int) $nimble_cf_name['CF7_form_id']));
                                    if ($wpdb->num_rows > 0 && !empty($post_exists)) {
                                        $cf7_form_names['CF7-Working'][$nimble_cf_name['ID']] = strtoupper($nimble_cf_name['form_title']);
                                    } else {
                                        $cf7_form_names['CF7-Deleted'][$nimble_cf_name['ID']] = strtoupper($nimble_cf_name['form_title']);
                                        $sql_flag_update = $wpdb->query($wpdb->prepare("update SaveContactForm7_lookup SET CF7_removed_flag = %s WHERE lookup_id = %d "), "YES", (int) $nimble_cf_name['ID']
                                        );
                                    }
                                } else {                                
                                    $post_exists = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix . "contact_form_7 where cf7_unit_id = %s ", $nimble_cf_name['CF7_form_id']));
                                    if ($wpdb->num_rows > 0 && !empty($post_exists)) {
                                        $cf7_form_names['CF7-Working'][$nimble_cf_name['ID']] = strtoupper($nimble_cf_name['form_title']);
                                    } else {
                                        $cf7_form_names['CF7-Deleted'][$nimble_cf_name['ID']] = strtoupper($nimble_cf_name['form_title']);
                                        $sql_flag_update = $wpdb->query($wpdb->prepare("update SaveContactForm7_lookup SET CF7_removed_flag = %s WHERE lookup_id = %d "), "YES", (int) $nimble_cf_name['ID']);
                                    }
                                }
                            }
                        }
                        foreach ($cf7_form_names as $key => $value) {
                            $optcolor = ($key == "CF7-Deleted" ? 'red' : '');
                            echo "<optgroup label=" . $key . ">";
                            foreach ($value as $id => $cf7_form) {
                                echo "<option value = '" . $id . "' style=' color : $optcolor ;'>" . $cf7_form . "</option>";
                            }
                            echo "</optgroup>";
                        }
                        ?>
                    </select>
                </div>
                <div id="nimble_table_wrapper">
                    <p>
                    <h4 align="center">Please Select a Form You Have Submitted To View Its Data........!</h4>
                    </p>
                </div>
            </div>    
            <?php
        }

    }

    /*     * ******************************************************************************************************************************************** */
// code start for settings API use
    /*     * ******************************************************************************************************************************************** */

    add_action('admin_init', 'nimble_options');

    function nimble_options() {
        /* Display Options Section */
        add_settings_section(
                'nimble_display_page', '', 'nimble_display_section_callback', 'nimble_settings_options'
        );
        add_settings_field(
                'nimble_scf7_display_created_date', 'Show Created Date Field', 'nimble_display_date_options_callback', 'nimble_settings_options', 'nimble_display_page'
        );
        register_setting('nimble_settings_options', 'nimble_scf7_display_created_date');
    }

    /* Call Backs
      ----------------------------------------------------------------- */

    function nimble_display_section_callback() {
        //echo '<p> Display Data Options:</p>'; 
    }

    function nimble_display_date_options_callback() {
        echo '<input type="checkbox" id="nimble_scf7_display_created_date" name="nimble_scf7_display_created_date" value="1" ' . checked(1, get_option('nimble_scf7_display_created_date'), false) . '/> Display Entry Date & Time';
    }

    /* Display Page
      ----------------------------------------------------------------- */

    function nimble_settings_page() {
        global $wpdb;
        settings_errors();
        ?>  

        <h2 class="nav-tab-wrapper">  
            Display Settings
        </h2>  
        <form method="post" action="options.php">  
            <?php
            settings_fields('nimble_settings_options');
            do_settings_sections('nimble_settings_options');
            ?>             
            <?php submit_button(); ?>  
        </form> 
        <?php
    }

    /*     * ******************************************************************************************************************************************** */

// code after settings API use
    /*     * ******************************************************************************************************************************************** */

// function to get submitted contact form 7 names
    function nimble_get_cf7_name() {
        global $wpdb;
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // get form title from Lookup table

        $contact_forms = $wpdb->get_results("select * from SaveContactForm7_lookup order by CF7_removed_flag desc");
        //var_dump($contact_forms);exit;
        //$sql = "select * from SaveContactForm7_lookup order by CF7_removed_flag desc";
        //$contact_forms = $wpdb->get_results($sql);
        if (!empty($contact_forms)) {
            foreach ($contact_forms as $contact_form) {
                $form_name[] = array("ID" => $contact_form->lookup_id, "form_title" => $contact_form->CF7_created_title, "CFDBA_table" => $contact_form->CFDBA_tbl_name, "CF7_version" => $contact_form->CF7_version, "CF7_form_id" => $contact_form->CF7_form_id, "CF7_from_wpposts_or_tbl" => $contact_form->CF7_from_wpposts_or_tbl, "form_status" => $contact_form->CF7_removed_flag);
            }
            return $form_name;
        }
    }

    // function to get db table fields name of selected form
    function nimble_getFields($tab, $export, $isHeader = false) {
        global $wpdb;
        $nimble_dir_pah = wp_upload_dir();
        $nimble_date_options = get_option('nimble_scf7_display_created_date');
        $id = explode("_", $tab);
        $col_with_cmnt = array();        
        
        $row_fields = $wpdb->get_results("SHOW full COLUMNS FROM $tab");
        if (!empty($row_fields)) {
            if ($isHeader == true) {
                foreach ($row_fields as $k => $v) {
                    $sendingArr[] = $v->Field;
                    $col_with_cmnt[] = $v->Comment;

                    if (strlen($v->Comment) != 0) {
                        $sendingArr[] = $v->Field;
                        $col_with_cmnt[] = "";  // for dynamic added extra column column
                    }
                }
            } else {
                foreach ($row_fields as $k => $v) {
                    if (strlen($v->Comment) == 0) {
                        $sendingArr[] = '`' . $v->Field . '`';
                    } else {
                        $sendingArr[] = 'CONCAT("' . $nimble_dir_pah['baseurl'] . '/nimble_uploads/", `id`,"/",`' . $v->Field . '`," ")';
                        $sendingArr[] = ' IF( ' . $v->Field . ' IS NULL, "" , CONCAT("<a href=\'' . $nimble_dir_pah['baseurl'] . '/nimble_uploads/", `id`,"/",`' . $v->Field . '`,"\' target=\'_blank\' title=\'View-",' . $v->Field . ',"\' >","<i class=\'icon-view\'></i>","</a>","&nbsp;&nbsp;","<a href=\'' . $nimble_dir_pah['baseurl'] . '/nimble_uploads/", `id`,"/",`' . $v->Field . '`,"\' target=\'_blank\' title=\'Download-",' . $v->Field . ',"\' download>","<i class=\'icon-download\'></i>","</a>") ) as `' . $v->Field . '`';
                    }
                }
            }

            if ($nimble_date_options !== "") {
                unset($col_with_cmnt[1]);
                array_push($col_with_cmnt, "");
                $aaaa = array_values($col_with_cmnt);
                $arr = array($sendingArr, $aaaa);
                return $arr;
            } else {
                $arr = array($sendingArr, $col_with_cmnt);
                return $arr;
            }
        }
    }

// function to populate table with custom header
    if (!function_exists('nimble_populate_data')) {

        function nimble_populate_data() {
            global $wpdb;
            if (isset($_REQUEST['id'])) {
                $id = absint($_REQUEST['id']);
            }

            $dt_header = '<div class="nimble_table_inner"><table id="nimble_table_data" class="display hover" width="100%" cellspacing="0" border="0px" ><thead><tr>';
            $dt_columnslist = "";
            $dt_columnslistCount = 0;
            $table = "SaveContactForm7_" . $id;
            $export = "";
            $db_fields = nimble_getFields($table, $export, true);
            $nimble_date_options = get_option('nimble_scf7_display_created_date');


            if (!empty($db_fields)) {
                if ($nimble_date_options != "") {
                    $i = 0;
                    $header_create_date_index = array_splice($db_fields[0], 1, 1);
                    $header_fields_arr = array_merge($db_fields[0], $header_create_date_index);

                    foreach ($header_fields_arr as $db_field) {    // loop for column header 
                        if (!in_array($db_field, array("id"))) {
                            $columns[] = array("db" => $db_field, "dt" => $i);
                            $dt_header .= "<th align='left'>" . ucwords(str_replace("_", " ", $db_field)) . "</th>";
                            $i++;
                        }
                    }

                    foreach ($db_fields[1] as $key => $value) {   // loop for export options columns like [0,1,2,3,5] and target column 
                        if ($key == 0) {
                            continue;
                        } elseif ($value == "") {
                            $dt_columnslist .= $dt_columnslistCount . ",";
                        } else {
                            $dt_column_target = $dt_columnslistCount + 1;
                        }
                        $dt_columnslistCount++;
                    }
                } else {
                    $i = 0;
                    foreach ($db_fields[0] as $db_field) {      // loop for column header 
                        if (!in_array($db_field, array("id", "created_on"))) {
                            $columns[] = array("db" => $db_field, "dt" => $i);
                            $dt_header .= "<th align='left'>" . ucwords(str_replace("_", " ", $db_field)) . "</th>";
                            $i++;
                        }
                    }

                    foreach ($db_fields[1] as $key => $value) {     // loop for export options columns like [0,1,2,3,5] and target column 
                        if ($key == 0 || $key == 1) {
                            continue;
                        } elseif ($value == "") {
                            $dt_columnslist .= $dt_columnslistCount . ",";
                        } else {
                            $dt_column_target = $dt_columnslistCount + 1;
                        }
                        $dt_columnslistCount++;
                    }
                }

                $dt_header .= '</tr></thead></table></div>';
                $data['dt_header'] = $dt_header;
                $data['dt_columnslist'] = rtrim($dt_columnslist, ",");
                $data['dt_column_target'] = @$dt_column_target;
                echo json_encode($data);
                wp_die();
            }
        }

    }
// function to populate table with custom header closed
    add_action('wp_ajax_nimble_ajax_data', 'nimble_populate_data', 1, 1); // ajax for logged in users
    add_action('wp_ajax_nopriv_nimble_ajax_data', 'nimble_populate_data', 1, 1); // ajax for not logged in users
// function to populate data into datatable

    if (!function_exists("nimble_populate_datatable")) {

        function nimble_populate_datatable() {
            $export = "";
            if (isset($_REQUEST['exportbutton'])) {
                $export = sanitize_text_field($_REQUEST['exportbutton']);
            }

            global $wpdb;
            $nimble_dir_pah = wp_upload_dir();
            $table = "SaveContactForm7_" . absint($_POST['id']);
            $db_fields = nimble_getFields($table, $export);
            $i = 0;
            $nimble_date_options = get_option('nimble_scf7_display_created_date');

            if (!empty($db_fields)) {
                if ($nimble_date_options != "") {
                    $i = 0;
                    $header_create_date_index = array_splice($db_fields[0], 1, 1);
                    $header_fields_arr = array_merge($db_fields[0], $header_create_date_index);

                    foreach ($header_fields_arr as $db_field) {    // loop for column header 
                        if (!in_array($db_field, array("`id`"))) {
                            $columns[] = array("db" => $db_field, "dt" => $i);
                            $i++;
                        }
                    }
                } else {
                    $i = 0;
                    foreach ($db_fields[0] as $db_field) {      // loop for column header 
                        if (!in_array($db_field, array("`id`", "`created_on`"))) {
                            $columns[] = array("db" => $db_field, "dt" => $i);
                            $i++;
                        }
                    }
                }

                $sql_details = array(
                    'user' => $wpdb->dbuser,
                    'pass' => $wpdb->dbpassword,
                    'db' => $wpdb->dbname,
                    'host' => $wpdb->dbhost
                );
                /** for custom filter and export data ** */
                if (isset($_REQUEST['searchvalue']) && $_REQUEST['searchvalue'] != '') {
                    $search = sanitize_text_field($_REQUEST['searchvalue']);
                } else {
                    $search = '';
                }
                $ordertype = "";
                $join = "";
                if (isset($_REQUEST['column']) && $_REQUEST['column'] != '') {
                    $columnorder = sanitize_text_field($_REQUEST['column']);
                    $ordertype = sanitize_text_field($_REQUEST['ordertype']);
                } else {
                    $columnorder = '';
                }

                /** for custom filter and export data code ends ** */
                $primaryKey = 'id';
                require( 'includes/ssp.class.php' );
                $finalRows = array();
                $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $search, $columnorder, $ordertype, $join);  //ajax response
                if (isset($data['data']) && !empty($data['data'])) {
                    foreach ($data['data'] as $key => $value) {
                        for ($i = 0; $i < count($columns); $i++) {
                            $aa[$i] = $value[$i];
                        }
                        $finalRows[$key] = $aa;
                    }
                }
            }

            echo json_encode($data);
            wp_die();
        }

    }

    add_action('wp_ajax_nimble_ajax_datatable', 'nimble_populate_datatable'); // ajax for logged in users
    add_action('wp_ajax_nopriv_nimble_ajax_datatable', 'nimble_populate_datatable'); // ajax for not logged in users
//function nimble_populate_datatable() closed  
//
// function to create database table with id in table name after contact form created
    if (!function_exists("nimble_after_cf7_create")) {

        function nimble_after_cf7_create($wpcf7) {
            global $wpdb;
            global $wpcf7_shortcode_manager;
            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $plugin_data = get_plugins();
            if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "4.2.2") {
                $obj = WPCF7_ContactForm::get_current();
                $shortcode = WPCF7_ShortcodeManager::get_instance();
                $form = $shortcode->scan_shortcode($obj->prop('form'));
                $CF7_form_id = $obj->id();
                $CF7_form_title = $obj->title();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from, $form);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9") {
                $obj = WPCF7_ContactForm::get_current();
                $shortcode = WPCF7_ShortcodeManager::get_instance();
                $form = $shortcode->scan_shortcode($obj->prop('form'));
                $CF7_form_id = $obj->id();
                $CF7_form_title = $obj->title();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.7") {

                $shortcode = WPCF7_ShortcodeManager::get_instance();
                $form = $shortcode->scan_shortcode($wpcf7->form);
                $CF7_form_id = $wpcf7->id;
                $CF7_form_title = $wpcf7->title;
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.1") {

                $shortcode = $wpcf7_shortcode_manager;
                $form = $shortcode->scan_shortcode($wpcf7->form);
                $CF7_form_id = $wpcf7->id;
                $CF7_form_title = $wpcf7->title;
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0") {
                $shortcode = $wpcf7_shortcode_manager;
                $form = $shortcode->scan_shortcode($wpcf7->form);
                $CF7_form_id = $wpcf7->id;
                $CF7_form_title = $wpcf7->title;
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from, $form);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "2.4.6") {
                $shortcode = $wpcf7_shortcode_manager;
                $form = $shortcode->scan_shortcode($wpcf7->form);
                $CF7_form_id = $wpcf7->id;
                $CF7_form_title = $wpcf7->title;
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from, $form);
            }

            if (!empty($form)) {
                foreach ($form as $key => $fields) {
                    if ($fields['name'] == '')
                        continue;
                    if (strstr($fields['name'], "-") != FALSE) {
                        $fields['name'] = str_replace("-", "_", $fields['name']);
                    }
                    if ($fields['type'] == 'file' || $fields['type'] == 'file*') {
                        $db_table_fields[] = "`" . $fields['name'] . "` text COMMENT 'file_field' default NULL";
                    } else {
                        $db_table_fields[] = "`" . $fields['name'] . "` text ";
                    }
                }
            }
            $db_table_field = implode(",", $db_table_fields);
            if ($table != "") {
                $sql_create = "CREATE TABLE IF NOT EXISTS " . $table . "(`id` int(8) NOT NULL PRIMARY KEY AUTO_INCREMENT, `created_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, " . $db_table_field . ") DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ";
                $wpdb->query($sql_create);
//                 $wpdb->query( $wpdb->prepare("CREATE TABLE IF NOT EXISTS  %s (`id` int(8) NOT NULL PRIMARY KEY AUTO_INCREMENT, `created_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, %s) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ", $table,$db_table_field) );
            }
        }

    }
    add_action('wpcf7_after_save', 'nimble_after_cf7_create');

    if (!function_exists('custom_js')) {

        function custom_js() {
            ?>  
            <script type="text/javascript">
                var PATH = '<?php echo plugin_dir_url(__FILE__); ?>';
            </script>
            <?php
        }

        add_action('admin_footer', 'custom_js');
    }

    /*     * ******functions used for lookup ********** */

    if (!function_exists("getCF7_from")) {

        function getCF7_from() {
            global $wpdb;
            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $plugin_data = get_plugins();
            if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] > "2.4.6") {
                $CF7_from = $wpdb->prefix . "posts";
            } else {
                $CF7_from = $wpdb->prefix . "contact_form_7";
            }
            return $CF7_from;
        }

    }

    /* function to insert data into lookup table on form creation */
    if (!function_exists("nimble_lookup_entry")) {

        function nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from, $form) {
            global $wpdb;
            $sql_lookup_insert = "INSERT INTO SaveContactForm7_lookup (`CF7_created_title`, `CF7_version`, `CF7_form_id`,`CF7_from_wpposts_or_tbl` )
            SELECT * FROM (SELECT %s, %s, %d, %s) AS tmp
            WHERE NOT EXISTS (
                SELECT CF7_form_id
             FROM SaveContactForm7_lookup
             WHERE `CF7_version` = %s
                    AND `CF7_form_id` = %d
                    AND `CF7_from_wpposts_or_tbl` = %s
                            AND `CF7_removed_flag` = 'NO') LIMIT 1";

            if ($wpdb->query($wpdb->prepare($sql_lookup_insert, $CF7_form_title, $CF7_version, $CF7_form_id, $CF7_from, $CF7_version, $CF7_form_id, $CF7_from))) {
                $table = "SaveContactForm7_" . $wpdb->insert_id;
            } else {
                $table = "";
            }
            $sql_lookup_update = "UPDATE `SaveContactForm7_lookup` SET `CFDBA_tbl_name` = %s WHERE `lookup_id` = %d ";
            $wpdb->query($wpdb->prepare($sql_lookup_update, $table, $wpdb->insert_id));
            return $table;
        }

    }

    /* function to fetch table name from lookup table */
    if (!function_exists("nimble_get_tbl_from_lookup")) {

        function nimble_get_tbl_from_lookup($CF7_form_id, $CF7_title, $CF7_version, $CF7_from) {
            global $wpdb;

            $result = $wpdb->get_results($wpdb->prepare("select `CFDBA_tbl_name` from SaveContactForm7_lookup where CF7_form_id = %d  AND CF7_version = %s AND CF7_from_wpposts_or_tbl = %s AND CF7_removed_flag = 'NO' ", (int) $CF7_form_id, $CF7_version, $CF7_from));
            if (!empty($result[0]->CFDBA_tbl_name)) {
                return $result[0]->CFDBA_tbl_name;
            }
        }

    }
} // is_admin() closed

/** * **************************************** */
// function to submit contact from7 data to db
/** * **************************************** */
if (!function_exists("nimble_save_cf7_data")) {

    function nimble_save_cf7_data($wpcf7) {
        global $wpdb;

        global $wpcf7_shortcode_manager;
        $nimble_dir_pah = wp_upload_dir();
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_data = get_plugins();
        //check version and apply code accordingly
        if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "4.2.2") {
            $obj = WPCF7_ContactForm::get_current();
            $submission = WPCF7_Submission::get_instance();
            $shortcode = WPCF7_ShortcodeManager::get_instance();
            $form = $shortcode->scan_shortcode($obj->prop('form'));
            if ($submission) {
                $submited = array();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $submited['title'] = $wpcf7->title();
                $submited['posted_data'] = $submission->get_posted_data();
                $submited['uploaded_files'] = $submission->uploaded_files();
            }
            $table = nimble_get_tbl_from_lookup($obj->id(), $obj->title(), $CF7_version, $CF7_from);

            if ($table == "") {
                $table = nimble_lookup_entry($obj->id(), $obj->title(), $CF7_version, $CF7_from, $form);
            }
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9") {

            $obj = WPCF7_ContactForm::get_current();
            $shortcode = WPCF7_ShortcodeManager::get_instance();
            $form = $shortcode->scan_shortcode($obj->prop('form'));
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $submited = array();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $submited['title'] = $wpcf7->title();
                $submited['posted_data'] = $submission->get_posted_data();
                $submited['uploaded_files'] = $submission->uploaded_files();
            }
            $table = nimble_get_tbl_from_lookup($obj->id(), $obj->title(), $CF7_version, $CF7_from);
            if ($table == "") {
                $table = nimble_lookup_entry($obj->id(), $obj->title(), $CF7_version, $CF7_from, $form);
            }
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.7") {

            $submission = $wpcf7;
            $shortcode = WPCF7_ShortcodeManager::get_instance();
            $form = $shortcode->scan_shortcode($submission->form);
            if ($submission) {
                $submited = array();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $submited['title'] = $submission->title;
                $submited['posted_data'] = $submission->posted_data;
                $submited['uploaded_files'] = $submission->uploaded_files;
            }
            $table = nimble_get_tbl_from_lookup($submission->id, $submited['title'], $CF7_version, $CF7_from);

            if ($table == "") {
                $table = nimble_lookup_entry($submission->id, $submited['title'], $CF7_version, $CF7_from, $form);
            }
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.1.2") {

            $submission = $wpcf7;
            $shortcode = $wpcf7_shortcode_manager;
            $form = $shortcode->scan_shortcode($submission->form);
            if ($submission) {
                $submited = array();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $submited['title'] = $submission->title;
                $submited['posted_data'] = $submission->posted_data;
                $submited['uploaded_files'] = $submission->uploaded_files;
            }
            $table = nimble_get_tbl_from_lookup($submission->id, $submited['title'], $CF7_version, $CF7_from);

            if ($table == "") {
                $table = nimble_lookup_entry($submission->id, $submited['title'], $CF7_version, $CF7_from, $form);
            }
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0") {

            $submission = $wpcf7;
            $shortcode = $wpcf7_shortcode_manager;
            $form = $shortcode->scan_shortcode($submission->form);
            if ($submission) {
                $submited = array();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $submited['title'] = $submission->title;
                $submited['posted_data'] = $submission->posted_data;
                $submited['uploaded_files'] = $submission->uploaded_files;
            }
            $table = nimble_get_tbl_from_lookup($submission->id, $submited['title'], $CF7_version, $CF7_from);

            if ($table == "") {
                $table = nimble_lookup_entry($submission->id, $submited['title'], $CF7_version, $CF7_from, $form);
            }
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "2.4.6") {
            $submission = $wpcf7;
            $shortcode = $wpcf7_shortcode_manager;
            $form = $shortcode->scan_shortcode($submission->form);
            if ($submission) {
                $submited = array();
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $submited['title'] = $submission->title;
                $submited['posted_data'] = $submission->posted_data;
                $submited['uploaded_files'] = $submission->uploaded_files;
            }
            $table = nimble_get_tbl_from_lookup($submission->id, $submited['title'], $CF7_version, $CF7_from);

            if ($table == "") {
                $table = nimble_lookup_entry($submission->id, $submited['title'], $CF7_version, $CF7_from, $form);
            }
        }
// version check code ends here
        // $arr_default_field = array("_wpcf7", "_wpcf7_version", "_wpcf7_locale", "_wpcf7_unit_tag", "_wpnonce","g-recaptcha-response", "_wpcf7_is_ajax_call");
        $fields = array();
        $values = array();
        $posted_data = $submited['posted_data'];
        $allformdata = $form;
        $nimble_compare_array = array();
        foreach ($allformdata as $nimble_data_single_value) {
            if ($nimble_data_single_value['type'] != "submit" && $nimble_data_single_value['name'] != "") {
                $nimble_compare_array[] = $nimble_data_single_value['name'];
            }
        }
        /* for version 3.1 and 3.1.1 start */
        if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] == "3.1.1" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] == "3.1") {
            if (isset($table) && $table != "") {
                $row_fields = $wpdb->get_results("SHOW full COLUMNS FROM $table");
            }
            $fname = array_values($submited['uploaded_files']);
            $fieldname = key($submited['uploaded_files']);
            if ($fieldname != "") {
                $filename = explode("/", $fname[0]);
                if (array_key_exists($fieldname, $posted_data) && $posted_data[$fieldname] == "") {
                    $posted_data[$fieldname] = $filename[8];
                } else {
                    $posted_data = array_merge($posted_data, array($fieldname => $filename[8]));
                }
            } else {
                foreach ($row_fields as $row_field) {
                    if ($row_field->Comment == "file_field") {
                        $fieldname = $row_field->Field;
                    }
                }
            }
        }
        /* for version 3.1 and 3.1.1 ends */

        foreach ($posted_data as $key => $sdata) {
            if ((in_array($key, $nimble_compare_array))) {
                if (strstr($key, "-") != FALSE) {
                    $key = str_replace("-", "_", $key);
                }
                $fields[] = "`" . $key . "`";
                if (is_array($sdata)) {
                    $sdata == "" ? $values[] = 'NULL' : $values[] = "'" . esc_sql(implode(",", $sdata)) . "'";
                } else {
                    $sdata == "" ? $values[] = 'NULL' : $values[] = "'" . esc_sql($sdata) . "'";
                }
            }
        }
        $field = implode(",", $fields);
        $value = implode(",", $values);
        $sql_insert = "insert into $table ($field) values ($value)";
        
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s ", $table)) != $table) {

            if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "4.2.2") {

                $obj = WPCF7_ContactForm::get_current();
                $shortcode = WPCF7_ShortcodeManager::get_instance();
                $form = $shortcode->scan_shortcode($obj->prop('form'));
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_get_tbl_from_lookup($obj->id(), $obj->title(), $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9") {
                $obj = WPCF7_ContactForm::get_current();
                $shortcode = WPCF7_ShortcodeManager::get_instance();
                $form = $shortcode->scan_shortcode($obj->prop('form'));
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_get_tbl_from_lookup($obj->id(), $obj->title(), $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.7") {
                $obj = $wpcf7;
                $shortcode = WPCF7_ShortcodeManager::get_instance();
                $form = $shortcode->scan_shortcode($obj->form);
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_get_tbl_from_lookup($obj->id, $obj->title, $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.1") {
                $obj = $wpcf7;
                $shortcode = $wpcf7_shortcode_manager;
                $form = $shortcode->scan_shortcode($obj->form);
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_get_tbl_from_lookup($obj->id, $obj->title, $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0") {
                $obj = $wpcf7;
                $shortcode = $wpcf7_shortcode_manager;
                $form = $shortcode->scan_shortcode($obj->form);
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_get_tbl_from_lookup($obj->id, $obj->title, $CF7_version, $CF7_from);
            } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "2.4.5") {
                $obj = $wpcf7;
                $shortcode = $wpcf7_shortcode_manager;
                $form = $shortcode->scan_shortcode($obj->form);
                $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
                $CF7_from = getCF7_from();
                $table = nimble_get_tbl_from_lookup($obj->id, $obj->title, $CF7_version, $CF7_from);
            }


            foreach ($form as $key => $fields) {
                if ($fields['name'] == '')
                    continue;

                if (strstr($fields['name'], "-") != FALSE) {
                    $fields['name'] = str_replace("-", "_", $fields['name']);
                }
                if ($fields['type'] == 'file' || $fields['type'] == 'file*') {
                    $db_table_fields[] = "`" . $fields['name'] . "` text COMMENT 'file_field' default NULL ";
                } else {
                    $db_table_fields[] = "`" . $fields['name'] . "` text ";
                }
            }

            $db_table_field = implode(",", $db_table_fields);
            $sql_create = "CREATE TABLE IF NOT EXISTS " . $table . "(`id` int(8) NOT NULL PRIMARY KEY AUTO_INCREMENT, `created_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, " . $db_table_field . ") DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ";
            $wpdb->query($sql_create);
            $query_status = $wpdb->query($sql_insert);
        } else {
            $query_status = $wpdb->query($sql_insert);
        }

        if ($query_status == TRUE) {
            //insert image into plugin upload directory into wp-content/uploads... directory
            $id = $wpdb->insert_id;    // last inserted row id from databasr table
            $fieldname = key($submited['uploaded_files']); // form input file field name 
            if ($fieldname != "") {
                if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] == "3.1" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] == "3.1.1") {
                    $uploaded_file_info = pathinfo(implode("/", $fname)); //uploaded file info like basename,extension etc     
                } else {
                    $uploaded_file_info = pathinfo($submited['posted_data'][$fieldname]); //uploaded file info like basename,extension etc
                }
                if (!file_exists($nimble_dir_pah['basedir'] . "/nimble_uploads")) {

                   

                    mkdir($nimble_dir_pah['basedir'] . "/nimble_uploads", 0777);
                    chmod($nimble_dir_pah['basedir'] . "/nimble_uploads", 0777);

                    $filepath = array_values($submited['uploaded_files']);  // source location of the file
                    if (!isset($filepath)) {
                        
                        if (!file_exists($nimble_dir_pah['basedir'] . "/nimble_uploads/$id")) {
                            mkdir($nimble_dir_pah['basedir'] . "/nimble_uploads/$id", 0777);
                            chmod($nimble_dir_pah['basedir'] . "/nimble_uploads/$id", 0777);
                            echo $nimble_dir_pah['basedir'] . "/nimble_uploads/$id";
                            $newfile = $nimble_dir_pah['basedir'] . "/nimble_uploads/$id/" . $uploaded_file_info['basename']; // destination location of the file
                            copy($filepath[0], $newfile);
                        } else {
                         
                            $newfile = $nimble_dir_pah['basedir'] . "/nimble_uploads/$id/" . $uploaded_file_info['basename']; // destination location of the file
                            copy($filepath[0], $newfile);
                        }
                    }else{
                        if (!file_exists($nimble_dir_pah['basedir'] . "/nimble_uploads/$id")) {
                            mkdir($nimble_dir_pah['basedir'] . "/nimble_uploads/$id", 0777);
                            chmod($nimble_dir_pah['basedir'] . "/nimble_uploads/$id", 0777);
                            
                            $newfile = $nimble_dir_pah['basedir'] . "/nimble_uploads/$id/" . $uploaded_file_info['basename']; // destination location of the file
                            copy($filepath[0], $newfile);
                        }
                    }
                } else {

                    $filepath = array_values($submited['uploaded_files']); // source location of the file
                    if (isset($filepath)) {
                        if (!file_exists($nimble_dir_pah['basedir'] . "/nimble_uploads/$id")) {
                            mkdir($nimble_dir_pah['basedir'] . "/nimble_uploads/$id", 0777);



                            chmod($nimble_dir_pah['basedir'] . "/nimble_uploads/$id", 0777);

                            $newfile = $nimble_dir_pah['basedir'] . "/nimble_uploads/$id/" . $uploaded_file_info['basename']; // destination location of the file
                            copy($filepath[0], $newfile);
                        } else {
                            $newfile = $nimble_dir_pah['basedir'] . "/nimble_uploads/$id/" . $uploaded_file_info['basename']; // destination location of the file
                            copy($filepath[0], $newfile);
                        }
                    }
                }
            }
        }
//file uploads code ends here
    }

}
add_action('wpcf7_before_send_mail', 'nimble_save_cf7_data');

/* * *************************************************** */
// function to update table structure on contact form 7 edit  //
/* * *************************************************** */

if (!function_exists("nimble_after_cf7_update")) {

    function nimble_after_cf7_update($wpcf7) {
        global $wpdb;
        global $wpcf7_shortcode_manager;
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_data = get_plugins();
        if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "4.2.2") {
            $obj = WPCF7_ContactForm::get_current();
            $CF7_form_title = $obj->title();
            $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
            $CF7_from = getCF7_from();
            $shortcode = WPCF7_ShortcodeManager::get_instance();
            $form = $shortcode->scan_shortcode($obj->prop('form'));
            $table = nimble_get_tbl_from_lookup($obj->id(), $CF7_form_title, $CF7_version, $CF7_from);
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.9") {
            $obj = WPCF7_ContactForm::get_current();
            $CF7_form_title = $obj->title();
            $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
            $CF7_from = getCF7_from();
            $shortcode = WPCF7_ShortcodeManager::get_instance();
            $form = $shortcode->scan_shortcode($obj->prop('form'));
            $table = nimble_get_tbl_from_lookup($obj->id(), $CF7_form_title, $CF7_version, $CF7_from);
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.7") {
            $submission = $wpcf7;
            $CF7_form_title = $submission->title;
            $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
            $CF7_from = getCF7_from();
            $shortcode = WPCF7_ShortcodeManager::get_instance();
            $form = $shortcode->scan_shortcode($wpcf7->form);
            $table = nimble_get_tbl_from_lookup($wpcf7->id, $CF7_form_title, $CF7_version, $CF7_from);
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.1") {
            $shortcode = $wpcf7_shortcode_manager;
            $CF7_form_title = $wpcf7->title;
            $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
            $CF7_from = getCF7_from();
            $form = $shortcode->scan_shortcode($wpcf7->form);
            $table = nimble_get_tbl_from_lookup($wpcf7->id, $CF7_form_title, $CF7_version, $CF7_from);
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0-Beta" || $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "3.0") {
            $shortcode = $wpcf7_shortcode_manager;
            $CF7_form_title = $wpcf7->title;
            $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
            $CF7_from = getCF7_from();
            $form = $shortcode->scan_shortcode($wpcf7->form);
            $table = nimble_get_tbl_from_lookup($wpcf7->id, $CF7_form_title, $CF7_version, $CF7_from);
        } else if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] >= "2.4.5") {

            $shortcode = $wpcf7_shortcode_manager;
            $CF7_form_title = $wpcf7->title;
            $CF7_version = $plugin_data['contact-form-7/wp-contact-form-7.php']['Version'];
            $CF7_from = getCF7_from();
            $form = $shortcode->scan_shortcode($wpcf7->form);
            $table = nimble_get_tbl_from_lookup($wpcf7->id, $CF7_form_title, $CF7_version, $CF7_from);
        }
        $db_table_fields = $wpdb->get_col("DESC " . $table);

        foreach ($form as $key => $fields) {

            if ($fields['name'] == '')
                continue;

            if (strstr($fields['name'], "-") != FALSE) {
                $fields['name'] = str_replace("-", "_", $fields['name']);
            }

            if (!in_array($fields['name'], $db_table_fields)) {
                if ($fields['type'] == 'file' || $fields['type'] == 'file*') {
                    $newFieldsArr[] = "`" . $fields['name'] . "` text COMMENT 'file_field' default NULL ";
//                    $sql_alter = "ALTER TABLE %s ADD( %s text COMMENT 'file_field' default NULL )";
//                    $wpdb->query( $wpdb->prepare($sql_alter,$table,$fields['name']));
                } else {

                    $newFieldsArr[] = $fields['name'] . " text ";
//                    $sql_alter = "ALTER TABLE %s ADD( %s text )";
//                    $wpdb->query( $wpdb->prepare($sql_alter,$table,$fields['name']));
                }
            }
        }

        if (!empty($newFieldsArr)) {

            $newFieldsStr = implode(',', $newFieldsArr);

            $sql_alter = "ALTER TABLE $table ADD(" . $newFieldsStr . ")";
            $wpdb->query($sql_alter);
        }

        if ($CF7_form_title == "") {
            $CF7_form_title = "Untitled";
        }
        $sql_alter_lookup = "UPDATE SaveContactForm7_lookup SET `CF7_created_title` = %s WHERE `CFDBA_tbl_name` = %s";
        $wpdb->query($wpdb->prepare($sql_alter_lookup, $CF7_form_title, $table));
    }

}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (!is_plugin_active('contact-form-7-skins/index.php') && !is_plugin_active('contact-form-7-skins/contact-form-7-skins.php')) {
    do_action('wpcf7_after_update');
}
add_action('wpcf7_after_update', 'nimble_after_cf7_update');

if (!function_exists("nimble_get_tbl_from_lookup")) {

    function nimble_get_tbl_from_lookup($CF7_form_id, $CF7_title, $CF7_version, $CF7_from) {
        global $wpdb;
        //$result = $wpdb->get_results("select `CFDBA_tbl_name` from SaveContactForm7_lookup where CF7_form_id = " . $CF7_form_id . " AND CF7_created_title ='" . $CF7_title . "' AND CF7_version = '" . $CF7_version . "' AND CF7_from_wpposts_or_tbl = '" . $CF7_from . "' AND CF7_removed_flag = 'NO' ");
        $result = $wpdb->get_results($wpdb->prepare("select `CFDBA_tbl_name` from SaveContactForm7_lookup where CF7_form_id = %d  AND CF7_created_title = %s AND CF7_version = %s AND CF7_from_wpposts_or_tbl = %s AND CF7_removed_flag = 'NO' ", $CF7_form_id, $CF7_title, $CF7_version, $CF7_from));
        if (!empty($result[0]->CFDBA_tbl_name)) {
            return $result[0]->CFDBA_tbl_name;
        }
    }

}

if (!function_exists("getCF7_from")) {

    function getCF7_from() {
        global $wpdb;
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_data = get_plugins();
        if ($plugin_data['contact-form-7/wp-contact-form-7.php']['Version'] > "2.4.6") {
            $CF7_from = $wpdb->prefix . "posts";
        } else {
            $CF7_from = $wpdb->prefix . "contact_form_7";
        }
        return $CF7_from;
    }

}

/* function to insert data into lookup table on form creation */
if (!function_exists("nimble_lookup_entry")) {

    function nimble_lookup_entry($CF7_form_id, $CF7_form_title, $CF7_version, $CF7_from, $form) {
        global $wpdb;
        $sql_lookup_insert = "INSERT INTO SaveContactForm7_lookup (`CF7_created_title`, `CF7_version`, `CF7_form_id`,`CF7_from_wpposts_or_tbl` )
            SELECT * FROM (SELECT %s, %s, %d, %s) AS tmp
            WHERE NOT EXISTS (
                SELECT CF7_form_id
             FROM SaveContactForm7_lookup
             WHERE `CF7_version` = %s
                    AND `CF7_form_id` = %d
                    AND `CF7_from_wpposts_or_tbl` = %s
                            AND `CF7_removed_flag` = 'NO') LIMIT 1";

        if ($wpdb->query($wpdb->prepare($sql_lookup_insert, $CF7_form_title, $CF7_version, $CF7_form_id, $CF7_from, $CF7_version, $CF7_form_id, $CF7_from))) {
            $table = "SaveContactForm7_" . $wpdb->insert_id;
        } else {
            $table = "";
        }
        $sql_lookup_update = "UPDATE `SaveContactForm7_lookup` SET `CFDBA_tbl_name` = %s WHERE `lookup_id` = %d ";
        $wpdb->query($wpdb->prepare($sql_lookup_update, $table, $wpdb->insert_id));
        return $table;
    }

}
?>