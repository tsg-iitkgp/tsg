<?php 
/* Get format content of a specific post */

add_action('wp_ajax_vlog_format_content', 'vlog_get_format_content');
add_action('wp_ajax_nopriv_vlog_format_content', 'vlog_get_format_content');

if(!function_exists('vlog_get_format_content')):
function vlog_get_format_content(){
	$post_id = absint( $_POST['id'] );
	$format = $_POST['format'];

	$p = new WP_Query( array('p' => $post_id));

	if($p->have_posts()):

		while( $p->have_posts() ) : $p->the_post();

			 $output = hybrid_media_grabber( array( 'type' => $format, 'split_media' => true ) );
			 echo $output;

		endwhile;

	endif;

	wp_reset_postdata();

	die();
}
endif;

/* Watch later handlers */

//Prints the wath later
add_action('wp_ajax_vlog_watch_later', 'vlog_watch_later_posts');
add_action('wp_ajax_nopriv_vlog_watch_later', 'vlog_watch_later_posts');

add_action('wp_ajax_vlog_load_watch_later', 'vlog_load_watch_later');
add_action('wp_ajax_nopriv_vlog_load_watch_later', 'vlog_load_watch_later');

if(!function_exists('vlog_watch_later_posts')):
function vlog_watch_later_posts(){
	$post_id = absint( $_POST['id'] );
	$what = $_POST['what'];

	$ids = vlog_get_watch_later_posts();
	
	if($what == 'add'){
		$ids[] = $post_id;	
	} else {
		$ids = array_diff( $ids , array( $post_id ));
	}
	
	if(!isset($_COOKIE['vlog_watch_later'])){
		setcookie('vlog_watch_later', implode( "_", array( $post_id ) ), time() + 30 * 86400, COOKIEPATH, COOKIE_DOMAIN);
	} else {
		$ids = array_values($ids);
		setcookie('vlog_watch_later', implode( "_", $ids ), time() + 30 * 86400, COOKIEPATH, COOKIE_DOMAIN);
	}

	if(!empty($ids)){
		$args = array('post__in' => $ids );
		vlog_print_watch_later_posts( $args );
	}

	die();
}
endif;


/* Update latest theme version (we use internally for new version introduction text) */

add_action('wp_ajax_vlog_update_version', 'vlog_update_version');

if(!function_exists('vlog_update_version')):
function vlog_update_version(){
	update_option('vlog_theme_version',VLOG_THEME_VERSION);
	die();
}
endif;


/* Hide welcome screen */

add_action('wp_ajax_vlog_hide_welcome', 'vlog_hide_welcome');

if(!function_exists('vlog_hide_welcome')):
function vlog_hide_welcome(){
	update_option('vlog_welcome_box_displayed', true);
	die();
}
endif;


?>