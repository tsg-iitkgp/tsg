<?php // User Submitted Posts - Enqueue Script & Style

if (!defined('ABSPATH')) die();



function usp_enqueueResources() {
	
	global $usp_options;
	
	$min_images   = $usp_options['min-images'];
	$include_js   = $usp_options['usp_include_js'];
	$form_type    = $usp_options['usp_form_version'];
	$display_url  = $usp_options['usp_display_url'];
	$recaptcha    = $usp_options['usp_recaptcha'];
	
	$protocol = is_ssl() ? 'https://' : 'http://';
	
	$display_url = esc_url_raw(trim($display_url));
	$current_url = esc_url_raw($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	$current_url = remove_query_arg(array('submission-error', 'error', 'success', 'post_id'), $current_url);
	
	$plugin_url  = plugins_url() .'/'. basename(dirname(dirname(__FILE__)));
	
	$custom_url  = get_stylesheet_directory_uri() .'/usp/usp.css';
	$custom_path = get_stylesheet_directory() .'/usp/usp.css';
	
	$usp_css = ($form_type === 'custom' && file_exists($custom_path)) ? $custom_url : $plugin_url . '/resources/usp.css';
	
	$display_js = false;
	$display_css = false;
	
	if (empty($display_url) || strpos($current_url, $display_url) !== false) {
		
		if ($include_js == true)      $display_js = true;
		if ($form_type !== 'disable') $display_css = true;
		
	}
	
	if ($display_css) {
		
		wp_enqueue_style('usp_style', $usp_css, false, null, 'all');
		
	}
	
	if ($display_js) {
		
		if ($recaptcha === 'show') {
			
			wp_enqueue_script('usp_recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null);
		
		}
		
		wp_enqueue_script('usp_cookie',  $plugin_url .'/resources/jquery.cookie.js',      array('jquery'), null);
		wp_enqueue_script('usp_parsley', $plugin_url .'/resources/jquery.parsley.min.js', array('jquery'), null);
		wp_enqueue_script('usp_core',    $plugin_url .'/resources/jquery.usp.core.js',    array('jquery'), null);
		
		usp_inline_script();
		
	}
	
}
add_action('wp_enqueue_scripts', 'usp_enqueueResources');



// WP >= 4.5
function usp_inline_script() {
	
	$wp_version = get_bloginfo('version');
	
	if (version_compare($wp_version, '4.5', '>=')) {
		
		global $usp_options;
		
		$min_images   = isset($usp_options['min-images'])   ? $usp_options['min-images']   : '';
		$max_images   = isset($usp_options['max-images'])   ? $usp_options['max-images']   : '';
		$custom_field = isset($usp_options['custom_name'])  ? $usp_options['custom_name']  : '';
		$usp_casing   = isset($usp_options['usp_casing'])   ? $usp_options['usp_casing']   : '';
		$usp_response = isset($usp_options['usp_response']) ? $usp_options['usp_response'] : ''; 
		$print_casing = $usp_casing ? 'true' : 'false';
		
		$script  = 'var usp_custom_field = '.       json_encode($custom_field) .'; ';
		$script .= 'var usp_case_sensitivity = '.   json_encode($print_casing) .'; ';
		$script .= 'var usp_challenge_response = '. json_encode($usp_response) .'; ';
		$script .= 'var usp_min_images = '.         json_encode($min_images)   .'; ';
		$script .= 'var usp_max_images = '.         json_encode($max_images)   .'; ';
		
		wp_add_inline_script('usp_core', $script, 'before');
		
	}
	
}



// WP < 4.5
function usp_print_scripts() { 
	
	$wp_version = get_bloginfo('version');
	
	if (version_compare($wp_version, '4.5', '<')) {
		
		global $usp_options;
		
		$min_images   = isset($usp_options['min-images'])   ? $usp_options['min-images']   : '';
		$max_images   = isset($usp_options['max-images'])   ? $usp_options['max-images']   : '';
		$custom_field = isset($usp_options['custom_name'])  ? $usp_options['custom_name']  : '';
		$usp_casing   = isset($usp_options['usp_casing'])   ? $usp_options['usp_casing']   : '';
		$usp_response = isset($usp_options['usp_response']) ? $usp_options['usp_response'] : ''; 
		$print_casing = $usp_casing ? 'true' : 'false';
		
		if (!is_admin()) : ?>
			
			<script type="text/javascript">
				var usp_custom_field = <?php       echo json_encode($custom_field); ?>; 
				var usp_case_sensitivity = <?php   echo json_encode($print_casing); ?>; 
				var usp_challenge_response = <?php echo json_encode($usp_response); ?>; 
				var usp_min_images = <?php         echo json_encode($min_images);   ?>; 
				var usp_max_images = <?php         echo json_encode($max_images);   ?>; 
			</script>
			
		<?php endif;
		
	}
	
}
add_action('wp_print_scripts','usp_print_scripts');



function usp_load_admin_styles($hook) {
	
	global $pagenow;
	
	/*
		wp_enqueue_style($handle, $src, $deps, $ver, $media)
		wp_enqueue_script($handle, $src, $deps, $ver, $in_footer)
	*/
	
	$base = plugins_url() .'/'. basename(dirname(dirname(__FILE__)));
	
	if ($hook === 'settings_page_user-submitted-posts/user-submitted-posts') {
		
		wp_enqueue_style('usp_admin_styles', $base .'/resources/usp-admin.css', array(), USP_VERSION, 'all');
		wp_enqueue_script('usp_admin_script', $base .'/resources/jquery.usp.admin.js', array('jquery'), USP_VERSION, false);
		
	}
	
	if ($pagenow === 'edit.php') {
		
		wp_enqueue_style('usp_posts_styles', $base .'/resources/usp-posts.css', array(), USP_VERSION, 'all');
		
	}
	
}
add_action('admin_enqueue_scripts', 'usp_load_admin_styles');


