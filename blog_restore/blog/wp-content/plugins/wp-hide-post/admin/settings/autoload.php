<?php
if (!defined('SCB_SETTING_BASE'))
{
    define('SCB_SETTING_BASE', __DIR__);
}

if (!class_exists('wphp_settingsAPI'))
{
    include_once dirname(__FILE__) . "/class_settingsAPI.php";
}
if (!class_exists('wphp_settings'))
{
    include_once dirname(__FILE__) . "/class_settings.php";
}
if (!class_exists('wphp_settingsAPICustom'))
{
    include_once dirname(__FILE__) . "/class_settingsAPICustom.php";
}
if (!class_exists('Browser'))
{
    include_once dirname(__FILE__) . "/browser.php";
}
if (!function_exists('wphp_get_default_setting'))
{
    function wphp_get_default_setting($section = '', $name = "")
    {

        $default_general['max_upload_size'] = 200000;

        $settings['general'] = $default_general;

        $default = array();

        foreach ($settings as $key => $setting)
        {
            $default = array_merge($default, $setting);
        }

        return $section ? ($name ? @$settings[$section][$name] : @$settings[$section]) : $default;
    }
}
/**
 * Get system info
 *
 * @since       2.0
 * @access      public
 * @global      object $wpdb Used to query the database using the WordPress Database API
 * @return      string $return A string containing the info to output
 */

