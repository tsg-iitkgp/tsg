<?php

add_action( 'widgets_init', 'vlog_register_widgets' );

/**
 * Register widgets
 *
 * Callback function which includes widget classes and initialize theme specific widgets
 *
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_register_widgets' ) ) :
	function vlog_register_widgets() {

		//Include widget classes
		include_once get_template_directory() .'/core/widgets/posts.php';
		include_once get_template_directory() .'/core/widgets/adsense.php';
		include_once get_template_directory() .'/core/widgets/categories.php';
		

		register_widget( 'VLOG_Posts_Widget' );
		register_widget( 'VLOG_Adsense_Widget' );
		register_widget( 'VLOG_Category_Widget' );

		if(vlog_is_series_active()){
			include_once get_template_directory() .'/core/widgets/series.php';
			register_widget( 'VLOG_Series_Widget' );
		}
	}
endif;



?>