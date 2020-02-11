<?php

add_action( 'init', 'vlog_register_menus' );

/**
 * Register menus
 *
 * Callback function theme menus registration and init
 * 
 * @return void
 * @since  1.0
 */

if( !function_exists( 'vlog_register_menus' ) ) :
    function vlog_register_menus() {
	    register_nav_menu('vlog_main_menu', esc_html__( 'Main Menu' , 'vlog'));
	   	register_nav_menu('vlog_social_menu', esc_html__( 'Social Menu' ,'vlog'));
	   	register_nav_menu('vlog_secondary_menu_1', esc_html__( 'Secondary Menu 1' , 'vlog'));
	   	register_nav_menu('vlog_secondary_menu_2', esc_html__( 'Secondary Menu 2' , 'vlog'));
    }
endif;

?>