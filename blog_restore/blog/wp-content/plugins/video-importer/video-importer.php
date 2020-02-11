<?php
/*
Plugin Name: Video Importer
Plugin URI: https://refactored.co/plugins/video-importer
Description: Automatically imports videos from YouTube and Vimeo accounts
Version: 1.6.2
Author: Refactored Co.
Author URI: https://refactored.co
License: GPL2
*/

// Define

define( 'REFACTORED_VIDEO_IMPORTER_PATH', dirname(__FILE__) );
define( 'REFACTORED_VIDEO_IMPORTER_VERSION', '1.6.2' );

// Providers
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/php/providers.php' );

// Source class
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/php/class-refactored-video-source.php' );
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/php/class-refactored-video-source-factory.php' );

// Settings
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/php/class-refactored-settings.php' );
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/php/class-refactored-video-importer-settings.php' );

class Refactored_Video_Importer {

	var $providers = array();
	var $settings;

	var $settings_args = array(
		'file'    => __FILE__,
		'version' => REFACTORED_VIDEO_IMPORTER_VERSION,
		'name'    => 'Refactored Video Importer',
		'slug'    => 'refactored_video_importer',
		'options' => array(
			'general' => array(
				'name'        => 'General Settings',
				'description' => 'These settings are general.',
				'fields'      => array(
					'logging' => array(
						'name'        => 'Logging',
						'type'        => 'radio',
						'default'     => 'disabled',
						'options'     => array(
							'disabled' => 'Disabled',
							'enabled'  => 'Enabled'
						),
						'description' => 'Only enable when troubleshooting. May affect performance.'
					)
				)
			)
		)
	);

