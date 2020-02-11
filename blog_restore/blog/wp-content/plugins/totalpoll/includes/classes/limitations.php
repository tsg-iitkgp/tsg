<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Limitations' ) ) :

	/**
	 * Limitations Class
	 *
	 * @package TotalPoll/Classes/Limitations
	 * @since   3.0.0
	 */
	class TP_Limitations {
		/**
		 * @var object Poll object.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $poll = null;
		/**
		 * @var object Request object.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $request = null;
		/**
		 * @var object Limitations settings.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $limitations = array();
		/**
		 * @var object Limitations items.
		 * @access protected
		 * @since  3.0.0
		 */
		public $bag = null;

		/**
		 * Limitations constructor.
		 *
		 * @param object $poll    Poll object.
		 * @param object $request Request object.
		 *
		 * @since 3.0.0
		 */
		public function __construct( $poll, $request ) {
			if ( $poll instanceof TotalPoll::$classes['poll']['class'] ):
				$this->poll        = $poll;
				$this->limitations = $this->poll->settings( 'limitations' );
			endif;

			if ( $request instanceof TotalPoll::$classes['request']['class'] ):
				$this->request = $request;
			endif;
		}

		/**
		 * Run limitations.
		 *
		 * @param bool|false $purge Purge cached items.
		 *
		 * @since 3.0.0
		 * @return array Errors.
		 */
		public function run( $purge = false ) {
			if ( $this->bag === null || $purge !== false ):
				$this->bag = new WP_Error();
				$this->is_valid_cookies();
				$this->is_valid_ip();
				$this->is_valid_selection();
				$this->is_valid_results();
				$this->is_valid_quota();
				$this->is_valid_date();
				$this->is_valid_captcha();
				$this->is_valid_membership();
				$this->is_valid_ip_filter();
				$this->is_valid_vote_source();
			endif;

			return (array) $this->bag->get_error_messages();
		}

		/**
		 * Apply limitations.
		 *
		 * @since 3.0.0
		 * @return void.
		 */
		public function apply() {
			$this->apply_cookies();
			$this->apply_ip();
			$this->apply_membership();
		}

		/**
		 * Get errors.
		 *
		 * @since 3.0.0
		 * @return array Errors.
		 */
		public function errors() {
			if ( $this->bag instanceof WP_Error ) {
				return (array) $this->bag->get_error_messages();
			}

			return array();
		}

		/**
		 * Check cookies validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_cookies() {
			if ( ! empty( $this->limitations['cookies']['enabled'] ) ):

				$cookie_key = 'tpc_' . $this->limitations['unique_id'];

				if ( ! empty( $_COOKIE[ $cookie_key ] ) ):
					$this->poll->skip_to( 'results' );
					$this->bag->add(
						'cookies',
						__( 'You cannot vote again in this poll.', TP_TD )
					);

					return false;
				endif;

			endif;

			return true;
		}

		/**
		 * Check IP validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_ip() {
			if ( ! empty( $this->limitations['ip']['enabled'] ) ):

				$ip_cookie_key = 'tpic_' . $this->limitations['unique_id'];
				$ip_exists     = ! empty( $_COOKIE[ $ip_cookie_key ] );

				if ( ! $ip_exists && $this->request && $this->request->type === 'vote' ):
					$ip_key             = 'tpip_' . $this->request->ip . '_' . $this->limitations['unique_id'];
					$votes_quota_per_ip = absint( $this->limitations['ip']['votes_quota_per_ip'] );
					$votes_quota_per_ip = $votes_quota_per_ip < 1 ? 1 : $votes_quota_per_ip;
					$ip_votes           = (int) get_transient( $ip_key );
					$ip_exists          = $ip_votes >= $votes_quota_per_ip ? true : $ip_exists;

					if ( $ip_exists ):
						$this->apply_ip();
					endif;
				endif;

				if ( $ip_exists === true ):
					$this->poll->skip_to( 'results' );
					$this->bag->add(
						'ip',
						__( 'You cannot vote again in this poll.', TP_TD )
					);

					return false;
				endif;

			endif;

			return true;
		}


		/**
		 * Check selection validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_selection() {
			if ( $this->request && $this->request->type === 'vote' ):
				$minimum = isset( $this->limitations['selection']['minimum'] ) ? abs( $this->limitations['selection']['minimum'] ) : 1;
				$maximum = isset( $this->limitations['selection']['maximum'] ) ? abs( $this->limitations['selection']['maximum'] ) : 1;

				if ( count( $this->request->choices ) < $minimum ):
					$this->bag->add(
						'minimum',
						sprintf(
							_n( 'You have to vote for at least one choice.', 'You have to vote for at least %d choices.', $minimum, TP_TD ),
							$minimum
						)
					);

					return false;
				endif;

				if ( $maximum !== 0 && count( $this->request->choices ) > $maximum ):
					$this->bag->add(
						'maximum',
						sprintf(
							_n( 'You cannot vote for more than one choice.', 'You cannot vote for more than %d choices.', $maximum, TP_TD ),
							$maximum
						)
					);

					return false;
				endif;
			endif;

			return true;

		}

		/**
		 * Check captcha validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_captcha() {
			if ( $this->request && $this->request->type === 'vote' && ! empty( $this->limitations['captcha']['enabled'] ) ):
				$response = isset( $_REQUEST['g-recaptcha-response'] ) ? $_REQUEST['g-recaptcha-response'] : false;

				$site_secret = get_option( '_tp_options_captcha_site_secret', false );
				$valid       = false;

				if ( $site_secret ):
					$curl_handle = curl_init();
					// set URL and other appropriate options
					curl_setopt( $curl_handle, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify?secret={$site_secret}&response={$response}&remoteip={$this->request->ip}" );
					curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );
					curl_setopt( $curl_handle, CURLOPT_TIMEOUT, '10' );
					curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, false );

					$valid      = json_decode( curl_exec( $curl_handle ), true );
					$curl_error = curl_error( $curl_handle );

				endif;

				if ( ! $valid || ! is_array( $valid ) || ! array_key_exists( 'success', $valid ) || $valid['success'] == false || ! empty( $curl_error ) ):
					$this->bag->add( 'captcha', __( 'You have entered an invalid captcha code.', TP_TD ) );

					return false;
				endif;

			endif;

			return true;

		}

		/**
		 * Check quota.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_quota() {
			if ( ! empty( $this->limitations['quota']['enabled'] ) ):
				$quota = isset( $this->limitations['quota']['votes'] ) ? (int) $this->limitations['quota']['votes'] : false;
				if ( $quota && $quota > 0 && $quota <= $this->poll->votes() ):
					$this->poll->skip_to( 'results' );
					$this->bag->add(
						'quota',
						__( 'You cannot vote because the quota has been exceeded.', TP_TD )
					);

					return false;
				endif;
			endif;

			return true;
		}


		/**
		 * Check results viewing validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_results() {
			if ( ! empty( $this->limitations['results']['require_vote']['enabled'] ) && $this->request && $this->request->type === 'results' ):

				$cookie_key = 'tpc_' . $this->limitations['unique_id'];
				if ( empty( $_COOKIE[ $cookie_key ] ) ):
					$this->poll->skip_to( 'vote' );
					$this->bag->add(
						'require_vote',
						__( 'You cannot see results before voting.', TP_TD )
					);

					return false;
				endif;

			endif;

			return true;

		}

		/**
		 * Check date validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_date() {
			if ( ! empty( $this->limitations['date']['enabled'] ) ):
				$start_date = isset( $this->limitations['date']['start'] ) ? (int) $this->limitations['date']['start'] : false;
				$end_date   = isset( $this->limitations['date']['end'] ) ? (int) $this->limitations['date']['end'] : false;

				if ( $start_date && $start_date > current_time( 'timestamp' ) ):
					$this->bag->add(
						'start_date',
						__( 'You cannot vote because this poll has not started yet.', TP_TD )
					);

					return false;
				endif;

				if ( $end_date && $end_date < current_time( 'timestamp' ) ):
					$this->bag->add(
						'end_date',
						__( 'You cannot vote because this poll has been completed.', TP_TD )
					);
					$this->poll->skip_to( 'results' );

					return false;
				endif;
			endif;

			return true;
		}

		/**
		 * Check IP filtered list validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_ip_filter() {
			if ( ! empty( $this->limitations['ip']['enabled'] ) ):

				$list = isset( $this->limitations['ip']['filter'] ) ? explode( "\n", $this->limitations['ip']['filter'] ) : false;

				if ( $this->request && $this->request->type === 'vote' && ! empty( $list ) ):

					foreach ( $list as $rule ):
						// Strip whitespaces
						$rule        = str_replace( ' ', '', $rule );
						$blacklisted = isset( $rule[0] ) && $rule[0] === '-';
						$rule        = $blacklisted ? substr( $rule, 1 ) : $rule;
						// Generate a new regular expression
						$regexp = str_replace(
							'\*',
							'.+',
							preg_quote( $rule )
						);

						$regexp = "/{$regexp}/i";

						if ( preg_match( $regexp, $this->request->ip ) ):

							// When the IP is black-listed (prefixed with "-")
							if ( $blacklisted ):
								$this->bag->add(
									'ip',
									__( 'You cannot vote because this poll is not available in your region.', TP_TD )
								);

								return false;
							endif;

							break;
						endif;

					endforeach;

				endif;

			endif;

			return true;
		}

		/**
		 * Check membership validity.
		 *
		 * @since 3.0.0
		 * @return bool Validity.
		 */
		public function is_valid_membership() {
			if ( ! empty( $this->limitations['membership']['enabled'] ) ):
				global $current_user;
				$logged_in_roles = isset( $this->limitations['membership']['type'] ) ? (array) $this->limitations['membership']['type'] : false;

				if ( ! empty( $logged_in_roles ) ):

					if ( is_user_logged_in() ):

						if ( ! in_array( $current_user->roles[0], $logged_in_roles ) ):
							$this->bag->add(
								'membership_type',
								__( 'You cannot vote because you have insufficient rights.', TP_TD )
							);

							return false;
						endif;

						$member_key = 'tpm_' . get_current_user_id() . '_' . $this->limitations['unique_id'];

						if ( $this->request && $this->request->type === 'vote' && get_post_meta( $this->poll->id(), $member_key, true ) ):
							$this->bag->add(
								'membership_once',
								__( 'You cannot vote again in this poll.', TP_TD )
							);

							return false;
						endif;

					else:

						$this->bag->add(
							'logged_in',
							sprintf(
								__( 'You cannot vote because you are a guest, please <a href="%s">sign in</a> or <a href="%s">register</a>.', TP_TD ),
								wp_login_url(),
								wp_registration_url()
							)
						);

						return false;
					endif;

				endif;
			endif;

			return true;
		}

		/**
		 * Check source validity.
		 *
		 * @since 3.2.0
		 * @return bool Validity.
		 */
		public function is_valid_vote_source() {
			if ( $this->request && $this->request->type === 'vote' && ! empty( $this->limitations['direct']['enabled'] ) && empty( $_POST['totalpoll'] ) ):

				$this->bag->add(
					'direct',
					__( 'Voting via links has been disabled for this poll.', TP_TD )
				);

				return false;

			endif;

			return true;
		}

		/**
		 * Apply cookies
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function apply_cookies() {
			if ( ! empty( $this->limitations['cookies']['enabled'] ) ):

				$cookie_key = 'tpc_' . $this->limitations['unique_id'];

				if ( $this->request && $this->request->type === 'vote' && empty( $_COOKIE[ $cookie_key ] ) && count( $this->request->choices ) !== 0 ):
					$cookie_timeout_minutes = isset( $this->limitations['cookies']['timeout'] ) ? (int) $this->limitations['cookies']['timeout'] : 1440;
					// 2147483647 bellow is for 2038.
					$cookie_timeout_timestamp = $cookie_timeout_minutes == 0 ? 2147483647 : time() + ( MINUTE_IN_SECONDS * $cookie_timeout_minutes );
					setcookie( $cookie_key, true, $cookie_timeout_timestamp, COOKIEPATH, COOKIE_DOMAIN );
				endif;

			endif;

		}

		/**
		 * Apply IP
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function apply_ip() {
			if ( ! empty( $this->limitations['ip']['enabled'] )
			     && $this->request && $this->request->type === 'vote'
			     && count( $this->request->choices ) !== 0
			):

				$ip_cookie_key      = 'tpic_' . $this->limitations['unique_id'];
				$ip_key             = 'tpip_' . $this->request->ip . '_' . $this->limitations['unique_id'];
				$ip_votes           = (int) get_transient( $ip_key );
				$ip_timeout_minutes = isset( $this->limitations['ip']['timeout'] ) ? (int) $this->limitations['ip']['timeout'] : 1440;
				set_transient( $ip_key, $ip_votes + 1, MINUTE_IN_SECONDS * $ip_timeout_minutes );

				// 2147483647 bellow is for 2038.
				$cookie_timeout_timestamp = $ip_timeout_minutes == 0 ? 2147483647 : time() + ( MINUTE_IN_SECONDS * $ip_timeout_minutes );
				setcookie( $ip_cookie_key, true, $cookie_timeout_timestamp, COOKIEPATH, COOKIE_DOMAIN );

			endif;
		}

		/**
		 * Apply membership
		 *
		 * @since 3.1.0
		 * @return void
		 */
		public function apply_membership() {
			$logged_in_roles = isset( $this->limitations['membership']['type'] ) ? (array) $this->limitations['membership']['type'] : false;

			if ( ! empty( $logged_in_roles ) && ! empty( $this->limitations['membership']['enabled'] ) && ! empty( $this->limitations['membership']['once']['enabled'] ) ):

				$member_key    = 'tpm_' . get_current_user_id() . '_' . $this->limitations['unique_id'];
				$member_exists = get_post_meta( $this->poll->id(), $member_key, true );

				if ( ! $member_exists && $this->request && $this->request->type === 'vote' ):
					update_post_meta( $this->poll->id(), $member_key, true );
				endif;

			endif;
		}
	}


endif;