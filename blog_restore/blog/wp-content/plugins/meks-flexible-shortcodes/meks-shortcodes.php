<?php
/*
Plugin Name: Meks Flexible Shortcodes
Plugin URI: http://mekshq.com
Description: Add some cool elements to your post/page content. Smart styling options will make it fit into any theme design. Columns, buttons, higlights, social icons, tabs, toggles, accordions, pull quotes, progress bars, separators, dropcaps...
Author: Meks
Version: 1.3.1
Author URI: http://mekshq.com
Text Domain: meks-flexible-shortcodes
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


define( 'MKS_SC_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'MKS_SC_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'MKS_SC_PLUGIN_VER', '1.3.1' );

add_action( 'init', 'mks_register_shortcodes' );

/* Register Shortcodes */
if ( !function_exists( 'mks_register_shortcodes' ) ) :
	function mks_register_shortcodes() {

		//Register column shortcodes
		$columns = array(
			'col',
			'one_half',
			'one_third',
			'one_quarter',
			'two_thirds',
		);

		foreach ( $columns as $item ) {
			add_shortcode( 'mks_'.$item, 'mks_columns_sc' );
		}

		//Default list of shortcodes
		$shortcodes = array(
			'button',
			'dropcap',
			'pullquote',
			'separator',
			'highlight',
			'social',
			'icon',
			'progressbar',
			'accordion',
			'accordion_item',
			'toggle',
			'tabs',
			'tab_item'
		);

		//Allow themes and plugins to enable/disable specific shortcodes
		$shortcodes = apply_filters( 'mks_shortcodes_list', $shortcodes );

		//Add shortcodes
		foreach ( $shortcodes as $shortcode_name ) {
			add_shortcode( 'mks_'.$shortcode_name, 'mks_'.$shortcode_name.'_sc' );
		}
	}
endif;

/* Include helper functions */
include_once MKS_SC_PLUGIN_DIR.'inc/functions.php';

/* Include all shortcodes callback functions */
include_once MKS_SC_PLUGIN_DIR.'inc/callbacks.php';


add_action( 'admin_init', 'mks_shortcodes_ui' );

/* Add shortcodes UI support */
if ( !function_exists( 'mks_shortcodes_ui' ) ) :
	function mks_shortcodes_ui() {
		if ( current_user_can( 'edit_posts' ) ) {
			add_filter( 'mce_buttons', 'mks_register_shortcode_buttons' );
			add_filter( 'mce_external_plugins', 'mks_register_shortcode_plugin' );
		}
	}
endif;

/* Register shortcodes UI button */
if ( !function_exists( 'mks_register_shortcode_buttons' ) ) :
	function mks_register_shortcode_buttons( $buttons ) {
		array_push( $buttons, 'mks_shortcodes_button' );
		return $buttons;
	}
endif;

/* Register shortcodes plugin */
if ( !function_exists( 'mks_register_shortcode_plugin' ) ) :
	function mks_register_shortcode_plugin( $plugins ) {
		$plugins['mks_shortcodes'] = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/admin.js';
		return $plugins;
	}
endif;


add_action( 'wp_ajax_mks_generate_shortcodes_ui', 'mks_generate_shortcodes_ui' );

