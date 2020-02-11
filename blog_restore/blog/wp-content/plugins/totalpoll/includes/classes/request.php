<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Request' ) ) :

	/**
	 * Request Class
	 *
	 * @package TotalPoll/Classes/Request
	 * @since   3.0.0
	 */
	class TP_Request {

		/**
		 * @var string IP.
		 * @access public
		 * @since  3.0.0
		 */
		public $ip = false;
		/**
		 * @var string User agent.
		 * @access public
		 * @since  3.0.0
		 */
		public $user_agent = false;
		/**
		 * @var bool Is XHR (AJAX).
		 * @access public
		 * @since  3.0.0
		 */
		public $is_ajax = false;
		/**
		 * @var int Poll ID.
		 * @access public
		 * @since  3.0.0
		 */
		public $id = false;
		/**
		 * @var object Poll object.
		 * @access public
		 * @since  3.0.0
		 */
		public $poll = false;
		/**
		 * @var string Request type (vote, results ...)
		 * @access public
		 * @since  3.0.0
		 */
		public $type = false;
		/**
		 * @var string Last view.
		 * @access public
		 * @since  3.0.0
		 */
		public $view = false;
		/**
		 * @var array Choices.
		 * @access public
		 * @since  3.0.0
		 */
		public $choices = array();
		/**
		 * @var int Page.
		 * @access public
		 * @since  3.0.0
		 */
		public $page = 1;
		/**
		 * @var array Custom fields.
		 * @access public
		 * @since  3.0.0
		 */
		public $fields = array();
		/**
		 * @var array Log.
		 * @access public
		 * @since  3.0.0
		 */
		public $log = array(
			'status'    => '',
			'time'      => '',
			'details'   => array(),
			'choices'   => array(),
			'ip'        => '',
			'useragent' => '',
			'fields'    => array(),
		);

		/**
		 * Request constructor.
		 *
		 * @param int|false $id Poll ID.
		 * @param array     $choices
		 *
		 * @since 3.0.0
		 */
		public function __construct( $id = false, $choices = array() ) {
			// Load options
			TotalPoll::options();

			$this->is_ajax = defined( 'DOING_AJAX' ) === true && DOING_AJAX === true;

			$this->id      = isset( $_REQUEST['totalpoll']['id'] ) ? (int) $_REQUEST['totalpoll']['id'] : get_the_ID();
			$this->choices = isset( $_REQUEST['totalpoll']['choices'] ) ? (array) $_REQUEST['totalpoll']['choices'] : $choices;
			$this->page    = isset( $_REQUEST['totalpoll']['page'] ) ? absint( $_REQUEST['totalpoll']['page'] ) : 1;
			$this->view    = isset( $_REQUEST['totalpoll']['view'] ) ? $_REQUEST['totalpoll']['view'] : 'view';

			if ( isset( $this->choices['other'] ) ):
				foreach ( $this->choices['other'] as $field ):
					if ( is_array( $field ) || str_replace( array( ' ', '.', ',', '-', '_', '/', '\\' ), '', $field ) === '' ):
						unset( $this->choices['other'] );
						continue;
					endif;
				endforeach;
			endif;

			if ( $this->id ):
				if ( get_post_meta( $this->id, 'choices', true ) !== false ):
					$this->poll = TotalPoll::poll( $this->id );
					$this->poll->requested_by( $this );
					$this->poll->page( $this->page );

					// Setup hooks
					add_action( 'totalpoll/actions/request/vote', array( $this, 'vote' ) );
					add_action( 'totalpoll/actions/request/results', array( $this, 'results' ) );
					add_action( 'totalpoll/actions/request/preview', array( $this, 'preview' ) );
					add_action( 'totalpoll/actions/request/next', array( $this, 'next' ) );
					add_action( 'totalpoll/actions/request/previous', array( $this, 'previous' ) );
					add_action( 'totalpoll/actions/request/load', array( $this, 'load' ) );
				endif;
			endif;

			// Get real IP
			if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ):
				$this->ip = $_SERVER['HTTP_CLIENT_IP'];
			elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ):
				$this->ip = current( explode( ', ', $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
			elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ):
				$this->ip = $_SERVER['HTTP_X_FORWARDED'];
			elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ):
				$this->ip = $_SERVER['HTTP_FORWARDED_FOR'];
			elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ):
				$this->ip = $_SERVER['HTTP_FORWARDED'];
			elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ):
				$this->ip = $_SERVER['REMOTE_ADDR'];
			endif;

			// User agent
			$this->user_agent = $_SERVER['HTTP_USER_AGENT'];

		}

		/**
		 * Getter.
		 *
		 * @param string $name Name.
		 * @param array  $args Args.
		 *
		 * @since 3.0.0
		 * @return mixed Value.
		 */
		public function __call( $name, $args ) {
			return isset( $this->{$name} ) ? $this->{$name} : false;
		}

		/**
		 * Vote request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function vote() {
			$this->type = 'vote';

			if ( $this->poll !== false ): // Check poll

				$fields             = $this->poll->fields();
				$fields_errors      = apply_filters( 'totalpoll/filters/poll/vote/fields', $fields->run(), $this );
				$limitations_errors = apply_filters( 'totalpoll/filters/poll/vote/limitations', $this->poll->limitations()->run(), $this );

				$this->log = array(
					'status'    => '',
					'time'      => current_time( 'timestamp' ),
					'details'   => array(),
					'choices'   => array(),
					'ip'        => $this->ip,
					'useragent' => $this->user_agent,
					'fields'    => $this->poll->fields()->to_array(),
				);

				if ( empty( $limitations_errors ) === true && empty( $fields_errors ) === true && $this->poll->vote( $this->choices ) === true ):
					$this->poll->limitations()->apply();
					$this->log['status']                      = true;
					$this->log['fields']['__submission_date'] = date( 'Y-m-d H:i e' );
					TotalPoll::instance( 'meta-pageable' )->add( 'submissions', $this->id, $this->log['fields'] );

					if ( ! empty( $fields->unique_fields ) ):
						foreach ( $fields->unique_fields as $field ):
							$unique_key = sanitize_key( $field->name() . '_' . $field->value() );
							update_post_meta( $this->id, "_tp_unique_{$unique_key}", true );
						endforeach;
					endif;

					if ( is_user_logged_in() ):

						if ( $this->poll->settings( 'logs', 'details', 'user_id', 'enabled' ) ):
							$this->log['details']['user_id'] = 'User ID: ' . get_current_user_id();
						endif;

						if ( $this->poll->settings( 'logs', 'details', 'user_login', 'enabled' ) ):
							$this->log['details']['user_login'] = 'Username: ' . wp_get_current_user()->user_login;
						endif;

						if ( $this->poll->settings( 'logs', 'details', 'user_email', 'enabled' ) ):
							$this->log['details']['user_email'] = 'User email: ' . wp_get_current_user()->user_email;
						endif;

					endif;

					/**
					 * After successful vote request.
					 *
					 * @param object $poll Poll object.
					 *
					 * @since  3.1.1
					 * @action totalpoll/actions/poll/vote
					 */
					do_action( 'totalpoll/actions/poll/vote', $this->poll, $this->log );
				else:
					$this->log['status']  = false;
					$this->log['details'] = array_merge( $this->log['details'], $limitations_errors, $fields_errors );
				endif;

				foreach ( $this->poll->choices() as $index => $choice ):
					if ( $index === 'other' || in_array( $choice['index'], $this->choices ) ):
						$this->log['choices'][] = $choice['content']['label'];
					endif;
				endforeach;

				if ( $this->log['status'] == true ):
					if ( $this->poll->settings( 'notifications', 'triggers', 'new_vote', 'enabled' ) == true ):
						ob_start();

						$deactivate = admin_url( "post.php?post={$this->id}&action=edit" );
						$meta       = array(
							__( 'IP', TP_TD )      => $this->log['ip'],
							__( 'Browser', TP_TD ) => $this->log['useragent'],
							__( 'Time', TP_TD )    => date( 'Y-m-d H:i:s', $this->log['time'] ),
							__( 'Choices', TP_TD ) => implode( '<br/>', (array) $this->log['choices'] ),
							__( 'Details', TP_TD ) => implode( '<br/>', (array) $this->log['details'] ),
						);

						foreach ( $this->log['fields'] as $label => $value ):
							$meta[ sprintf( __( 'Field: %s', TP_TD ), $label ) ] = implode( '<br/>', (array) $value );
						endforeach;

						include TP_PATH . 'includes/admin/partials/notification-email.php';

						$email_html = ob_get_clean();

						wp_mail( $this->poll->settings( 'notifications', 'email' ), sprintf( __( 'Poll notification: New vote - %s' ), $this->poll->question() ), $email_html, array( 'Content-Type: text/html; charset=UTF-8' ) );

					endif;
				endif;

				// Google analytics
				$ga_script = "<script type=\"text/javascript\">try {%s} catch(e){console.log('%s');}</script>";
				$ga_calls  = '';

				foreach ( $this->log['choices'] as $choice ):
					$ga_calls .= sprintf( "ga('send', 'event', '[POLLS] %s', 'vote', '%s');", esc_attr( $this->poll->question() ), esc_attr( $choice ) );
				endforeach;

				$ga = sprintf( $ga_script, $ga_calls, defined( 'WP_DEBUG' ) && WP_DEBUG === true ? '[TotalPoll] Please setup Google Analytics.' : '' );

				$this->poll->after_content( $ga );

				// Save log
				if ( $this->poll->settings( 'logs', 'enabled' ) == true ):
					$this->log = apply_filters( 'totalpoll/filters/poll/log', $this->log, $this );
					TotalPoll::instance( 'meta-pageable' )->add( 'logs', $this->id, $this->log );
				endif;

			endif;
		}

		/**
		 * Results request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function results() {
			$this->type = 'results';
		}

		/**
		 * Preview request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function preview() {
			$this->type = 'preview';
		}

		/**
		 * Next page request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function next() {
			$this->poll->skip_to( $this->view );
			$this->poll->page( $this->page + 1 );
		}

		/**
		 * Previous page request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function previous() {
			$this->poll->skip_to( $this->view );
			$this->poll->page( $this->page - 1 );
		}

		/**
		 * Load poll async
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function load() {
			$this->type = 'load';
		}

		/**
		 * AJAX request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function ajax() {
			echo empty( $this->poll ) ? '' : $this->poll->render();
			wp_die();
		}


	}


endif;