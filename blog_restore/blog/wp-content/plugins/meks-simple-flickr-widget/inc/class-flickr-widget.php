<?php
/*-----------------------------------------------------------------------------------*/
/*	Flicker Widget Class
/*-----------------------------------------------------------------------------------*/

class MKS_Flickr_Widget extends WP_Widget {

	var $defaults;

	function __construct() {
		$widget_ops = array( 'classname' => 'mks_flickr_widget', 'description' => __( 'Display your Flickr photostream', 'meks-simple-flickr-widget' ) );
		$control_ops = array( 'id_base' => 'mks_flickr_widget' );
		parent::__construct( 'mks_flickr_widget', __( 'Meks Flickr Widget', 'meks-simple-flickr-widget' ), $widget_ops, $control_ops );

		if ( !is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}

		$this->defaults = array(
			'title' => 'Flickr',
			'id' => '',
			'count' => 9,
			't_width' => 85,
			't_height' => 85,
			'randomize' => 0,
		);

		//Allow themes or plugins to modify default parameters
		$this->defaults = apply_filters( 'mks_flickr_widget_modify_defaults', $this->defaults );
	}

	function enqueue_styles() {
		wp_register_style( 'meks-flickr-widget', MKS_FLICKR_WIDGET_URL.'css/style.css', false, MKS_FLICKR_WIDGET_VER );
		wp_enqueue_style( 'meks-flickr-widget' );
	}


	function widget( $args, $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$photos = $this->get_photos( $instance['id'], $instance['count'] );

		if ( !empty( $photos ) ) {

			if($instance['randomize']){
				shuffle($photos);
			}

			$height = $instance['t_height'] ? $instance['t_height'].'px' : 'auto';
			$style = 'style="width: '.esc_attr( $instance['t_width'] ).'px; height: '.esc_attr( $height ).';"';

			echo '<ul class="flickr">';
			foreach ( $photos as $photo ) {
				echo '<li><a href="'.esc_url( $photo['img_url'] ).'" title="'.esc_attr( $photo['title'] ).'" target="_blank"><img src="'.esc_attr( $photo['img_src'] ).'" alt="'.esc_attr( $photo['title'] ).'" '.$style.'/></a></li>';
			}
			echo '</ul>';
			echo '<div class="clear"></div>';
		}
		echo $after_widget;
	}


	function get_photos( $id, $count = 8 ) {
		if ( empty( $id ) )
			return false;

		$transient_key = md5( 'mks_flickr_cache_' . $id . $count );
		$cached = get_transient( $transient_key );
		if ( !empty( $cached ) ) {
			return $cached;
		}

		$protocol = is_ssl() ? 'https' : 'http';
		$output = array();
		$rss = $protocol.'://api.flickr.com/services/feeds/photos_public.gne?id='.$id.'&lang=en-us&format=rss_200';
		$rss = fetch_feed( $rss );

		if ( is_wp_error( $rss ) ) {
			//check for group feed
			$rss = $protocol.'://api.flickr.com/services/feeds/groups_pool.gne?id='.$id.'&lang=en-us&format=rss_200';
			$rss = fetch_feed( $rss );
		}

		if ( !is_wp_error( $rss ) ) {
			$maxitems = $rss->get_item_quantity( $count );
			$rss_items = $rss->get_items( 0, $maxitems );
			foreach ( $rss_items as $item ) {
				$temp = array();
				$temp['img_url'] = esc_url( $item->get_permalink() );
				$temp['title'] = esc_html( $item->get_title() );
				$content =  $item->get_content();
				preg_match_all( "/<IMG.+?SRC=[\"']([^\"']+)/si", $content, $sub, PREG_SET_ORDER );
				$photo_url = str_replace( "_m.jpg", "_t.jpg", $sub[0][1] );
				$temp['img_src'] = esc_url( $photo_url );
				$output[] = $temp;
			}

			set_transient( $transient_key, $output, 60 * 60 * 24 );
		}

		//print_r( $output );

		return $output;
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['id'] = strip_tags( $new_instance['id'] );
		$instance['count'] = absint( $new_instance['count'] );
		$instance['t_width'] = absint( $new_instance['t_width'] );
		$instance['t_height'] = absint( $new_instance['t_height'] );
		$instance['randomize'] = isset( $new_instance['randomize'] ) ? 1 : 0;
		return $new_instance;
	}


	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'meks-simple-flickr-widget' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Flickr ID', 'meks-simple-flickr-widget' ); ?>:</label> <small><a href="http://idgettr.com/" target="_blank"><?php _e( 'What\'s my Flickr ID?', 'meks-simple-flickr-widget' ); ?></a></small>
			<input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo esc_attr( $instance['id'] ); ?>" />
			<small class="howto"><?php _e( 'Example ID: 23100287@N07', 'meks-simple-flickr-widget' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of photos', 'meks-simple-flickr-widget' ); ?>:</label>
			<input class="small-text" type="text" value="<?php echo absint( $instance['count'] ); ?>" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 't_width' ); ?>"><?php _e( 'Thumbnail width', 'meks-simple-flickr-widget' ); ?>:</label>
			<input class="small-text" type="text" value="<?php echo absint( $instance['t_width'] ); ?>" id="<?php echo $this->get_field_id( 't_width' ); ?>" name="<?php echo $this->get_field_name( 't_width' ); ?>" /> px
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 't_height' ); ?>"><?php _e( 'Thumbnail height', 'meks-simple-flickr-widget' ); ?>:</label>
			<input class="small-text" type="text" value="<?php echo absint( $instance['t_height'] ); ?>" id="<?php echo $this->get_field_id( 't_height' ); ?>" name="<?php echo $this->get_field_name( 't_height' ); ?>" /> px
			<small class="howto"><?php _e( 'Note: You can use "0" value for auto height', 'meks-simple-flickr-widget' ); ?></small>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'randomize' ); ?>">
			<input type="checkbox" value="1" id="<?php echo $this->get_field_id( 'randomize' ); ?>" name="<?php echo $this->get_field_name( 'randomize' ); ?>" <?php checked( $instance['randomize'], 1 ); ?>/> <?php _e( 'Randomize photos?', 'meks-simple-flickr-widget' ); ?>
		</label>
		</p>

		<?php
	}
}
?>