/* Generate shortcodes UI */
if ( !function_exists( 'mks_generate_shortcodes_ui' ) ) :
	function mks_generate_shortcodes_ui() {

		//Default UI sections/tabs
		$sections = array(
			array( 'id' => 'columns', 'title' => __( 'Columns', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'separators', 'title' => __( 'Separators', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'highlights', 'title' => __( 'Highlights', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'dropcaps', 'title' => __( 'Dropcaps', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'buttons', 'title' => __( 'Buttons', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'pullquotes', 'title' => __( 'Pull Quotes', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'social', 'title' => __( 'Social Icons', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'tabs', 'title' => __( 'Tabs', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'toggles', 'title' => __( 'Toggles', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'accordion', 'title' => __( 'Accordions', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'icons', 'title' => __( 'Icons', 'meks-flexible-shortcodes' ) ),
			array( 'id' => 'progressbars', 'title' => __( 'Progress Bars', 'meks-flexible-shortcodes' ) )
		);

		//Allow themes and plugins to enable/disable specific shortcodes UI panel
		$sections = apply_filters( 'mks_shortcodes_ui_args', $sections );

?>

  <div id="mks_wrap" class="wrap">

	  <div id="mks_tabs">
	  	<ul>
	  	<?php foreach ( $sections as $section ) : ?>
				<li><a data-nav="<?php echo $section['id'];?>" href="#"><?php echo $section['title'];?></a></li>
	  	<?php endforeach; ?>
	  	</ul>
	  </div>

	  <div id="mks_tabs_sections">
	  <?php foreach ( $sections as $section ) : ?>
			<div id="tabs-<?php echo $section['id'];?>" class="hidable wrap" style="display: none;">
			<?php include trailingslashit( plugin_dir_path( __FILE__ ) ).'templates/'.$section['id'].'.php'; ?>
			</div>
	  <?php endforeach; ?>
		</div>

  </div>

	<script type="text/javascript">
	/* <![CDATA[ */
	(function($) {
	    	$('#mks_tabs a').click(function(e) {
	    		e.preventDefault();
	    		mks_tabs_switch($(this));
			 });

			 mks_tabs_switch($('#mks_tabs a').first());

			 function mks_tabs_switch(obj){
			 	$('#mks_tabs_sections .hidable').hide();
	    	$('#mks_tabs_sections #tabs-'+ obj.attr('data-nav')).show();
	    	$('#mks_tabs li').removeClass('current');
	    	obj.parent().addClass('current');
	     }

	})(jQuery);
	/* ]]> */
	</script>
  <?php die();
	}
endif;

/* Load admin scripts and styles */
add_action( 'admin_enqueue_scripts', 'mks_shortcodes_load_admin_scripts' );

function mks_shortcodes_load_admin_scripts() {

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	wp_register_style( 'mks_shortcodes_fntawsm_css', MKS_SC_PLUGIN_URL.'css/font-awesome/css/font-awesome.min.css', false, MKS_SC_PLUGIN_VER, 'screen' );
	wp_enqueue_style( 'mks_shortcodes_fntawsm_css' );


	wp_register_style( 'mks_shortcodes_simple_line_icons', MKS_SC_PLUGIN_URL.'css/simple-line/simple-line-icons.css', false, MKS_SC_PLUGIN_VER, 'screen' );
	wp_enqueue_style( 'mks_shortcodes_simple_line_icons' );


	wp_register_style( 'mks_shortcodes_admin_css', MKS_SC_PLUGIN_URL.'css/admin.css', false, MKS_SC_PLUGIN_VER, 'screen' );
	wp_enqueue_style( 'mks_shortcodes_admin_css' );

	wp_enqueue_style( 'wp-jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-dialog' );

}

/* Load frontend scripts and styles */
add_action( 'wp_enqueue_scripts', 'mks_shortcodes_load_scripts' );

function mks_shortcodes_load_scripts() {

	wp_register_style( 'mks_shortcodes_fntawsm_css', MKS_SC_PLUGIN_URL.'css/font-awesome/css/font-awesome.min.css', false, MKS_SC_PLUGIN_VER, 'screen' );
	wp_enqueue_style( 'mks_shortcodes_fntawsm_css' );


	wp_register_style( 'mks_shortcodes_simple_line_icons', MKS_SC_PLUGIN_URL.'css/simple-line/simple-line-icons.css', false, MKS_SC_PLUGIN_VER, 'screen' );
	wp_enqueue_style( 'mks_shortcodes_simple_line_icons' );


	wp_register_style( 'mks_shortcodes_css', MKS_SC_PLUGIN_URL.'css/style.css', false, MKS_SC_PLUGIN_VER, 'screen' );
	wp_enqueue_style( 'mks_shortcodes_css' );

	wp_enqueue_script( 'mks_shortcodes_js', MKS_SC_PLUGIN_URL.'js/main.js', array( 'jquery'), MKS_SC_PLUGIN_VER );


}

/* Load text domain */
function mks_load_shortcodes_text_domain() {
	load_plugin_textdomain( 'meks-flexible-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'mks_load_shortcodes_text_domain' );


?>