function scb_systems_info($sep="\n")
{
    global $wpdb;

    if (!class_exists('Browser'))
    {
        require_once 'browser.php';
    }

    $browser = new Browser();

    // Get theme info
    if (get_bloginfo('version') < '3.4')
    {
        $theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');
        $theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
    }
    else
    {
        $theme_data = wp_get_theme();
        $theme      = $theme_data->Name . ' ' . $theme_data->Version;
    }

    // Try to identify the hosting provider
    $host = wphp_get_host();

    $return = '### Begin System Info ###' . "$sep$sep";

    // Start with the basics...
    $return .= '-- Site Info' . "$sep$sep";
    $return .= 'Site URL:                 ' . site_url() . "$sep";
    $return .= 'Home URL:                 ' . home_url() . "$sep";
    $return .= 'Multisite:                ' . (is_multisite() ? 'Yes' : 'No') . "$sep";

    $return = apply_filters('scb_sysinfo_after_site_info', $return);

    // Can we determine the site's host?
    if ($host)
    {
        $return .= "$sep" . '-- Hosting Provider' . "$sep$sep";
        $return .= 'Host:                     ' . $host . "$sep";

        $return = apply_filters('scb_sysinfo_after_host_info', $return);
    }

    // The local users' browser information, handled by the Browser class
    $return .= "$sep" . '-- User Browser' . "$sep$sep";
    $return .= $browser;

    $return = apply_filters('scb_sysinfo_after_user_browser', $return);

    // WordPress configuration
    $return .= "$sep" . '-- WordPress Configuration' . "$sep$sep";
    $return .= 'Version:                  ' . get_bloginfo('version') . "$sep";
    $return .= 'Language:                 ' . (defined('WPLANG') && WPLANG ? WPLANG : 'en_US') . "$sep";
    $return .= 'Permalink Structure:      ' . (get_option('permalink_structure') ? get_option('permalink_structure') : 'Default') . "$sep";
    $return .= 'Active Theme:             ' . $theme . "$sep";
    $return .= 'Show On Front:            ' . get_option('show_on_front') . "$sep";

    // Only show page specs if frontpage is set to 'page'
    if (get_option('show_on_front') == 'page')
    {
        $front_page_id = get_option('page_on_front');
        $blog_page_id  = get_option('page_for_posts');

        $return .= 'Page On Front:            ' . ($front_page_id != 0 ? get_the_title($front_page_id) . ' (#' . $front_page_id . ')' : 'Unset') . "$sep";
        $return .= 'Page For Posts:           ' . ($blog_page_id != 0 ? get_the_title($blog_page_id) . ' (#' . $blog_page_id . ')' : 'Unset') . "$sep";
    }

    // Make sure wp_remote_post() is working
    $request['cmd'] = '_notify-validate';

    $params = array(
        'sslverify'  => false,
        'timeout'    => 60,
        'user-agent' => 'SCB/' . WPHP_VER,
        'body'       => $request,
    );

    $response = wp_remote_post('https://www.paypal.com/cgi-bin/webscr', $params);

    if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300)
    {
        $WP_REMOTE_POST = 'wp_remote_post() works';
    }
    else
    {
        $WP_REMOTE_POST = 'wp_remote_post() does not work';
    }

    $return .= 'Remote Post:              ' . $WP_REMOTE_POST . "$sep";
    $return .= 'Table Prefix:             ' . 'Length: ' . strlen($wpdb->prefix) . '   Status: ' . (strlen($wpdb->prefix) > 16 ? 'ERROR: Too long' : 'Acceptable') . "$sep";
    $return .= 'WP_DEBUG:                 ' . (defined('WP_DEBUG') ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set') . "$sep";
    $return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "$sep";
    $return .= 'Registered Post Types:    ' . implode(', ', scb_custom_post_types('names')) . "$sep";

    $return = apply_filters('scb_sysinfo_after_wordpress_config', $return);

    // EDD configuration

    $return = apply_filters('scb_sysinfo_after_plugin_config', $return);

    // Must-use plugins
    if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
    $muplugins = function_exists('get_mu_plugins')?get_mu_plugins():array();
    if (count($muplugins > 0))
    {
        $return .= "$sep" . '-- Must-Use Plugins' . "$sep$sep";

        foreach ($muplugins as $plugin => $plugin_data)
        {
            $return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "$sep";
        }

        $return = apply_filters('scb_sysinfo_after_wordpress_mu_plugins', $return);
    }

    // WordPress active plugins
    $return .= "$sep" . '-- WordPress Active Plugins' . "$sep$sep";

    $plugins        = get_plugins();
    $active_plugins = get_option('active_plugins', array());

    foreach ($plugins as $plugin_path => $plugin)
    {
        if (!in_array($plugin_path, $active_plugins))
        {
            continue;
        }

        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "$sep";
    }

    $return = apply_filters('scb_sysinfo_after_wordpress_plugins', $return);

    // WordPress inactive plugins
    $return .= "$sep" . '-- WordPress Inactive Plugins' . "$sep$sep";

    foreach ($plugins as $plugin_path => $plugin)
    {
        if (in_array($plugin_path, $active_plugins))
        {
            continue;
        }

        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "$sep";
    }

    $return = apply_filters('scb_sysinfo_after_wordpress_plugins_inactive', $return);

    if (is_multisite())
    {
        // WordPress Multisite active plugins
        $return .= "$sep" . '-- Network Active Plugins' . "$sep$sep";

        $plugins        = wp_get_active_network_plugins();
        $active_plugins = get_site_option('active_sitewide_plugins', array());

        foreach ($plugins as $plugin_path)
        {
            $plugin_base = plugin_basename($plugin_path);

            if (!array_key_exists($plugin_base, $active_plugins))
            {
                continue;
            }

            $plugin = get_plugin_data($plugin_path);
            $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "$sep";
        }

        $return = apply_filters('scb_sysinfo_after_wordpress_ms_plugins', $return);
    }

    // Server configuration (really just versioning)
    $return .= "$sep" . '-- Webserver Configuration' . "$sep$sep";
    $return .= 'PHP Version:              ' . PHP_VERSION . "$sep";
    $return .= 'MySQL Version:            ' . $wpdb->db_version() . "$sep";
    $return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "$sep";

    $return = apply_filters('scb_sysinfo_after_webserver_config', $return);

    // PHP configs... now we're getting to the important stuff
    $return .= "$sep" . '-- PHP Configuration' . "$sep$sep";
    $return .= 'Safe Mode:                ' . (ini_get('safe_mode') ? 'Enabled' : 'Disabled' . "$sep");
    $return .= 'Memory Limit:             ' . ini_get('memory_limit') . "$sep";
    $return .= 'Upload Max Size:          ' . ini_get('upload_max_filesize') . "$sep";
    $return .= 'Post Max Size:            ' . ini_get('post_max_size') . "$sep";
    $return .= 'Upload Max Filesize:      ' . ini_get('upload_max_filesize') . "$sep";
    $return .= 'Time Limit:               ' . ini_get('max_execution_time') . "$sep";
    $return .= 'Max Input Vars:           ' . ini_get('max_input_vars') . "$sep";
    $return .= 'Display Errors:           ' . (ini_get('display_errors') ? 'On (' . ini_get('display_errors') . ')' : 'N/A') . "$sep";

    $return = apply_filters('scb_sysinfo_after_php_config', $return);

    // PHP extensions and such
    $return .= "$sep" . '-- PHP Extensions' . "$sep$sep";
    $return .= 'cURL:                     ' . (function_exists('curl_init') ? 'Supported' : 'Not Supported') . "$sep";
    $return .= 'fsockopen:                ' . (function_exists('fsockopen') ? 'Supported' : 'Not Supported') . "$sep";
    $return .= 'SOAP Client:              ' . (class_exists('SoapClient') ? 'Installed' : 'Not Installed') . "$sep";
    $return .= 'Suhosin:                  ' . (extension_loaded('suhosin') ? 'Installed' : 'Not Installed') . "$sep";

    $return = apply_filters('scb_sysinfo_after_php_ext', $return);

    // Session stuff
    $return .= "$sep" . '-- Session Configuration' . "$sep$sep";

    $return .= 'Session:                  ' . (isset($_SESSION) ? 'Enabled' : 'Disabled') . "$sep";

    // The rest of this is only relevant is session is enabled
    if (isset($_SESSION))
    {
        $return .= 'Session Name:             ' . esc_html(ini_get('session.name')) . "$sep";
        $return .= 'Cookie Path:              ' . esc_html(ini_get('session.cookie_path')) . "$sep";
        $return .= 'Save Path:                ' . esc_html(ini_get('session.save_path')) . "$sep";
        $return .= 'Use Cookies:              ' . (ini_get('session.use_cookies') ? 'On' : 'Off') . "$sep";
        $return .= 'Use Only Cookies:         ' . (ini_get('session.use_only_cookies') ? 'On' : 'Off') . "$sep";
    }

    $return = apply_filters('scb_sysinfo_after_session_config', $return);

    $return .= "$sep" . '### End System Info ###';

    return $return;
}
