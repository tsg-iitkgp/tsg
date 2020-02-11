<?php

/**
 * Load embedded Redux Framework
 */

if ( !class_exists( 'ReduxFramework' ) && file_exists( get_template_directory()  . '/inc/redux/framework.php' ) ) {
    require_once get_template_directory() . '/inc/redux/framework.php';
}

if ( ! class_exists( 'Redux' ) ) {
    return;
}

/**
 * Redux params
 */

$opt_name = 'vlog_settings';

$args = array(
    'opt_name'             => $opt_name,
    'display_name'         => wp_kses( sprintf( __( 'Vlog Options%sTheme Documentation%s', 'vlog' ), '<a href="http://mekshq.com/documentation/vlog" target="_blank">', '</a>' ), wp_kses_allowed_html( 'post' )),
    'display_version'      => vlog_get_update_notification(),
    'menu_type'            => 'menu',
    'allow_sub_menu'       => true,
    'menu_title'           => esc_html__( 'Theme Options', 'vlog' ),
    'page_title'           => esc_html__( 'Vlog Options', 'vlog' ),
    'google_api_key'       => '',
    'google_update_weekly' => false,
    'async_typography'     => true,
    'admin_bar'            => true,
    'admin_bar_icon'       => 'dashicons-admin-generic',
    'admin_bar_priority'   => '100',
    'global_variable'      => '',
    'dev_mode'             => false,
    'update_notice'        => false,
    'customizer'           => false,
    'allow_tracking' => false,
    'ajax_save' => false,
    'page_priority'        => '27.11',
    'page_parent'          => 'themes.php',
    'page_permissions'     => 'manage_options',
    'menu_icon'            => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzNiAzNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzYgMzQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBwb2ludHM9IjE4LjIsMzEuOCAzLjYsNCAzMi45LDQgIi8+PC9zdmc+',
    'last_tab'             => '',
    'page_icon'            => 'icon-themes',
    'page_slug'            => 'vlog_options',
    'save_defaults'        => true,
    'default_show'         => false,
    'default_mark'         => '',
    'show_import_export'   => true,
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => false,
    'output_tag'           => true,
    'database'             => '',
    'system_info'          => false
);

$GLOBALS['redux_notice_check'] = 1;

/* Footer social icons */

$args['share_icons'][] = array(
    'url'   => 'https://www.facebook.com/mekshq',
    'title' => 'Like us on Facebook',
    'icon'  => 'el-icon-facebook'
);

$args['share_icons'][] = array(
    'url'   => 'http://twitter.com/mekshq',
    'title' => 'Follow us on Twitter',
    'icon'  => 'el-icon-twitter'
);

$args['intro_text'] = '';
$args['footer_text'] = '';


/**
 * Initialize Redux
 */

Redux::setArgs( $opt_name , $args );


/**
 * Include redux option fields (settings)
 */

include_once get_template_directory() . '/core/admin/options-fields.php';


/**
 * Append custom css to redux framework admin panel
 *
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_redux_custom_css' ) ):
    function vlog_redux_custom_css() {
        wp_register_style( 'vlog-redux-custom', get_template_directory_uri().'/assets/css/admin/options.css', array( 'redux-admin-css' ), VLOG_THEME_VERSION, 'all' );
        wp_enqueue_style( 'vlog-redux-custom' );
    }
endif;

add_action( 'redux/page/vlog_settings/enqueue', 'vlog_redux_custom_css' );


/**
 * Remove redux framework admin page
 *
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_remove_redux_page' ) ):
    function vlog_remove_redux_page() {
        remove_submenu_page( 'tools.php', 'redux-about' );
    }
endif;

add_action( 'admin_menu', 'vlog_remove_redux_page', 99 );


/**
 * Add our Sidebar generator custom field to redux
 *
 * @since  1.0
 */

if ( !function_exists( 'vlog_sidgen_field_path' ) ):
function vlog_sidgen_field_path($field) {
    return get_template_directory() . '/core/admin/options-custom-fields/sidgen/field_sidgen.php';
}
endif;

add_filter( "redux/vlog_settings/field/class/sidgen", "vlog_sidgen_field_path" );
?>