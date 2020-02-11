<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_REST_API' ) ) :

	/**
	 * REST API Class
	 *
	 * @package TotalPoll/Classes/REST_API
	 * @since   3.2.0
	 */
	class TP_REST_API {

		public function __construct() {
			$this->register_endpoints();
		}

		public function register_endpoints() {
			register_rest_route(
				'totalpoll/v1', '/poll/(?P<id>\d+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'get_poll' ),
				)
			);
		}

		public function get_poll( $args = array() ) {

			$status = get_post_status( (int) $args['id'] );
			if ( $status == 'publish' ):
				$poll    = TotalPoll::poll( (int) $args['id'] );
				$choices = $poll->choices();
				foreach ( $choices as $index => &$choice ):
					unset( $choice['checked'] );

					foreach ( $choice['content'] as $key => $value ):
						if ( ! in_array( $key, array( 'type', 'label', 'image', 'video', 'audio', 'html' ) ) ):
							unset( $choice['content'][ $key ] );
						endif;
					endforeach;

				endforeach;


				return array(
					'title'    => $poll->title(),
					'question' => $poll->question(),
					'choices'  => $choices,
				);
			endif;

			return new WP_Error( 'totalpoll_invalid_id', 'Invalid Poll ID', array( 'status' => 404 ) );

		}


	}

endif;