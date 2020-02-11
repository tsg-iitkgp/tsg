<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

/**
 * Poll Widget.
 *
 * @since   2.0.0
 * @package TotalPoll\Widgets\Single
 */
if ( ! class_exists( 'TP_Widget' ) && class_exists( 'WP_Widget' ) ):

	class TP_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			parent::__construct(
				'totalpoll', // Base ID
				__( 'Poll - TotalPoll', TP_TD ), // Name
				array( 'description' => __( 'Poll widget', TP_TD ), ) // Args
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see   WP_Widget::widget()
		 * @since 2.0.0
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 *
		 * @return void
		 */
		public function widget( $args, $instance ) {
			if ( ! empty( $instance['poll_id'] ) ):
				/**
				 * Filter widget title
				 *
				 * @since  2.0.0
				 * @filter widget_title
				 *
				 * @param Widget title
				 */
				$title = apply_filters( 'widget_title', $instance['title'] );

				echo $args['before_widget'];

				if ( ! empty( $title ) ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}

				echo TotalPoll::poll( $instance['poll_id'] )->render();

				echo $args['after_widget'];
			endif;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 *
		 * @return void
		 */
		public function form( $instance ) {
			$defaults = array( 'title' => __( 'Poll', TP_TD ), 'poll_id' => 0 );
			$instance = wp_parse_args( $instance, $defaults );

			?>
			<p>
				<label><?php _e( 'Title:', TP_TD ); ?>
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
				</label>
			</p>
			<p>
				<label for="poll_id"><?php _e( 'Poll', TP_TD ); ?></label>
				<br>
				<select name="<?php echo $this->get_field_name( 'poll_id' ); ?>" class="widefat">
					<?php foreach ( (array) get_posts( 'post_type=poll&posts_per_page=-1' ) as $index => $poll ): ?>
						<option value="<?php echo $poll->ID; ?>" <?php selected( $instance['poll_id'], $poll->ID ); ?>>
							<?php echo $poll->post_title; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']   = strip_tags( $new_instance['title'] );
			$instance['poll_id'] = (int) strip_tags( $new_instance['poll_id'] );

			return $instance;
		}

	}

endif;

return 'TP_Widget';