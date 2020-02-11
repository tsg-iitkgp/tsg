<?php
/**
 * Admin Bootstrap Class
 *
 * @package TotalPoll/Admin/Bootstrap
 * @since   3.0.0
 */
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Bootstrap' ) ) :

	class TP_Admin_Bootstrap {

		private static $pages = array(
			'tp-about'      => 'pages/about.php',
			'tp-extensions' => 'pages/extensions.php',
			'tp-store'      => 'pages/store.php',
			'tp-templates'  => 'pages/templates.php',
			'tp-tools'      => 'pages/tools.php',
			'tp-options'    => 'pages/options.php',
			'tp-update'     => 'pages/update.php',
			'tp-support'    => 'pages/support.php',
		);

		private $pointers_type = '';

		public function __construct() {
			// Ajax
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ):
				TotalPoll::instance( 'admin/ajax' );
			endif;

			// Redirect to about page
			if ( get_transient( 'totalpoll_redirect_to_about' ) ):
				delete_transient( 'totalpoll_redirect_to_about' );
				add_action( 'init', array( $this, 'redirect_to_about' ) );
			endif;

			// Editor
			add_action( 'current_screen', array( $this, 'screen' ) );

			// Columns
			add_filter( 'manage_poll_posts_columns', array( $this, 'columns' ) );
			add_action( 'manage_poll_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );

			// Update notification
			add_filter( 'plugins_api', array( $this, 'information' ), 10, 3 );
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update' ) );

			// Register menus
			add_action( 'admin_menu', array( $this, 'menus' ) );

			// Enqueue assets
			if ( isset( $_GET['page'] ) && substr( $_GET['page'], 0, 3 ) == 'tp-' ):
				add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
			endif;

			// Update nag
			add_action( 'in_plugin_update_message-' . plugin_basename( TP_ROOT ), array( $this, 'update_nag' ) );

			do_action( 'totalpoll/actions/admin/init' );

			$this->extensions();
		}

		public static function get_default_settings() {
			return array(
				'limitations'   =>
					array(
						'cookies'    => array( 'timeout' => '1440', 'votes_per_cookie' => 1 ),
						'ip'         => array( 'timeout' => '1440', 'filter' => '', 'votes_quota_per_ip' => 1 ),
						'direct'     => array(),
						'membership' => array( 'type' => array(), 'once' => array() ),
						'captcha'    => array(
							'site_key'    => get_option( '_tp_options_captcha_site_key' ),
							'site_secret' => get_option( '_tp_options_captcha_site_secret' ),
						),
						'quota'      => array( 'votes' => '1000' ),
						'date'       => array( 'start' => false, 'end' => false ),
						'selection'  => array( 'minimum' => 1, 'maximum' => 1 ),
						'unique_id'  => current_time( 'timestamp' ) . mt_rand( 100, 999 ),
					),
				'results'       =>
					array(
						'require_vote' => array(),
						'order'        => array( 'by' => '', 'direction' => 'desc' ),
						'hide'         => array( 'until' => '', 'content' => '' ),
						'format'       => array(),
					),
				'choices'       =>
					array(
						'order'      => array( 'by' => '', 'direction' => 'desc' ),
						'other'      => array(),
						'pagination' => array( 'per_page' => 0 ),
					),
				'design'        =>
					array(
						'template'   => array( 'name' => 'default' ),
						'scroll'     => array(),
						'one_click'  => array(),
						'transition' => array( 'type' => 'fade' ),
						'settings'   => array(),
						'preset'     => '',
					),
				'fields'        => array(),
				'screens'       => array(),
				'logs'          => array(
					'enabled' => false,
					'details' => array(),
				),
				'notifications' => array(
					'email'    => get_option( 'admin_email', '' ),
					'triggers' => array(
						'new_vote'   => array(),
						'new_choice' => array(),
					),
				),
			);
		}

		public function screen() {
			global $current_screen;

			// Pointers
			if ( $current_screen->post_type === 'poll' && $current_screen->action !== 'add' && ! get_option( 'totalpoll_hide_global_pointers' ) ):
				$this->pointers_type = 'global';
			endif;

			if ( $current_screen->base === 'post' && $current_screen->post_type === 'poll' ):
				// Include the editor class and initialize it
				TotalPoll::instance( 'admin/editor' );
				// Pointers
				if ( ! get_option( 'totalpoll_hide_poll_pointers' ) ):
					$this->pointers_type = 'poll';
				endif;
			endif;

			if ( $current_screen->post_type === 'poll' && get_option( 'totalpoll_hide_welcome' ) === false ):
				// Welcome
				add_action( 'all_admin_notices', array( $this, 'welcome' ) );
			endif;

			if ( $current_screen->post_type === 'poll' && PHP_VERSION_ID < 50400 && get_option( 'totalpoll_hide_php_version' ) === false ):
				// Welcome
				add_action( 'all_admin_notices', array( $this, 'php_version' ) );
			endif;

			// Enqueue pointers
			if ( ! empty( $this->pointers_type ) ):
				add_action( 'admin_enqueue_scripts', array( $this, 'pointers' ) );
			endif;

			if ( $current_screen->base === 'options-general' && isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true' ):
				flush_rewrite_rules();
			endif;

			add_action( 'add_meta_boxes', array( $this, 'metaboxes' ) );
		}

		public function metaboxes() {
			add_meta_box( 'tc-customization', __( 'Customization', 'totalpoll' ), array( $this, 'customization_metabox' ), 'poll', 'side' );
		}

		public function customization_metabox() {
			include_once TP_PATH . 'includes/admin/partials/customization.php';
		}

		public function redirect_to_about() {
			wp_redirect( admin_url( 'edit.php?post_type=poll&page=tp-about' ) );
			exit;
		}

		public function columns( $columns ) {
			return array(
				'cb'                => '<input type="checkbox" />',
				'title'             => __( 'Title' ),
				'shortcode'         => __( 'Shortcode', TP_TD ),
				'results-shortcode' => __( 'Results Shortcode', TP_TD ),
				'total-votes'       => __( 'Total votes', TP_TD ),
				'date'              => __( 'Date' ),
			);
		}

		public function columns_content( $column, $id ) {
			if ( $column === 'shortcode' || $column === 'results-shortcode' ):
				printf(
					'<input class="%s" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" onclick="this.onfocus()" value="%s">',
					$column === 'results-shortcode' ? 'widefat' : '',
					esc_attr(
						$column === 'shortcode' ?
							"[totalpoll id=\"$id\"]" :
							"[totalpoll id=\"$id\" fragment=\"results\"]"
					)
				);
			elseif ( $column === 'total-votes' ):
				echo number_format( (int) get_post_meta( $id, 'votes', true ) );
			endif;
		}

		public function update_nag() {
			if ( ! get_option( 'totalpoll_license_status', false ) ):
				printf(
					'<hr><strong>%s</strong>',
					sprintf( __( 'You need to <a href="%s">enter your Envato license key</a> in order to update TotalPoll.', TP_TD ), admin_url( 'edit.php?post_type=poll&page=tp-update' ) )
				);
			endif;
		}

		public function information( $false, $action, $response ) {
			if ( $action === 'plugin_information' && $response->slug == 'totalpoll' ):

				$url              = add_query_arg( array( 'license' => get_option( 'totalpoll_license_key' ), 'domain' => $_SERVER['SERVER_NAME'], 'from' => TP_VERSION ), TP_API_ENDPOINT . 'update' );
				$api_response     = wp_remote_get( $url );
				$totalpoll_update = json_decode( wp_remote_retrieve_body( $api_response ) );

				if ( isset( $totalpoll_update->new_version ) ):
					$totalpoll_update->sections = (array) $totalpoll_update->sections;

					return $totalpoll_update;
				endif;

			endif;

			return false;
		}

		public function update( $transient ) {
			if ( ! empty( $transient->response ) || ! empty( $transient->checked ) ):

				$url              = add_query_arg( array( 'license' => get_option( 'totalpoll_license_key' ), 'domain' => $_SERVER['SERVER_NAME'], 'from' => TP_VERSION ), TP_API_ENDPOINT . 'update' );
				$api_response     = wp_remote_get( $url );
				$totalpoll_update = json_decode( wp_remote_retrieve_body( $api_response ) );

				if ( isset( $totalpoll_update->new_version ) && version_compare( $totalpoll_update->new_version, TP_VERSION, '>' ) ):

					$totalpoll_update->package = str_replace(
						array( '__KEY__', '__DOMAIN__' ),
						array( get_option( 'totalpoll_license_key', '' ), $_SERVER['SERVER_NAME'] ),
						$totalpoll_update->package
					);

					// Disable auto update
					if ( ! get_option( 'totalpoll_license_status', false ) ):
						$totalpoll_update->package = '';
					endif;

					$transient->response[ $totalpoll_update->plugin ] = $totalpoll_update;

					if ( isset( $transient->no_update[ $totalpoll_update->plugin ] ) ) :
						unset( $transient->no_update[ $totalpoll_update->plugin ] );
					endif;
				endif;

			endif;

			return $transient;
		}

		public function activate( $key ) {
			$status = get_option( 'totalpoll_license_status', false );

			if ( $status == true ):
				return true;
			endif;

			$url          = add_query_arg( array( 'license' => $key, 'domain' => $_SERVER['SERVER_NAME'] ), TP_API_ENDPOINT . 'activate' );
			$api_response = wp_remote_get( $url );
			$response     = json_decode( wp_remote_retrieve_body( $api_response ) );

			$status = isset( $response->success ) ? true : false;

			update_option( 'totalpoll_license_key', $key );
			update_option( 'totalpoll_license_status', $status );

			return $status;
		}

		public function store( $purge = false ) {
			if ( $purge ):
				delete_transient( 'totalpoll_store_downloads' );
			endif;

			$downloads = get_transient( 'totalpoll_store_downloads' );

			if ( $downloads === false ):
				$api_response = wp_remote_get( TP_API_ENDPOINT . 'totalpoll/store/?license=' . get_option( 'totalpoll_license_key' ) );
				$downloads    = json_decode( wp_remote_retrieve_body( $api_response ), true );
				set_transient( 'totalpoll_store_downloads', $downloads, HOUR_IN_SECONDS * 6 );
			endif;

			return $downloads;
		}

		public function menus() {
			$menu_slug = 'edit.php?post_type=poll';

			// Extensions
			add_submenu_page( $menu_slug, __( 'TotalPoll Options', TP_TD ), __( 'Options', TP_TD ), 'install_themes', 'tp-options', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll Extensions', TP_TD ), __( 'Extensions', TP_TD ), 'install_themes', 'tp-extensions', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll Templates', TP_TD ), __( 'Templates', TP_TD ), 'install_themes', 'tp-templates', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll Store', TP_TD ), __( 'Store', TP_TD ), 'install_themes', 'tp-store', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll Tools', TP_TD ), __( 'Tools', TP_TD ), 'manage_options', 'tp-tools', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll About', TP_TD ), __( 'About', TP_TD ), 'edit_posts', 'tp-about', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll Update', TP_TD ), __( 'Update', TP_TD ), 'manage_options', 'tp-update', array( $this, 'page' ), 10, 3 );
			add_submenu_page( $menu_slug, __( 'TotalPoll Support Center', TP_TD ), __( 'Support / Help', TP_TD ), 'edit_posts', 'tp-support', array( $this, 'page' ), 10, 3 );
		}

		public function assets() {
			// TotalPoll
			wp_enqueue_style( 'tp-admin-core', TP_URL . 'assets/css/admin-core.css', array(), TP_VERSION );
			wp_enqueue_style( 'tp-admin-pages', TP_URL . 'assets/css/admin-pages.css', array( 'tp-admin-core' ), TP_VERSION );

			// @TODO: Refactor this
			if ( $_GET && $_GET['page'] == 'tp-options' ):
				// Datepicker
				wp_enqueue_script( 'jquery-datetimepicker', TP_URL . 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/jquery.datetimepicker.full.js', array( 'jquery' ), ( WP_DEBUG ? time() : TP_VERSION ) );
				wp_enqueue_style( 'jquery-datetimepicker', TP_URL . 'assets/css/jquery.datetimepicker' . ( WP_DEBUG ? '' : '.min' ) . '.css', array(), ( WP_DEBUG ? time() : TP_VERSION ) );

				// Color picker
				if ( wp_script_is( 'wp-color-picker', 'registered' ) ):
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_script( 'wp-color-picker' );
				endif;

				wp_enqueue_script( 'tp-admin-api', TP_URL . 'assets/js/admin-api.js', array( 'jquery-ui-sortable' ), TP_VERSION );
				wp_enqueue_script( 'tp-admin-options', TP_URL . 'assets/js/admin-options.js', array( 'tp-admin-api' ), TP_VERSION );

				wp_localize_script( 'tp-admin-api', 'i18nTotalPoll',
					array(
						'sure'            => __( 'Are you sure?!', TP_TD ),
						'change_template' => __( 'Changing the current template requires a page reload. All changes will be saved now. Are you sure?', TP_TD ),
					)
				);
			endif;

			if ( is_rtl() ):
				wp_enqueue_style( 'tp-admin-rtl', TP_URL . 'assets/css/admin-rtl.css', array(), TP_VERSION );
			endif;
		}

		public function page() {
			$page = $_GET['page'];
			if ( isset( self::$pages[ $page ] ) ):
				include_once TP_PATH . 'includes/admin/' . self::$pages[ $page ];
			endif;
		}

		public function welcome() {
			include_once TP_PATH . 'includes/admin/partials/welcome.php';
		}

		public function php_version() {
			include_once TP_PATH . 'includes/admin/partials/phpversion.php';
		}

		public function pointers( $type = 'global' ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'tp-pointers', TP_URL . 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/pointers.js', array( 'jquery', 'wp-pointer' ), ( WP_DEBUG ? time() : TP_VERSION ), true );

			$pointers = array(
				'type' => $this->pointers_type,
				'i18n' => array(
					'previous' => __( 'Previous', TP_TD ),
					'next'     => __( 'Next', TP_TD ),
					'done'     => __( 'Done', TP_TD ),
				),
			);

			if ( $this->pointers_type === 'global' ):
				$pointers['items'] = array(
					'#menu-posts-poll > a'                                                  =>
						array(
							'title' => __( 'Welcome!', TP_TD ),
							'body'  => __( 'You can access to your polls and TotalPoll features from this menu.', TP_TD ),
						),
					'#menu-posts-poll a[href="post-new.php?post_type=poll"]'                =>
						array(
							'title' => __( 'New poll', TP_TD ),
							'body'  => __( 'You can create your first poll from this page.', TP_TD ),
						),
					'#menu-posts-poll a[href="edit.php?post_type=poll&page=tp-extensions"]' =>
						array(
							'title' => __( 'Extensions', TP_TD ),
							'body'  => __( 'From this page you can manage TotalPoll extensions.', TP_TD ),
						),
					'#menu-posts-poll a[href="edit.php?post_type=poll&page=tp-templates"]'  =>
						array(
							'title' => __( 'Templates', TP_TD ),
							'body'  => __( 'From this page you can manage manage TotalPoll templates.', TP_TD ),
						),
					'#menu-posts-poll a[href="edit.php?post_type=poll&page=tp-store"]'      =>
						array(
							'title' => __( 'Store', TP_TD ),
							'body'  => __( 'Extend TotalPoll functionality and appearance with premium extensions and templates.', TP_TD ),
						),
					'#menu-posts-poll a[href="edit.php?post_type=poll&page=tp-tools"]'      =>
						array(
							'title' => __( 'Tools', TP_TD ),
							'body'  => __( 'Tools for importing, exporting and migrating polls.', TP_TD ),
						),
					'#menu-posts-poll a[href="edit.php?post_type=poll&page=tp-update"]'     =>
						array(
							'title' => __( 'Update', TP_TD ),
							'body'  => __( 'Receive updates by activating TotalPoll via Envato license key.', TP_TD ),
						),
					'#menu-posts-poll a[href="edit.php?post_type=poll&page=tp-support"]'    =>
						array(
							'title' => __( 'Support / Help', TP_TD ),
							'body'  => __( 'Visit this page if you need any help with TotalPoll.', TP_TD ),
						),
				);
			else:
				$pointers['items'] = array(
					'#title'                             =>
						array(
							'title'    => __( 'Title', TP_TD ),
							'body'     => __( "First things first, let's give the poll a title i.e. Favorite colors.", TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'.totalpoll-question-wrapper'        =>
						array(
							'title'    => __( 'Question', TP_TD ),
							'body'     => __( 'And of course a question i.e. What is your favorite colors.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'.totalpoll-containables-types'      =>
						array(
							'title'    => __( 'Choices', TP_TD ),
							'body'     => __( "Now let's introduce poll choices i.e. Red, green and blue.", TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-insert-bulk]'              =>
						array(
							'title'    => __( 'Bulk insertion', TP_TD ),
							'body'     => __( 'Protip: Use bulk insertion when you have already a list of choices.', TP_TD ),
							'position' => array(
								'edge'  => 'right',
								'align' => 'right',
							),
						),
					'[data-tp-tab="limitations"]'        =>
						array(
							'title'    => __( 'Limitations', TP_TD ),
							'body'     => __( 'Set limitations to match your needs.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="results"]'            =>
						array(
							'title'    => __( 'Results', TP_TD ),
							'body'     => __( 'Change results settings to match your needs.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="choices"]'            =>
						array(
							'title'    => __( 'Choices', TP_TD ),
							'body'     => __( 'Change choices settings to match your needs.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="custom-fields"]'      =>
						array(
							'title'    => __( 'Custom fields', TP_TD ),
							'body'     => __( 'You can manage custom fields from this tab.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="design"]'             =>
						array(
							'title'    => __( 'Design', TP_TD ),
							'body'     => __( "And polish poll's appearance from this one.", TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="screens"]'            =>
						array(
							'title'    => __( 'Screens', TP_TD ),
							'body'     => __( 'Set a "thank you" or "welcome" message from this tab.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="browse-statistics"]'  =>
						array(
							'title'    => __( 'Statistics', TP_TD ),
							'body'     => __( 'Browse the statistics from this tab.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="browse-logs"]'        =>
						array(
							'title'    => __( 'Logs', TP_TD ),
							'body'     => __( 'You can also browse the logs from this tab.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'[data-tp-tab="browse-submissions"]' =>
						array(
							'title'    => __( 'Submissions', TP_TD ),
							'body'     => __( 'Lastly, the submissions from this one.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
						),
					'#major-publishing-actions input'    =>
						array(
							'title'    => __( 'Shortcode', TP_TD ),
							'body'     => __( 'Paste this shortcode anywhere to embed the poll.', TP_TD ),
							'position' => array(
								'edge'  => 'top',
								'align' => 'right',
							),
						),
					'#publishing-action'                 =>
						array(
							'title'    => __( 'Publish', TP_TD ),
							'body'     => __( "You are almost done! Now It's time to publish the poll.", TP_TD ),
							'position' => array(
								'edge'  => 'right',
								'align' => 'right',
							),
						),
					'[data-tp-reset-votes]'              =>
						array(
							'title'    => __( 'Reset votes', TP_TD ),
							'body'     => __( 'You can use this button to reset choices votes to 0. You can also set votes manually from "votes" field underneath.', TP_TD ),
							'position' => array(
								'edge'  => 'right',
								'align' => 'right',
							),
						),
					'#menu-posts-poll'                   =>
						array(
							'title'    => __( 'Thank you!', TP_TD ),
							'body'     => sprintf(
								__( 'By now you are familiar with the basics of TotalPoll. Thank you for your time! If you need any help, please don\'t hesitate to visit our <a href="%s">support center</a>.', TP_TD ),
								admin_url( 'edit.php?post_type=poll&page=tp-support' )
							),
							'position' => array(
								'edge'  => 'left',
								'align' => 'left',
							),
						),
				);
			endif;

			wp_localize_script(
				'tp-pointers',
				'totalpollPointers',
				$pointers
			);
		}

		public function extensions() {
			$extensions = TotalPoll::instance( 'admin/extensions' )->load();
			foreach ( $extensions as $extension ):
				TotalPoll::module( 'extension', $extension, null );
			endforeach;
		}

		public function get_system_details() {
			global $wpdb, $wp_version;

			$details = array(

				'PHP'       => array(
					'Version'              => PHP_VERSION,
					'OS'                   => PHP_OS,
					'Extensions'           => implode( ', ', get_loaded_extensions() ),
					'Safe Mode'            => ini_get( 'safe_mode' ) ? 'ON' : 'OFF',
					'Memory Limit'         => size_format( ini_get( 'memory_limit' ) * 1048576 ),
					'Post Max Size'        => size_format( ini_get( 'post_max_size' ) * 1048576 ),
					'Upload max file size' => size_format( ini_get( 'upload_max_filesize' ) * 1048576 ),
					'Time Limit'           => ini_get( 'max_execution_time' ),
					'Max Input Vars'       => ini_get( 'max_input_vars' ),
					'Display Errors'       => ini_get( 'display_errors' ) ? ini_get( 'display_errors' ) : 'OFF',
				),
				'MySQL'     => array(
					'Version' => $wpdb->db_version(),
				),
				'Server'    => array(
					'Software' => $_SERVER['SERVER_SOFTWARE'],
					'Port'     => $_SERVER['SERVER_PORT'],
					'Protocol' => $_SERVER['SERVER_PROTOCOL'],
				),
				'Sessions'  => array(
					'Enabled'          => isset( $_SESSION ) ? 'ON' : 'OFF',
					'Name'             => ini_get( 'session.name' ),
					'Path'             => ini_get( 'session.save_path' ),
					'Use Cookies'      => ini_get( 'session.use_cookies' ) ? 'ON' : 'OFF',
					'Use Only Cookies' => ini_get( 'session.use_only_cookies' ) ? 'ON' : 'OFF',
					'Cookie Path'      => ini_get( 'session.cookie_path' ),
				),
				'Cookies'   => array(
					'Domain' => COOKIE_DOMAIN,
					'Path'   => SITECOOKIEPATH,
				),
				'WordPress' => array(
					'Version'                 => $wp_version,
					'Locale'                  => get_locale(),
					'MU'                      => is_multisite() ? 'ON' : 'OFF',
					'Home'                    => get_option( 'home' ),
					'Memory Limit'            => WP_MEMORY_LIMIT,
					'Max Memory Limit'        => WP_MAX_MEMORY_LIMIT,
					'Short Initialization'    => SHORTINIT ? 'ON' : 'OFF',
					'Debug Mode'              => WP_DEBUG ? 'ON' : 'OFF',
					'Debug Script'            => SCRIPT_DEBUG ? 'ON' : 'OFF',
					'Debug Log'               => WP_DEBUG_LOG ? 'ON' : 'OFF',
					'Cache'                   => WP_CACHE ? 'ON' : 'OFF',
					'Force SSL'               => FORCE_SSL_ADMIN ? 'ON' : 'OFF',
					'Cron'                    => ! defined( 'DISABLE_WP_CRON' ) ? 'ON' : 'OFF',
					'Revisions'               => WP_POST_REVISIONS ? WP_POST_REVISIONS : 'OFF',
					'Compress Stylesheets'    => defined( 'COMPRESS_CSS' ) && COMPRESS_CSS ? 'ON' : 'OFF',
					'Compress Scripts'        => defined( 'COMPRESS_SCRIPTS' ) && COMPRESS_SCRIPTS ? 'ON' : 'OFF',
					'Concatenate Scripts'     => defined( 'CONCATENATE_SCRIPTS ' ) && CONCATENATE_SCRIPTS ? 'ON' : 'OFF',
					'Enforce Gzip'            => defined( 'ENFORCE_GZIP ' ) && ENFORCE_GZIP ? 'ON' : 'OFF',
					'Directory Permissions'   => defined( 'FS_CHMOD_DIR' ) ? FS_CHMOD_DIR : 'OFF',
					'File Permissions'        => defined( 'FS_CHMOD_FILE' ) ? FS_CHMOD_DIR : 'OFF',
					'Filesystem Method'       => defined( 'FS_METHOD' ) ? FS_METHOD : 'OFF',
					'Proxy'                   => defined( 'WP_PROXY_HOST' ) ? 'ON' : 'OFF',
					'Block External Requests' => defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL ? 'ON' : 'OFF',
					'Save Queries'            => defined( 'SAVEQUERIES' ) ? SAVEQUERIES : 'OFF',
				),
				'Plugins'   => array(),
			);

			$plugins        = get_plugins();
			$active_plugins = get_option( 'active_plugins', array() );

			foreach ( $plugins as $plugin_path => $plugin ):
				// If the plugin isn't active, don't show it.
				if ( ! in_array( $plugin_path, $active_plugins ) ):
					continue;
				endif;

				$details['Plugins'][ $plugin['Name'] ] = $plugin['Version'];

			endforeach;

			$info = '';

			foreach ( $details as $section => $section_details ):
				$info .= PHP_EOL . '+' . str_repeat( '—', 60 ) . '+' . PHP_EOL .
				         '| ' . $section . str_repeat( ' ', absint( 59 - strlen( $section ) ) ) . '|' .
				         PHP_EOL . '+' . str_repeat( '—', 60 ) . '+' . PHP_EOL;

				foreach ( $section_details as $label => $detail ):
					$info .= '| ' . $label . ' ' . str_repeat( '—', absint( 30 - strlen( $label ) ) ) .
					         ( strlen( $detail ) > 50 ? str_repeat( '—', 27 ) . ' |' . PHP_EOL : ' ' ) . $detail .
					         ( strlen( $detail ) > 50 ? PHP_EOL : str_repeat( ' ', absint( 27 - strlen( $detail ) ) ) . '|' . PHP_EOL ) .
					         ( strlen( $detail ) > 50 ? ( '| ' . str_repeat( '—', 58 ) . ' |' . PHP_EOL ) : '' );
				endforeach;

				$info .= '+' . str_repeat( '—', 60 ) . '+' . PHP_EOL;

			endforeach;

			return $info;
		}

	}


endif;