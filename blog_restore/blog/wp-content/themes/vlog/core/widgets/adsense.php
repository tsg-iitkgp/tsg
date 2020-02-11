<?php
/*-----------------------------------------------------------------------------------*/
/*	Adsense Widget Class
/*-----------------------------------------------------------------------------------*/

class VLOG_Adsense_Widget extends WP_Widget { 

	var $defaults;

	function __construct() {
		$widget_ops = array( 'classname' => 'vlog_adsense_widget', 'description' => esc_html__('You can place Google AdSense or any JavaScript related code inside this widget', 'vlog') );
		$control_ops = array( 'id_base' => 'vlog_adsense_widget' );
		parent::__construct( 'vlog_adsense_widget', esc_html__('Vlog Adsense', 'vlog'), $widget_ops, $control_ops );

		$this->defaults = array( 
				'title' => '',
				'adsense_code' => '',
				'expand' => 1
			);
	}

	
	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );

		if($instance['expand']){
			$before_widget = preg_replace('/class="(.*)"/', 'class="$1 vlog-widget-expand"', $before_widget);
		}

		echo $before_widget;

		if ( !empty($title) ) {
			echo $before_title . $title . $after_title;
		}
		
		$adsense_code = !empty( $instance['adsense_code'] ) ? $instance['adsense_code'] : '';

		?>
		<div class="vlog-adsense-wrapper">
			<?php echo $adsense_code; ?>
		</div>
	
		<?php
			echo $after_widget;
	}

	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['adsense_code'] = current_user_can('unfiltered_html') ? $new_instance['adsense_code'] : stripslashes( wp_filter_post_kses( addslashes($new_instance['adsense_code']) ) );
		$instance['expand'] = isset($new_instance['expand']) ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>
			
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title', 'vlog'); ?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
			<small class="howto"><?php esc_html_e('Leave empty for no title', 'vlog'); ?></small>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'adsense_code' )); ?>"><?php esc_html_e('Adsense Code', 'vlog'); ?>:</label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'adsense_code' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'adsense_code' )); ?>" class="widefat" rows="10"><?php echo $instance['adsense_code']; ?></textarea>
			<small class="howto"><?php esc_html_e('Place your Google Adsense code or any JavaScript related advertising code.', 'vlog'); ?></small>
		</p>

		<p>	
			<label for="<?php echo esc_attr( $this->get_field_id( 'expand' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'expand' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'expand' ) ); ?>" value="1" <?php checked(1, $instance['expand']); ?>/>
				<?php esc_html_e('Expand widget area to 300px', 'vlog'); ?>
			</label>
			<small class="howto"><?php esc_html_e('Check this option if you are using 300px wide adsense so it can fit your widget area', 'vlog'); ?></small>

		</p>
				
	<?php
	}
}

?>