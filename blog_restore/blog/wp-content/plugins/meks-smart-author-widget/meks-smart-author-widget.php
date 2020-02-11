<?php 
/*
Plugin Name: Meks Smart Author Widget
Plugin URI: https://mekshq.com
Description: Easily display your author/user profile info inside WordPress widget. Smart feature of this plugin is user/author "auto detection" which means that it can optionaly show author of current post on single post templates or on specific author archive.
Author: Meks
Version: 1.1.1
Author URI: https://mekshq.com
Text Domain: meks-smart-author-widget
Domain Path: /languages
*/


/*  Copyright 2013  Meks  (email : support@mekshq.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define ('MKS_AUTHOR_WIDGET_URL', trailingslashit(plugin_dir_url(__FILE__)));
define ('MKS_AUTHOR_WIDGET_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define ('MKS_AUTHOR_WIDGET_VER', '1.1.1');

/* Initialize Widget */
if(!function_exists('mks_author_widget_init')):
	function mks_author_widget_init() {
		require_once(MKS_AUTHOR_WIDGET_DIR.'inc/class-author-widget.php');
		register_widget('MKS_Author_Widget');
	}
endif;

add_action('widgets_init','mks_author_widget_init');

/* Load text domain */
function mks_load_author_widget_text_domain() {
  load_plugin_textdomain( 'meks-smart-author-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'mks_load_author_widget_text_domain' );

?>