	/**
	 * Constructs the plugin's main class
	 */
	function __construct() {

		// Create provider array
		$this->providers = apply_filters( 'refactored_video_importer/providers', $this->providers );

		// Filter settings args so providers can have their own settings
		$this->settings_args = apply_filters( 'refactored_video_importer/settings_args', $this->settings_args );

		// Settings
		$this->settings = new Refactored_Video_Importer_Settings( $this->settings_args );

		// Run action when options are updated
		add_action( 'update_option_refactored_video_importer', array( &$this, 'options_updated' ) );

		// Activation and deactivation hooks
		register_activation_hook( __FILE__, array( &$this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivation' ) );

		// Make sure import is scheduled
		add_action( 'wp', array( &$this, 'schedule_import' ) );

		// Initialize video source post type
		add_action( 'init', array( &$this, 'init_video_source_post_type' ) );

		// Add columns to the video source post type
		add_filter( 'manage_edit-rf_video_source_columns', array( &$this, 'video_source_columns' ) );
		add_action( 'manage_rf_video_source_posts_custom_column', array( &$this, 'manage_video_source_columns' ), 10, 2 );

		// Change post status information at top of source list
		add_action( 'views_edit-rf_video_source', array( &$this, 'views_edit' ) );

		// Remove bulk options dropdown
		add_filter( 'bulk_actions-edit-rf_video_source', '__return_empty_array' );

		// Remove months dropdown in source list
		add_filter( 'months_dropdown_results', array( &$this, 'months_dropdown_results' ), 10, 2 );

		// Remove "Quick Edit" action in source row
		add_filter( 'post_row_actions', array( &$this, 'video_source_row_actions' ), 10, 2 );

		// Add action to the import all sources cron job
		add_action ( 'rfvi_import_all_sources', array( &$this, 'import_all_sources' ) );

		// Add action hook for the import all sources cron job
		add_action ( 'refactored_video_importer/import_all_sources', array( &$this, 'perform_import') );

		// Add actions to import videos when a source is published
		add_action( 'save_post', array( &$this, 'import_new_video_source' ), 100, 1 );

		// Initialize meta boxes
		add_action( 'admin_init', array( &$this, 'init_meta_boxes' ) );

		// Initialize meta boxes
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

		// Filter 'Enter title here' for video sources
		add_filter( 'enter_title_here', array( &$this, 'change_default_title' ) );

		// Save the selected provider
		add_action( 'save_post', array( &$this, 'save_provider_selection_meta' ), 1, 2 );

		// Save the source information
		add_action( 'save_post', array( &$this, 'save_source_information_meta' ), 10, 2 );

		// Save the import options meta box
		add_action( 'save_post', array( &$this, 'save_import_options_meta' ), 10, 2 );

		// Add admin menus
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );

		// Add javascript to bulk page in tools
		add_action( 'admin_init', array( &$this, 'bulk_scripts' ), 20 );

		// Add Ajax actions
		$this->add_ajax_actions();

	}

	/**
	 * A friendly wrapper that contains all the Ajax actions
	 */
	function add_ajax_actions() {

		// Loads source details meta box
		add_action( 'wp_ajax_rf_video_source_details', array( &$this, 'video_source_details_callback' ) );

		// Import a source now
		add_action( 'wp_ajax_rfvi_import_source', array( &$this, 'import_source_ajax_callback' ) );

		// Delete all videos from a source
		add_action( 'wp_ajax_rfvi_delete_videos_from_source', array( &$this, 'delete_videos_from_source_callback' ) );

		// Get a page of videos from source
		add_action( 'wp_ajax_rfvi_get_source_page', array( &$this, 'get_source_page_callback' ) );

		// Import video from bulk page
		add_action( 'wp_ajax_rfvi_import_video_from_bulk', array( &$this, 'import_video_from_bulk_tool_callback' ) );

	}

	/**
	 * Records a message to the log when enabled
	 * @param  string $message A message of the event
	 * @return void
	 */
	function log( $message ) {
		if ( $this->settings->options['general']['logging'] === 'enabled' ) {
			$log = get_option( 'rfvi_log' );
			$time_and_message = '[' . date( 'Y-m-d H:i:s O' ) . '] ' . $message;
			if ( $log ) {
				$new_log = $log . PHP_EOL . $time_and_message;
			} else {
				$new_log = $time_and_message;
			}
			update_option( 'rfvi_log', $new_log );
		}
	}

	/**
	 * Adds the admin menu items
	 */
	function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=rf_video_source',
			__( 'Bulk Video Import', 'refactored-video-importer' ),
			__( 'Bulk Import', 'refactored-video-importer' ),
			'import',
			'rfvi-bulk',
			array( &$this, 'bulk_action_page' )
		);
	}

	/**
	 * Adds javascript and CSS to the bulk import page
	 * @return void
	 */
	function bulk_scripts() {
		wp_enqueue_script( 'rfvi-bulk-js', plugins_url( '/js/bulk.js' , __FILE__ ), array( 'jquery' ), REFACTORED_VIDEO_IMPORTER_VERSION );
		wp_enqueue_style( 'rfvi-bulk-css', plugins_url('/css/bulk.css', __FILE__), false, REFACTORED_VIDEO_IMPORTER_VERSION );
	}

	/**
	 * Outputs the bulk video import page
	 */
	function bulk_action_page() {

		if ( ! current_user_can( 'import' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		?>
		<div class="wrap">

			<div id="icon-tools" class="icon32"></div><h2>Bulk Video Import</h2>

			<p>Use this tool to import all videos from a source.</p>

			<p>When a source is created, only the most recent videos are imported. The automatic checks will continue to only find new videos, so this tool can be used to import all of the old videos from a source.</p>

			<form id="rfvi-bulk-process">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">Source</th>
							<td>
								<select name="source-id" id="source-id">
								<?php
								$args = array( 'numberposts' => -1, 'post_type' => 'rf_video_source' );
								$sources = get_posts( $args );
								foreach( $sources as $source ) : ?>
									<option value="<?php echo $source->ID; ?>"><?php echo $source->post_title ; ?></option>
								<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"></th>
							<td>
								<input type="submit" value="Import Videos" class="button button-primary">
							</td>
						</tr>
					</tbody>
				</table>
			</form>

			<div id="rfvi-bulk-results">
				<div class="progress-bar-container">
					<span class="percentage">0%</span>
					<div class="progress-bar">&nbsp;</div>
				</div>
				<div class="stats"></div>
				<ul class="log"></ul>
			</div>

		</div>
		<?php

	}

	/**
	 * AJAX callback that gets a page of videos from the source
	 */
	function get_source_page_callback() {
		// Security checks
		if ( !current_user_can( 'import' ) ) die();
		// if ( !wp_verify_nonce( $_POST['nonce'], 'rfvi_bulk' ) ) die();

		// Cleared checks

		// Get the source
		$source = $this->get_source( $_POST['source_id'] );
		// Get video page from source
		$video_page = $source->get_page( $_POST['page'] );
		// Echo JSON
		echo json_encode( $video_page );
		die();
	}

	/**
	 * AJAX callback that imports a single video while using the bulk import tool
	 */
	function import_video_from_bulk_tool_callback() {
		// Security checks
		if ( !current_user_can( 'import' ) ) die();
		// if ( !wp_verify_nonce( $_POST['nonce'], 'rfvi_bulk' ) ) die();

		// Cleared checks
		$video_array = $_POST['video_array'];
		$source = $this->get_source( $_POST['source_id'] );
		// The video already exists, don't import it
		if ( in_array( $video_array['id'], $this->get_known_video_ids( $_POST['source_id'] ) ) ) {
			echo 'exists';
			die();
		}

		$result = $source->import_video( $video_array );
		if ( $result > 0 ) echo 'new';
		die();
	}

	/**
	 * Runs any actions needed when options are updated
	 * @return void
	 */
	function options_updated() {
		// Deletes the log if logging is disabled
		if ( $this->settings->options['general']['logging'] != 'enabled' ) {
			delete_option( 'rfvi_log' );
		}
	}

	/**
	 * Do activation routine
	 */
	function activation() {
		// $this->schedule_import();
	}

	/**
	 * Do deactivation routine
	 */
	function deactivation() {
		$this->unschedule_import();
	}

	/**
	 * Create a schedule event to import videos
	 */
	function schedule_import() {
		if ( !wp_next_scheduled( 'rfvi_import_all_sources' ) ) {
			wp_schedule_event( strtotime( '+1 hour', time() ), 'hourly', 'rfvi_import_all_sources' );
		}
	}

	/**
	 * Clear the scheduled event to import videos
	 */
	function unschedule_import() {
		wp_clear_scheduled_hook( 'rfvi_import_all_sources' );
	}

	/**
	 * Initializes the custom post type that contains video sources
	 */
	function init_video_source_post_type() {
		$labels = array(
			'name'               => 'Video Sources',
			'singular_name'      => 'Video Source',
			'add_new'            => 'Add New Source',
			'add_new_item'       => 'Add New Source',
			'edit_item'          => 'Edit Source',
			'new_item'           => 'New Source',
			'all_items'          => 'All Sources',
			'view_item'          => 'View Source',
			'search_items'       => 'Search Sources',
			'not_found'          => 'No video sources found',
			'not_found_in_trash' => 'No video sources found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Video Importer'
		);
		$capabilities = array(
			'edit_post'              => 'import',
			'read_post'              => 'import',
			'delete_post'            => 'import',
			'edit_posts'             => 'import',
			'edit_others_posts'      => 'import',
			'publish_posts'          => 'import',
			'read_private_posts'     => 'import',
			'delete_posts'           => 'import',
			'delete_private_posts'   => 'import',
			'delete_published_posts' => 'import',
			'delete_others_posts'    => 'import',
			'edit_private_posts'     => 'import',
			'edit_published_posts'   => 'import'
        );
		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'capabilities'       => $capabilities,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 100,
			'supports'           => array( 'title' )
		);
		register_post_type( 'rf_video_source', $args );
	}

	/**
	 * Add columns to video source list
	 * @param  array $columns Key/value pairs for list columns
	 * @return array          Our modified key/value pairs for list columns
	 */
	function video_source_columns( $columns ) {

		$columns = array(
			'title'  => __( 'Source Name' ),
			'videos' => __( 'Videos' ),
			'last_checked' => __( 'Last Checked' ),
			'recent' => __( 'Most Recent' )
		);

		return $columns;
	}

	function format_interval( $timestamp, $granularity = 2 ) {
		$timestamp = time() - $timestamp;
		$units = array( '1 year|@count years' => 31536000, '1 week|@count weeks' => 604800, '1 day|@count days' => 86400, '1 hour|@count hours' => 3600, '1 min|@count min' => 60, '1 sec|@count sec' => 1 );
		$output = '';
		foreach ( $units as $key => $value ) {
			$key = explode( '|', $key );
			if ( $timestamp >= $value ) {
				$floor = floor( $timestamp / $value );
				$output .= ( $output ? ' ' : '' ) . ( $floor == 1 ? $key[0] : str_replace( '@count', $floor, $key[1] ) );
				$timestamp %= $value;
				$granularity--;
			}

			if ( $granularity == 0 ) {
				break;
			}
		}

		return $output ? $output : '0 sec';
	}

	function manage_video_source_columns( $column, $post_id ) {
		global $post;

		switch( $column ) {

			case 'videos' :

				echo '<strong class="video-count">';
				echo Refactored_Video_Source::count_imported_videos( $post_id );
				echo '</strong>';

				echo '<div class="row-actions">';
				echo '<span class="trash"><a href="#" class="rfvi-delete-all" data-source-id="' . $post_id . '" data-nonce="' . wp_create_nonce( 'delete_videos_from_source' ) . '">Trash All</a></span>';
				echo '</div>';

				break;

			case 'last_checked' :

				if ( $last_imported = get_post_meta( $post_id, 'rfvi_last_checked', true ) ) {
					$last_imported_date = '<strong>' . date_i18n( __( 'Y-m-d H:i:s' ), intval( $last_imported ) ) . '</strong><br>';
					$last_imported_date .= '<small>(' . $this->format_interval( intval( $last_imported ) ) . ' ago)</small>';
				} else {
					$last_imported_date = __( 'N/A', 'refactored-video-importer' );
				}
				echo $last_imported_date;

				echo '<div class="row-actions">';
				echo '<span class="import_now"><a href="#" class="rfvi-import-now" data-source-id="' . $post_id . '" data-nonce="' . wp_create_nonce( 'import_source' ) . '">Import Now</a><span class="rfvi-waiting-indicator">Working...</span></span>';
				echo '</div>';

				break;

			case 'recent' :

				$latest_id = Refactored_Video_Source::get_latest_video_id( $post_id );
				if ( $latest_id != 0 ) {
					echo '<strong><a href="' . get_edit_post_link( $latest_id ) . '">';
					echo get_the_title( $latest_id );
					echo '</a></strong>';

					echo '<div class="row-actions">';
					echo '<span class="edit"><a href="' . get_edit_post_link( $latest_id ) . '">Edit</a> | </span>';
					echo '<span class="view"><a href="' . get_permalink( $latest_id ) . '">View</a></span>';
					echo '</div>';
				} else {
					echo 'No videos imported yet';
				}

				break;

			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}

	/**
	 * Modifies the links above the source list table
	 * @param  array $views An array of key/values to display
	 * @return array        A modified array of key/values to display
	 */
	function views_edit( $views ) {
		global $locked_post_status, $avail_post_stati;

		$screen = get_current_screen();

		$post_type = $screen->post_type;

		// Check we're in the right post type
		if ( 'rf_video_source' != $post_type ) return $views;

		if ( !empty($locked_post_status) )
			return array();

		$status_links = array();
		$num_posts = wp_count_posts( $post_type, 'readable' );
		$class = '';
		$allposts = '';

		$total_posts = array_sum( (array) $num_posts );

		// Subtract post types that are not included in the admin all list.
		foreach ( get_post_stati( array('show_in_admin_all_list' => false) ) as $state )
			$total_posts -= $num_posts->$state;

		$class = empty( $class ) && empty( $_REQUEST['post_status'] ) && empty( $_REQUEST['show_sticky'] ) ? ' class="current"' : '';
		$status_links['all'] = "<a href='edit.php?post_type=$post_type{$allposts}'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_posts, 'posts' ), number_format_i18n( $total_posts ) ) . '</a>';

		foreach ( get_post_stati(array('show_in_admin_status_list' => true), 'objects') as $status ) {
			$class = '';

			$status_name = $status->name;

			if ( !in_array( $status_name, $avail_post_stati ) )
				continue;

			if ( empty( $num_posts->$status_name ) )
				continue;

			if ( isset($_REQUEST['post_status']) && $status_name == $_REQUEST['post_status'] )
				$class = ' class="current"';

			if ( $status_name == 'publish' ) {
				$label_count = _n_noop( 'Enabled <span class="count">(%s)</span>', 'Enabled <span class="count">(%s)</span>', 'refactored-video-importer' );
			} elseif ( $status_name == 'draft' ) {
				$label_count = _n_noop( 'Disabled <span class="count">(%s)</span>', 'Disabled <span class="count">(%s)</span>', 'refactored-video-importer' );
			} else {
				$label_count = $status->label_count;
			}

			$status_links[$status_name] = "<a href='edit.php?post_status=$status_name&amp;post_type=$post_type'$class>" . sprintf( translate_nooped_plural( $label_count, $num_posts->$status_name ), number_format_i18n( $num_posts->$status_name ) ) . '</a>';
		}

		return $status_links;
	}

	function months_dropdown_results( $months, $post_type ) {
		if ( $post_type == 'rf_video_source' ) {
			$months = array();
		}
		return $months;
	}

	/**
	 * Remove "Quick Edit" action from video sources
	 * @param  array   $actions An array of actions
	 * @param  WP_Post $post    The current WordPress post
	 * @return array            Our modified array of actions
	 */
	function video_source_row_actions( $actions, $post ) {
		if ( $post->post_type == 'rf_video_source' ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Gets the IDs of all posts imported by a video source
	 * @param  int   $source_id The source's post ID
	 * @return array            An array of post IDs
	 */
	function get_imported_video_ids( $source_id ) {
		$args = array(
			'post_type'      => 'any',
			'posts_per_page' => '-1',
			'meta_key'       => 'rfvi_source_id',
			'meta_value'     => $source_id,
			'fields'         => 'ids'
		);
		$query = new WP_Query( $args );
		return $query->posts;
	}

	/**
	 * Gets an array of known video IDs (not post IDs)
	 * @param  int   $source_id The source ID
	 * @return array            An array of YouTube or Vimeo IDs
	 */
	public static function get_known_video_ids( $source_id ) {
		// TODO: $this->log( 'Getting known video IDs' );
		global $wpdb;
		$known_ids = $wpdb->get_col(
			"
				SELECT meta_value
				FROM $wpdb->postmeta
				WHERE meta_key='rfvi_video_id'
			"
		);
		// TODO: $this->log( 'Done getting ' . count( $known_ids ) . ' known video IDs' );
		return $known_ids;
	}

	/**
	 * Initializes meta boxes
	 */
	function init_meta_boxes() {
		add_meta_box(
			'provider-selection',
			__( 'Select Provider', 'refactored-video-importer' ),
			array( &$this, 'provider_selector_meta_box' ),
			'rf_video_source',
			'normal'
		);
		add_meta_box(
			'source-details',
			__( 'Source Information', 'refactored-video-importer' ),
			array( &$this, 'source_information_meta_box' ),
			'rf_video_source',
			'normal'
		);
		add_meta_box(
			'import-options',
			__( 'Import Options', 'refactored-video-importer' ),
			array( &$this, 'import_options_meta_box' ),
			'rf_video_source',
			'normal'
		);
		remove_meta_box( 'submitdiv', 'rf_video_source', 'side' );
		add_meta_box(
			'submitdiv',
			__( 'Source Status' ),
			array( &$this, 'submit_meta_box' ),
			'rf_video_source',
			'side'
		);
	}

	/**
	 * Display post submit form fields.
	 * @param object $post
	 */
	function submit_meta_box($post, $args = array() ) {
		global $action;

		$post_type = $post->post_type;
		$post_type_object = get_post_type_object($post_type);
		$can_publish = current_user_can($post_type_object->cap->publish_posts);
		?>
		<div class="submitbox" id="submitpost">

		<div id="minor-publishing">

		<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
		<div style="display:none;">
		<?php submit_button( __( 'Save' ), 'button', 'save' ); ?>
		</div>

		<div id="misc-publishing-actions">

		<div class="misc-pub-section misc-pub-post-status"><label for="post_status"><?php _e('Status:') ?></label>
		<span id="post-status-display">
		<?php
		switch ( $post->post_status ) {
			case 'private':
				_e('Privately Published');
				break;
			case 'publish':
				_e('Enabled');
				break;
			case 'future':
				_e('Scheduled');
				break;
			case 'pending':
				_e('Pending Review');
				break;
			case 'draft':
			case 'auto-draft':
				_e('Disabled');
				break;
		}
		?>
		</span>
		<?php if ( 'publish' == $post->post_status || 'private' == $post->post_status || $can_publish ) { ?>
		<a href="#post_status" <?php if ( 'private' == $post->post_status ) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit status' ); ?></span></a>

		<div id="post-status-select" class="hide-if-js">
		<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr( ('auto-draft' == $post->post_status ) ? 'draft' : $post->post_status); ?>" />
		<select name='post_status' id='post_status'>
		<option<?php selected( $post->post_status, 'publish' ); ?> value='publish'><?php _e('Enabled') ?></option>
		<?php if ( 'private' == $post->post_status ) : ?>
		<option<?php selected( $post->post_status, 'private' ); ?> value='publish'><?php _e('Privately Published') ?></option>
		<?php elseif ( 'future' == $post->post_status ) : ?>
		<option<?php selected( $post->post_status, 'future' ); ?> value='future'><?php _e('Scheduled') ?></option>
		<?php endif; ?>
		<?php if ( 'auto-draft' == $post->post_status ) : ?>
		<option<?php selected( $post->post_status, 'auto-draft' ); ?> value='draft'><?php _e('Disabled') ?></option>
		<?php else : ?>
		<option<?php selected( $post->post_status, 'draft' ); ?> value='draft'><?php _e('Disabled') ?></option>
		<?php endif; ?>
		</select>
		 <a href="#post_status" class="save-post-status hide-if-no-js button"><?php _e('OK'); ?></a>
		 <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel"><?php _e('Cancel'); ?></a>
		</div>

		<?php } ?>
		</div><!-- .misc-pub-section -->

		<div class="misc-pub-section curtime misc-pub-curtime">
			<?php
			if ( $last_imported = get_post_meta( $post->ID, 'rfvi_last_checked', true ) ) {
				$last_imported_date = date_i18n( __( 'M j, Y @ G:i' ), strtotime( $last_imported ) );
			} else {
				$last_imported_date = __( 'N/A', 'refactored-video-importer' );
			}
			?>
			<span id="timestamp"><?php _e( 'Last Imported:', 'refactored-video-importer' ); ?> <b><?php echo $last_imported_date; ?></b></span>
		</div><?php // /misc-pub-section ?>

		<?php
		/**
		 * Fires after the post time/date setting in the Publish meta box.
		 *
		 * @since 2.9.0
		 */
		do_action( 'post_submitbox_misc_actions' );
		?>
		</div>
		<div class="clear"></div>
		</div>

		<div id="major-publishing-actions">
		<?php
		/**
		 * Fires at the beginning of the publishing actions section of the Publish meta box.
		 *
		 * @since 2.7.0
		 */
		do_action( 'post_submitbox_start' );
		?>
		<div id="delete-action">
		<?php
		if ( current_user_can( "delete_post", $post->ID ) ) {
			if ( !EMPTY_TRASH_DAYS )
				$delete_text = __('Delete Permanently');
			else
				$delete_text = __('Move to Trash');
			?>
		<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
		} ?>
		</div>

		<div id="publishing-action">
		<span class="spinner"></span>
		<?php
		if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
			?>
				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish') ?>" />
				<?php submit_button( __( 'Save' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
			<?php
		} else { ?>
				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update') ?>" />
				<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Save') ?>" />
		<?php
		} ?>
		</div>
		<div class="clear"></div>
		</div>
		</div>

		<?php
	}

	/**
	 * Renders provider selection meta box
	 * @param WP_Post $post The post object for the current item
	 */
	function provider_selector_meta_box( $post ) {
		$current_provider = get_post_meta( $post->ID, 'rfvi_provider', true );
		echo '<input type="hidden" name="provider_selection_nonce" value="' . wp_create_nonce( 'provider_selection_meta_box' ) . '" />';
		echo '<input type="hidden" id="rf_video_importer_provider" name="rfvi_provider" value="' . $current_provider . '" />';
		echo '<table id="rf-video-provider-selector"><tr>';
		foreach ( $this->providers as $key => $provider ) {
			$selected = ( $current_provider == $key ? 'class="selected" ' : '' );
			echo '<td><a href="#" data-provider-slug="' . $key . '" ' . $selected . '>' . $provider->name . '</a></td>';
		}
		echo '</tr></table>';
	}

	/**
	 * Renders meta box for source details
	 * @param WP_Post $post The post object for the current item
	 */
	function source_information_meta_box( $post ) {
		echo '<input type="hidden" name="source_information_nonce" value="' . wp_create_nonce( 'source_information_meta_box' ) . '" />';
		echo '<div class="rf-loading"><img src="' . admin_url( 'images/wpspin_light.gif' ) . '"></div>';
		echo '<div id="details-box">';
		if ( ( $provider = get_post_meta( $post->ID, 'rfvi_provider', true ) ) != '' ) {
			$this->providers[$provider]->source_options_meta_box( $post->ID );
		} else {
			echo '<p>Select one of the providers above.</p>';
		}
		echo '</div>';
	}

	/**
	 * An AJAX callback that outputs the source options for a specific provider
	 */
	function video_source_details_callback() {
		// Security checks
		if ( !current_user_can( 'import' ) ) die();
		if ( !wp_verify_nonce( $_POST['nonce'], 'source_information_meta_box' ) ) die();
		// Cleared checks
		$this->providers[$_POST['provider']]->source_options_meta_box( $_POST['id'] );
		die();
	}

	/**
	 * An AJAX callback for importing a source now
	 */
	function import_source_ajax_callback() {
		// Security checks
		if ( !current_user_can( 'import' ) ) die();
		if ( !wp_verify_nonce( $_POST['nonce'], 'import_source' ) ) die();
		// Cleared checks
		$source = $this->get_source( $_POST['source_id'] );
		$new_videos = $source->import_new_videos();
		$response = array(
			'source_id'    => $source->id,
			'new_videos'   => count( $new_videos ),
			'total_videos' => Refactored_Video_Source::count_imported_videos( $source->id ),
			'last_checked' => date_i18n( __( 'Y-m-d H:i:s' ), intval( get_post_meta( $source->id, 'rfvi_last_checked', true ) ) )
		);
		echo json_encode( $response );
		die();
	}

	// TODO: Create delete_all_videos() method on Refactored_Video_Source
	/**
	 * An AJAX callback to delete all posts created by a video source
	 */
	function delete_videos_from_source_callback() {
		// Security checks
		if ( !current_user_can( 'import' ) ) die();
		if ( !wp_verify_nonce( $_POST['nonce'], 'delete_videos_from_source' ) ) die();
		// Cleared checks
		$count = 0;
		foreach ( Refactored_Video_Source::get_imported_video_ids( $_POST['source_id'] ) as $id ) {
			if ( wp_delete_post( $id ) ) $count++;
		}
		echo $count;
		die();
	}

	/**
	 * Renders meta box for import_options
	 * @param WP_Post $post The post object for the current item
	 */
	function import_options_meta_box( $post ) {
		$source = $this->get_source( $post->ID );
		$import_options = $source->import_options;

		echo 'These options will be applied to videos being imported.';
		echo '<input type="hidden" name="import_options_nonce" value="' . wp_create_nonce( 'import_options_meta_box' ) . '" />';
		// Post type
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		unset( $post_types['attachment'] );
		echo '<label for="import_post_type" class="header">Post Type</label>';
		echo '<select id="import_post_type" name="rfvi_import_options[post_type]">';
		foreach ( $post_types as $post_type ) {
			$selected = ( $import_options['post_type'] == $post_type->name ? 'selected="selected" ' : '' );
			echo '<option value="' . $post_type->name . '" ' . $selected . '>' . $post_type->labels->singular_name . '</option>';
		}
		echo '</select>';
		// Status
		echo '<label for="import_post_status" class="header">Status</label>';
		echo '<select id="import_post_status" name="rfvi_import_options[post_status]">';
		$post_status_options = array(
			'publish' => 'Published',
			'draft'   => 'Draft',
			'pending' => 'Pending',
			'private' => 'Private'
		);
		foreach ( $post_status_options as $key => $value ) {
			$selected = ( $import_options['post_status'] == $key ? 'selected="selected" ' : '' );
			echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
		}
		echo '</select>';
		// Date
		echo '<label for="import_date" class="header">Date</label>';
		echo '<select id="import_date" name="rfvi_import_options[import_date]">';
		$import_date_options = array(
			'published' => 'Added to Source',
			'current'   => 'Imported to WordPress'
		);
		foreach ( $import_date_options as $key => $value ) {
			$selected = ( $import_options['import_date'] == $key ? 'selected="selected" ' : '' );
			echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
		}
		echo '</select>';
		// Categories
		$categories = get_categories( array( 'hide_empty' => 0 ) );
		echo '<label class="header">Categories</label>';
		echo '<div class="checkbox-list">';
		foreach ( $categories as $category ) {
			$checked = ( in_array( $category->term_id, $import_options['categories'] ) ? 'checked="checked" ' : '' );
			echo '<label><input type="checkbox" name="rfvi_import_options[categories][]" value="' . $category->term_id . '" ' . $checked . '> ' . $category->name . '</label>';
		}
		echo '</div>';
		// Tags
		echo '<label class="header">Automatic Tags</label>';
		$checked = ( $import_options['auto_tags'] ? 'checked="checked" ' : '' );
		echo '<label for="import_auto_tags"><input type="checkbox" id="import_auto_tags" name="rfvi_import_options[auto_tags]" value="1" ' . $checked . '> Import tags from source when available</label>';
		echo '<label class="header">Additional Tags (comma separated)</label>';
		echo '<input type="text" name="rfvi_import_options[additional_tags]" value="' . $import_options['additional_tags'] . '" />';
		// // Taxonomies
		// $taxonomies = get_taxonomies( array( '_builtin' => false ), 'objects' );
		// foreach ( $taxonomies as $taxonomy ) {
		// 	$categories = get_categories( array( 'taxonomy' => $taxonomy->name ) );
		// 	echo '<label class="header">' . $taxonomy->labels->name . '</label>';
		// 	echo '<div class="checkbox-list">';
		// 	foreach ( $categories as $category ) {
		// 		$checked = ( in_array( $category->term_id, $import_options['categories'] ) ? 'checked="checked" ' : '' );
		// 		echo '<label><input type="checkbox" name="rfvi_import_options[categories][]" value="' . $category->term_id . '" ' . $checked . '> ' . $category->name . '</label>';
		// 	}
		// 	echo '</div>';
		// }
		// Author
		echo '<label class="header">Author</label>';
		wp_dropdown_users(
			array(
				'name' => 'rfvi_import_options[author]',
				'selected' => $import_options['author']
			)
		);
	}

	/**
	 * Save provider selection meta box data when a user is authenticated
	 * @param  int     $post_id The ID of the source being saved
	 * @param  WP_Post $post    The post object
	 */
	function save_provider_selection_meta( $post_id, $post ) {
		// Verify the nonce
		if ( !isset( $_POST['provider_selection_nonce'] ) || !wp_verify_nonce( $_POST['provider_selection_nonce'], 'provider_selection_meta_box' ) ) return $post->ID;
		// Verify user has proper permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) return $post->ID;
		// OK, we're authenticated: save the data
		update_post_meta( $post->ID, 'rfvi_provider', $_POST['rfvi_provider'] );
	}

	/**
	 * Save source information meta box data when a user is authenticated
	 * @param  int     $post_id The ID of the source being saved
	 * @param  WP_Post $post    The post object
	 */
	function save_source_information_meta( $post_id, $post ) {
		// Verify the nonce
		if ( !isset( $_POST['source_information_nonce'] ) || !wp_verify_nonce( $_POST['source_information_nonce'], 'source_information_meta_box' ) ) return $post->ID;
		// Verify user has proper permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) return $post->ID;
		// OK, we're authenticated: save the data
		update_post_meta( $post->ID, 'rfvi_source_information', $_POST['rfvi_source_information'] );
	}

	/**
	 * Save import options meta box data when a user is authenticated
	 * @param  int     $post_id The ID of the source being saved
	 * @param  WP_Post $post    The post object
	 */
	function save_import_options_meta( $post_id, $post ) {
		// Verify it is actually a video source
		if ( $post->post_type != 'rf_video_source' ) return $post->ID;
		// Verify the nonce
		if ( !isset( $_POST['import_options_nonce'] ) || !wp_verify_nonce( $_POST['import_options_nonce'], 'import_options_meta_box' ) ) return $post->ID;
		// Verify user has proper permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) return $post->ID;
		// OK, we're authenticated: save the data
		$import_options = $_POST['rfvi_import_options'];
		if ( !isset( $import_options['categories'] ) ) $import_options['categories'] = array();
		if ( !isset( $import_options['auto_tags'] ) ) $import_options['auto_tags'] = false;
		update_post_meta( $post->ID, 'rfvi_import_options', $import_options );
		// Save the version used to create this source
		update_post_meta( $post_id, 'rfvi_source_version', REFACTORED_VIDEO_IMPORTER_VERSION );
	}

	function admin_scripts( $hook ) {
		// if( 'edit.php' != $hook )
		// 	return;
		// CSS
		wp_enqueue_style( 'rf_video_importer_editor_css', plugins_url( 'css/source-editor.css', __FILE__ ), false, REFACTORED_VIDEO_IMPORTER_VERSION );
		// JS
		wp_enqueue_script( 'rf_video_importer_editor_js', plugins_url( 'js/source-editor.js', __FILE__ ), false, REFACTORED_VIDEO_IMPORTER_VERSION );
	}

	/**
	 * Import all sources
	 * This function is triggered by a cron job or can be run manually
	 * It doesn't actually perform the import, just triggers actions that can be hooked into
	 */
	function import_all_sources() {
		do_action( 'refactored_video_importer/pre_import_all_sources' );
		$this->log( 'Importing all sources...' );
		do_action( 'refactored_video_importer/import_all_sources' );
		$this->log( 'Finished importing all sources.' );
		do_action( 'refactored_video_importer/post_import_all_sources' );
	}

	/**
	 * Imports most recent videos from all providers
	 * Gets added to the 'refactored_video_importer/import_all_sources' action
	 */
	function perform_import() {
		// Let's do this
		$sources = $this->get_sources();
		foreach ( $sources as $source ) {
			$source->import_new_videos();
		}
	}

	/**
	 * Gets a source object
	 * @param  int $source_id A post ID of a source
	 * @return Refactored_Video_Source
	 */
	function get_source( $source_id ) {
		return Refactored_Video_Source_Factory::make( $source_id );
	}

	/**
	 * Gets an array of the
	 * @return array All the source objects
	 */
	function get_sources() {
		$ids = $this->get_source_ids();
		$sources = array();
		foreach ( $ids as $id ) {
			$sources[] = $this->get_source( $id );
		}
		return $sources;
	}

	/**
	 * Gets an array of source IDs with the least recently checked first
	 * @return array Array of source IDs
	 */
	function get_source_ids() {
		$scanned = get_posts(array(
			'posts_per_page' => -1,
			'post_type'      => 'rf_video_source',
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'rfvi_last_checked',
			'order'          => 'ASC',
			'fields'         => 'ids'
		));
		$unscanned = get_posts(array(
			'posts_per_page' => -1,
			'post_type'      => 'rf_video_source',
			'post__not_in'   => $scanned,
			'fields'         => 'ids'
		));
		$all_sources = array_merge( $unscanned, $scanned );
		$this->log( 'Found ' . count( $all_sources ) . ' sources' );
		return $all_sources;
	}

	/**
	 * Imports videos when a new source is published
	 * @param  int $source_id The new source ID
	 */
	function import_new_video_source( $source_id ) {
		if ( get_post_type( $source_id ) == 'rf_video_source' &&
			 get_post_status( $source_id ) == 'publish' ) {
			$source = $this->get_source( $source_id );
			$source->import_new_videos();
		}
	}

	/**
	 * Changes 'Enter title here' to something better for video sources
	 * @param  string $title Unfiltered string
	 * @return string        A modified title string for video sources
	 */
	function change_default_title( $title ){
		$screen = get_current_screen();
		if  ( 'rf_video_source' == $screen->post_type ) {
			$title = 'Enter a name for this source';
		}
		return $title;
	}

}

$refactored_video_importer = new Refactored_Video_Importer();