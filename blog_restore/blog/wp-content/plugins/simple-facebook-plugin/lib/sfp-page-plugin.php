<?php
/**
* Main Facebook Page Plagin File. Creates widget, shortcode and template tag.
*
* @package SF Plugin
* @author Ilya K.
* @since 1.4
*/

/**
* Page Plugin Widget Class
*
* Contains the main functions for SF and stores variables
*
* @since SF Plugin 1.4
* @author Ilya K.
*/

class SFPPagePluginWidget extends WP_Widget {
	
	/**
	 * Register widget with WordPress
	 */
	function __construct() {
		$widget_ops = array( 'description' => 'Display Facebook Page Plugin.' );
		parent::__construct( 'sfp_page_plugin_widget', $name = 'SFP - Facebook Page Plugin',  $widget_ops);
	}

	/**
	 * Front-end
	 */
	function widget( $args, $instance ) {
		
		global $sfplugin;

		// Add-ons hook
		$instance = apply_filters( "sfp_before_page_plugin", $instance, $this, $sfplugin );
		do_action( "sfp_before_page_plugin", $args, $instance, $this, $sfplugin );

		// extract user options
		extract( $args );
		extract( $instance );
		
		// Stnadar WP output
		echo $before_widget;
		
		// check for title
		$title = apply_filters( 'widget_title', $title );
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		
		// include Page Plugin view
		include( $sfplugin->pluginPath . 'views/view-page-plugin.php' );

		// Add-ons hook
		do_action("sfp_after_page_plugin", $args, $instance, $this, $sfplugin );
		
		// Stnadar WP output
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		// save new options
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['url'] 			= strip_tags( $new_instance['url'] );
		$instance['width']			= strip_tags( $new_instance['width'] );
		$instance['height']			= strip_tags( $new_instance['height'] );
		$instance['hide_cover']		= isset( $new_instance['hide_cover'] );
		$instance['show_facepile']	= isset( $new_instance['show_facepile'] );
		$instance['small_header']	= isset( $new_instance['small_header'] );
		$instance['timeline']		= isset( $new_instance['timeline'] );
		$instance['events']			= isset( $new_instance['events'] );
		$instance['messages']		= isset( $new_instance['messages'] );
		
		$instance['locale']			= strip_tags( $new_instance['locale'] );
	
		// Add-ons hook
		apply_filters( 'sfp_page_plugin_widget_update', $instance, $new_instance, $old_instance );
		
		return $instance;
	}

