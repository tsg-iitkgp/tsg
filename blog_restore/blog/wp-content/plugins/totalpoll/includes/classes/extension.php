<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Extension' ) ) :

	/**
	 * Extension Class
	 *
	 * @package TotalPoll/Classes/Extension
	 * @since   3.0.0
	 */
	abstract class TP_Extension {
		/**
		 * @var TP_Poll Poll object
		 * @access protected
		 * @since  3.0.0
		 */
		protected $poll;

		/**
		 * @var string Extension Text Domain.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $textdomain = '';

		/**
		 * @var string Extension Root File.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__FILE__ = __FILE__;

		/**
		 * @var string Extension url
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__URL__;

		/**
		 * @var string Extension path
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__PATH__;

		/**
		 * Extension constructor.
		 *
		 * @param bool|TP_Poll $poll Poll.
		 *
		 * @access public
		 * @since  3.0.0
		 */
		public function __construct( $poll ) {
			// Setup useful constant-like
			$this->__PATH__ = str_replace( '\\', '/', dirname( $this->__FILE__ ) . '/' );
			$this->__URL__  = content_url(
				str_replace(
					str_replace( '\\', '/', WP_CONTENT_DIR ),
					'',
					$this->__PATH__
				)
			);

			$this->poll = $poll;

			if ( ! empty( $this->textdomain ) ):
				$this->textdomain();
			endif;

		}

		/**
		 * Load text domain.
		 *
		 * @since  3.0.0
		 * @access protected
		 * @return bool
		 */
		protected function textdomain() {
			if ( ! empty( $this->textdomain ) ):
				$locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );

				return load_textdomain( $this->textdomain, "{$this->__PATH__}/languages/{$this->textdomain}-{$locale}.mo" );
			endif;

			return false;
		}

		/**
		 * Get asset URL.
		 *
		 * @param string $path Relative path to current extension path.
		 *
		 * @since 3.0.0
		 * @return string Asset URL.
		 */
		public function asset( $path ) {
			return $this->__URL__ . $path;
		}

		/**
		 * @param string $path Relative path to current extension path.
		 *
		 * @since 3.0.0
		 * @return string File path.
		 */
		public function path( $path ) {
			return $this->__PATH__ . $path;
		}

		/**
		 * Check whether this extension is enabled for a specific poll.
		 *
		 * @param object $poll     Poll.
		 * @param array  $settings Settings.
		 *
		 * @since  3.0.0
		 * @return bool Required.
		 */
		public static function required( $poll, $settings ) {
			return false;
		}

		/**
		 * On activation.
		 *
		 * @since  3.0.0
		 * @return void
		 */
		public static function activation() {

		}

		/**
		 * On deactivation.
		 *
		 * @since  3.0.0
		 * @return void
		 */
		public static function deactivation() {

		}

		/**
		 * On uninstall.
		 *
		 * @since  3.0.0
		 * @return void
		 */
		public static function uninstall() {

		}

	}


endif;