	/**
	 * Back-end form
	 */
	function form( $instance ) {

		global $sfplugin;
		
		$default = array(
			// default options
			'title' 		=> 'Our Facebook Page',
			'url'			=> 'http://www.facebook.com/topdevs.net',
			'width'			=> '',
			'height'		=> '',
			'hide_cover'	=> false,
			'show_facepile'	=> true,
			'small_header'	=> false,
			'timeline'		=> false,
			'events'		=> false,
			'messages'		=> false,
			
			'locale'		=> 'en_US'
		);

		// Add-ons hook
		//$instance = apply_filters( 'sfp_page_plugin_form', $instance, $default, $this, $sfplugin );

		extract( array_merge( $default, $instance ) ); ?>

		<?php 
			// Add-ons hook
			do_action( "sfp_page_plugin_widget_form_start", $instance, $this, $sfplugin );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Facebook Page URL:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
		</p>

		<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?></label> 
		<input size="6" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />px
		<p class="description">
			The plugin will automatically adapt to the width of its parent element on page load if parent's width is lower than plugin's. Min is 180 and Max is 500.
		</p>	
		<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:'); ?></label> 
		<input size="6" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />px
		<p class="description">Minimum is 70.</p>
		<?php 
			// Add-ons hook
			do_action( "sfp_page_plugin_widget_form_after_inputs", $instance, $this, $sfplugin );
		?>
		<table>
			<tr><td>
				<br/>
				<b><?php _e('Header'); ?></b>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('hide_cover'); ?>"><?php _e('Hide Cover Photo'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('hide_cover'); ?>" type="checkbox" name="<?php echo $this->get_field_name('hide_cover'); ?>" <?php checked(isset($hide_cover) ? $hide_cover : 0); ?>/>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('show_facepile'); ?>"><?php _e('Show Friend\'s Faces'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('show_facepile'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_facepile'); ?>" <?php checked(isset($show_facepile) ? $show_facepile : 0); ?>/>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('small_header'); ?>"><?php _e('Small Header'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('small_header'); ?>" type="checkbox" name="<?php echo $this->get_field_name('small_header'); ?>" <?php checked(isset($small_header) ? $small_header : 0); ?>/>
			</td></tr>
			<tr><td>
				<br/>
				<b><?php _e('Tabs'); ?></b>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('timeline'); ?>"><?php _e('Show Timeline'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('timeline'); ?>" type="checkbox" name="<?php echo $this->get_field_name('timeline'); ?>" <?php checked(isset($timeline) ? $timeline : 0); ?>/> 
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('events'); ?>"><?php _e('Show Events'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('events'); ?>" type="checkbox" name="<?php echo $this->get_field_name('events'); ?>" <?php checked(isset($events) ? $events : 0); ?>/> 
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('messages'); ?>"><?php _e('Show Messages'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('messages'); ?>" type="checkbox" name="<?php echo $this->get_field_name('messages'); ?>" <?php checked(isset($messages) ? $messages : 0); ?>/> 
			</td></tr>
			<?php 
				// Add-ons hook
				do_action("sfp_page_plugin_widget_form_after_checkboxes", $instance, $this, $sfplugin );
			?>
		</table>
		<br/>
		<p>
			<label for="<?php echo $this->get_field_id('locale'); ?>"><?php _e('Language'); ?></label> 
			<select name="<?php echo $this->get_field_name('locale'); ?>">
			<?php foreach ( $sfplugin->locales as $code => $name ) : ?>
				<option <?php selected(( $locale == $code) ? 1 : 0); ?> value="<?php echo $code; ?>" ><?php echo $name; ?></option>
			<?php endforeach; ?>
			</select>
		</p>
		<?php 
			do_action( "sfp_page_plugin_widget_form_end", $instance, $this, $sfplugin );
		?>

	<?php }
	
} // class SFPPagePluginWidget

/**
 * Add Page Plugin 'Shortcode'
 *
 * @since SF Plugin 1.4
 * @author Ilya K.
 */

function sfp_page_plugin_shortcode ( $instance ) {

	global $sfplugin;

	$instance = ( !$instance ) ? array() : $instance;

	// Add-ons hook
	$instance = apply_filters( "sfp_before_page_plugin", $instance, $sfplugin );

	extract( array_merge( array(
		// default options
		'url'			=> 'http://www.facebook.com/topdevs.net',
		'width'			=> '',
		'height'		=> '',
		'hide_cover'	=> false,
		'show_facepile'	=> true,
		'small_header'	=> false,
		'timeline'		=> false,
		'events'		=> false,
		'messages'		=> false,
		'locale'		=> 'en_US'
	), $instance ) );

	ob_start();

	// include Page Plugin view
	include( $sfplugin->pluginPath . 'views/view-page-plugin.php' );

	return ob_get_clean();
}


/**
* Add Page Plugin 'Template Tag'
* 
* @since SF Plugin 1.4
* @author Ilya K.
*/

function sfp_page_plugin ( $instance = array() ) { 
	
	global $sfplugin;

	// Add-ons hook
	$instance = apply_filters( "sfp_before_page_plugin", $instance, $sfplugin );
	
	extract( array_merge( array(
		// default options
		'url'			=> 'http://www.facebook.com/topdevs.net',
		'width'			=> '',
		'height'		=> '',
		'hide_cover'	=> false,
		'show_facepile'	=> true,
		'small_header'	=> false,
		'timeline'		=> false,
		'events'		=> false,
		'messages'		=> false,
		'locale'		=> 'en_US'
	), $instance ) );
	
	// include Page Plugin view
	include( $sfplugin->pluginPath . 'views/view-page-plugin.php' );
}

?>