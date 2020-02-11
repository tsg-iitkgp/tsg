<?php
/**
 * Editor Class
 *
 * @package TotalPoll/Classes/Admin/Editor
 * @since   3.0.0
 */
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( class_exists( 'TP_Admin_Editor' ) ) {
	return false;
}

class TP_Admin_Editor {

	public $defaults = array();
	public $poll;

	public function __construct() {
		global $current_screen;

		add_filter( 'admin_title', array( $this, 'print_statistics' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'setup' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'edit_form_after_title', array( $this, 'content' ) );
		add_action( 'post_submitbox_start', array( $this, 'actions' ) );
		add_action( 'save_post', array( $this, 'save' ) );

		// For ACF support purpose, we have to disable the datetimepicker implemented by ACF
		add_action( 'acf/settings/enqueue_datetimepicker', '__return_false' );

		$this->defaults = TotalPoll::instance( 'admin/bootstrap' )->get_default_settings();

		if ( $current_screen->action === 'add' ):
			// Load from default settings
			$default_settings = get_option( 'totalpoll_default_settings',
				array(
					'limitations' => array(
						'cookies' => array( 'enabled' => true ),
						'direct'  => array( 'enabled' => true ),
					),
					'results'     => array(
						'format' => array( 'votes' => true, 'percentages' => true ),
					),
					'logs'        => array( 'enabled' => true ),
				)
			);


			$this->defaults = TotalPoll::instance( 'helpers' )->parse_args( $default_settings, $this->defaults );

			// Refresh unique ID
			$this->defaults['limitations']['unique_id'] = current_time( 'timestamp' ) . mt_rand( 100, 999 );
		endif;

		$this->defaults = apply_filters( 'totalpoll/filters/admin/editor/poll/defaults', $this->defaults );

	}

	/**
	 * Print statistics.
	 *
	 * @param $title string Page title.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function print_statistics( $title ) {
		if ( ! empty( $_REQUEST['print'] ) ):
			$this->setup();
			include_once TP_PATH . 'includes/admin/editor/print.php';
			exit;
		endif;

		return $title;
	}

	/**
	 * Enqueue assets.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function assets() {
		// Disable auto save
		wp_dequeue_script( 'autosave' );

		// Datepicker
		wp_enqueue_script( 'jquery-datetimepicker', TP_URL . 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/jquery.datetimepicker.full.js', array( 'jquery' ), ( WP_DEBUG ? time() : TP_VERSION ) );
		wp_enqueue_style( 'jquery-datetimepicker', TP_URL . 'assets/css/jquery.datetimepicker' . ( WP_DEBUG ? '' : '.min' ) . '.css', array(), ( WP_DEBUG ? time() : TP_VERSION ) );

		// Color picker
		if ( wp_script_is( 'wp-color-picker', 'registered' ) ):
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		endif;

		// WP Media
		wp_enqueue_media();

		// TotalPoll
		wp_enqueue_script( 'tp-admin-api', TP_URL . 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/admin-api.js', array( 'media-views' ), ( WP_DEBUG ? time() : TP_VERSION ) );
		wp_enqueue_script( 'tp-admin-editor', TP_URL . 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/admin-editor.js', array( 'tp-admin-api' ), ( WP_DEBUG ? time() : TP_VERSION ) );
		wp_enqueue_style( 'tp-admin-core', TP_URL . 'assets/css/admin-core.css', array(), ( WP_DEBUG ? time() : TP_VERSION ) );
		wp_enqueue_style( 'tp-admin-editor', TP_URL . 'assets/css/admin-editor.css', array( 'tp-admin-core' ), ( WP_DEBUG ? time() : TP_VERSION ) );
		wp_localize_script( 'tp-admin-api', 'i18nTotalPoll',
			array(
				'sure'              => __( 'Are you sure?!', TP_TD ),
				'extract_thumbnail' => __( 'Extract thumbnail from video URL?', TP_TD ),
				'change_template'   => __( 'Changing the current template requires a page reload. All changes will be saved now. Are you sure?', TP_TD ),
				'max_inputs'        => sprintf(
					__( "STOP!\n\nSome of your changes will be lost because your PHP configuration is limited to %d inputs per page and unfortunately you have exceeded that limit.\n\nPlease ask your hosting provider to change the value of 'max_input_vars' in your PHP.INI file to a higher number.\n\nFor more details please visit our help center.", TP_TD ) . "\n",
					ini_get( 'max_input_vars' )
				),
			)
		);
		wp_localize_script( 'tp-admin-editor', 'TotalPollInfo',
			array(
				'max_input_vars' => ini_get( 'max_input_vars' ),
			)
		);
		if ( is_rtl() ):
			wp_enqueue_style( 'tp-admin-rtl', TP_URL . 'assets/css/admin-rtl.css', array(), ( WP_DEBUG ? time() : TP_VERSION ) );
		endif;

		// Chart
		wp_enqueue_script( 'google-chart', 'https://www.gstatic.com/charts/loader.js', array( 'tp-admin-editor' ), ( WP_DEBUG ? time() : TP_VERSION ) );

		/**
		 * Enqueue scripts and styles.
		 *
		 * @since  2.0.0
		 * @action totalpoll/admin/editor/assets
		 *
		 */
		do_action( 'totalpoll/actions/admin/editor/assets' );
	}

	/**
	 * Setup
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function setup() {
		global $post, $current_screen;

		// Data
		$poll_id = $post->ID;

		// WPML
		if ( ! empty( $GLOBALS['sitepress'] ) && $current_screen && $current_screen->action === 'add' && isset( $_REQUEST['trid'] ) ):
			$poll_id = $GLOBALS['sitepress']->get_original_element_id_by_trid( $_REQUEST['trid'] );
			$poll_id = $poll_id === false ? $post->ID : $poll_id;
		endif;
		// Polylang
		if ( $current_screen && $current_screen->action === 'add' && isset( $_REQUEST['from_post'] ) ):
			$poll_id = empty( $_REQUEST['from_post'] ) ? $post->ID : $_REQUEST['from_post'];
		endif;

		$this->poll = TotalPoll::instance( 'poll', array( $poll_id, true ) );
	}

	/**
	 * Editor Content
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function content() {

		$poll    = $this->poll;
		$context = 'poll';
		// View
		wp_nonce_field( plugin_basename( __FILE__ ), 'totalpoll_nonce' );
		include_once TP_PATH . 'includes/admin/editor/header.php';
		include_once TP_PATH . 'includes/admin/editor/basic.php';
		include_once TP_PATH . 'includes/admin/editor/footer.php';
	}

	/**
	 * Extra actions (publish box)
	 *
	 * @param $poll
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function actions( $poll ) {
		include_once TP_PATH . 'includes/admin/editor/actions.php';
	}

	/**
	 * Save poll settings.
	 *
	 * @param int $poll_id Poll ID
	 *
	 * @return bool|void
	 */
	public function save( $poll_id ) {
		global $wpdb;

		// Quick edits
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ):
			return false;
		endif;

		// Check permission and nonce
		if ( ! current_user_can( 'edit_post', $poll_id ) || ! $this->is_valid_nonce() ):
			return false;
		endif;

		// Revisions
		if ( $revision_id = wp_is_post_revision( $poll_id ) ):
			$poll_id = $revision_id;
		endif;

		// Global container
		$totalpoll = $_POST['totalpoll'];

		// Helpers
		$helpers = TotalPoll::instance( 'helpers' );

		do_action( 'totalpoll/actions/admin/editor/save/before', $poll_id, $totalpoll, $this );

		// Handle Downloads & reset
		if ( empty( $totalpoll['download'] ) === false || empty( $totalpoll['reset'] ) === false ):
			$meta_pageable = TotalPoll::instance( 'meta-pageable' );
		endif;

		if ( empty( $totalpoll['download'] ) === false ):

			$formats = array( 'csv', 'html' );

			$download = TotalPoll::instance( 'admin/download' );

			$labels  = array();
			$content = array();

			if ( isset( $totalpoll['download']['logs'] ) === true ):

				$format = in_array( $totalpoll['download']['logs'], $formats ) ? $totalpoll['download']['logs'] : $formats[0];

				$labels = array(
					__( 'Status', TP_TD ),
					__( 'Time', TP_TD ),
					__( 'IP', TP_TD ),
					__( 'Platform', TP_TD ),
					__( 'Browser', TP_TD ),
					__( 'Version', TP_TD ),
					__( 'Choices', TP_TD ),
					__( 'Details', TP_TD ),
					__( 'User agent', TP_TD ),
				);

				$fields = TotalPoll::poll( $poll_id )->fields()->raw_fields();

				foreach ( $fields as $field ):
					$labels[] = esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] );
				endforeach;

				$logs = $meta_pageable->paginate( 'logs', $poll_id, 1, - 1 );

				foreach ( $logs as $log ):
					$ua = $helpers->parse_useragent( $log['useragent'] );

					$item = array(
						$log['status'] == true ? __( 'Accepted', TP_TD ) : __( 'Denied', TP_TD ),
						date( 'Y-m-d H:i e', $log['time'] ),
						$log['ip'],
						$ua['platform'],
						$ua['browser'],
						$ua['version'],
						implode( ', ', (array) $log['choices'] ),
						implode( ', ', (array) $log['details'] ),
						$log['useragent'],
					);

					foreach ( $fields as $field ):
						$item[] = empty( $log['fields'][ $field['name'] ] ) ? __( 'N/A', TP_TD ) : esc_html( $log['fields'][ $field['name'] ] );
					endforeach;

					$content[] = $item;

				endforeach;

			elseif ( isset( $totalpoll['download']['submissions'] ) === true ):

				$format = in_array( $totalpoll['download']['submissions'], $formats ) ? $totalpoll['download']['submissions'] : $formats[0];

				$submissions = $meta_pageable->paginate( 'submissions', $poll_id, 1, - 1 );

				foreach ( $submissions as $submission ):
					$details = array();

					foreach ( $submission as $field => $value ):
						if ( ! in_array( $field, $labels ) ):
							$labels[$field] = $field;
						endif;
					endforeach;

					foreach ( $labels as $field ):
						$details[] = empty( $submission[ $field ] ) ? __( 'N/A', TP_TD ) : $submission[ $field ];
					endforeach;

					$content[] = $details;
				endforeach;

				$date_item_offset = array_search( '__submission_date', $labels );
				if ( $date_item_offset !== false ):
				    if($date_item_offset === '__submission_date'):
			            array_splice( $labels, 0, 1, __( 'Date', TP_TD ) );
			        else:
		                array_splice( $labels, $date_item_offset, 1, __( 'Date', TP_TD ) );
			        endif;
				endif;

			endif;

			$download->driver( $format );
			$download->filename( sanitize_title_with_dashes( $totalpoll['question'] ) . '-' . current_time( 'timestamp' ) );
			$download->labels( $labels );
			$download->content( $content );
			$download->send();

		endif;

		if ( empty( $totalpoll['reset'] ) === false ):
			if ( isset( $totalpoll['reset']['logs'] ) === true ):
				$meta_pageable->reset( 'logs', $poll_id );
				delete_post_meta( $poll_id, 'statistics_last_offset' );
			endif;

			if ( isset( $totalpoll['reset']['submissions'] ) === true ):
				$meta_pageable->reset( 'submissions', $poll_id );
				// Remove unique validated fields
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE %s",
						$poll_id,
						'_tp_unique_%'
					)
				);
			endif;

			if ( isset( $totalpoll['reset']['statistics'] ) === true ):
				delete_post_meta( $poll_id, 'statistics' );
				delete_post_meta( $poll_id, 'statistics_last_offset' );
			endif;
		endif;

		// Handle question
		update_post_meta( $poll_id, 'question', $totalpoll['question'] );

		// Handle choices
		$choices      = isset( $totalpoll['choices'] ) ? (array) $totalpoll['choices'] : array();
		$choices_meta = array();
		// Get count
		$choices_current_index = (int) get_post_meta( $poll_id, 'choices', true );
		// Update count
		$choices_meta['choices'] = count( $choices );
		$choices_total_votes     = 0;

		$translations_ids = array( $poll_id );
		$original_id      = $poll_id;

		// WPML compatibility
		if ( ! empty( $GLOBALS['sitepress'] ) ):
			$translations = $GLOBALS['sitepress']->get_element_translations( $GLOBALS['sitepress']->get_element_trid( $poll_id, 'post_poll' ) );
			foreach ( $translations as $translation ):
				if ( $translation->element_id != $poll_id ):
					$translations_ids[] = $translation->element_id;
				endif;
				if ( $translation->original == 1 ):
					$original_id = $translation->element_id;
				endif;
			endforeach;
		endif;

		// Polylang compatibility
		if ( ! empty( $GLOBALS['polylang'] ) ):
			$translations = pll_get_post_translations( $poll_id );
			foreach ( $translations as $code => $translation ):
				if ( $translation != $poll_id ):
					$translations_ids[] = $translation;
				endif;
				if ( pll_default_language() == $code ):
					$original_id = $translation;
				endif;
			endforeach;
		endif;

		// Compose a meta-data (key => value) array of 'what to update'
		// Note: This method avoids the direct usage of update/get post meta in order to keep votes consistent
		foreach ( $choices as $choice_index => $choice_options ):
			// Prefix for all post metadata
			$choice_prefix = "choice_{$choice_index}_";

			// Check modified votes

			if ( (int) $choice_options['last_votes'] === (int) $choice_options['votes'] ):
				// Check index
				$choice_votes_key = ( (int) $choice_options['index'] === $choice_index ) ? $choice_prefix : "choice_{$choice_options['index']}_";
				$choice_votes_key .= 'votes';

				// Retrieve current value
				$choices_meta[ $choice_prefix . 'votes' ] = (int) get_post_meta( $original_id, $choice_votes_key, true );
			else:
				// Override using the new value
				$choices_meta[ $choice_prefix . 'votes' ] = abs( $choice_options['votes'] );
			endif;

			// Date
			if ( empty( $choice_options['content']['date'] ) ):
				$choice_options['content']['date'] = current_time( 'timestamp' );
			endif;

			// Content
			$choices_meta[ $choice_prefix . 'content' ] = $choice_options['content'];

			// Total votes
			$choices_total_votes += $choices_meta[ $choice_prefix . 'votes' ];

		endforeach;

		foreach ( $translations_ids as $translations_id ):
			update_post_meta( $translations_id, 'votes', $choices_total_votes );
		endforeach;

		// Update post meta
		foreach ( $choices_meta as $key => $value ):
			// Update all translations
			if ( strpos( $key, 'votes' ) !== false ):
				foreach ( $translations_ids as $translations_id ):
					update_post_meta( $translations_id, $key, $value );
				endforeach;

				continue;
			endif;

			// Update current translation content
			update_post_meta( $poll_id, $key, $value );
		endforeach;

		// Delete old choices
		while ( $choices_current_index > $choices_meta['choices'] ):
			$choices_current_index --;
			delete_post_meta( $poll_id, "choice_{$choices_current_index}_type" );
			delete_post_meta( $poll_id, "choice_{$choices_current_index}_votes" );
			delete_post_meta( $poll_id, "choice_{$choices_current_index}_content" );
		endwhile;

		// Handle settings
		$settings = $helpers->parse_args( (array) $totalpoll['settings'], $this->defaults );

		// Limitations
		if ( ! empty( $settings['limitations']['captcha']['enabled'] ) ):
			update_option( '_tp_options_captcha_site_key', $settings['limitations']['captcha']['site_key'] );
			update_option( '_tp_options_captcha_site_secret', $settings['limitations']['captcha']['site_secret'] );
		endif;
		unset( $settings['limitations']['captcha']['site_key'], $settings['limitations']['captcha']['site_secret'] );

		// Absolute values
		if ( ! empty( $settings['limitations']['selection']['minimum'] ) ):
			$settings['limitations']['selection']['minimum'] = absint( $settings['limitations']['selection']['minimum'] );
		endif;

		if ( ! empty( $settings['limitations']['selection']['maximum'] ) ):
			$settings['limitations']['selection']['maximum'] = absint( $settings['limitations']['selection']['maximum'] );

			if ( $settings['limitations']['selection']['maximum'] !== 0 && $settings['limitations']['selection']['maximum'] < $settings['limitations']['selection']['minimum'] ):
				$settings['limitations']['selection']['maximum'] = $settings['limitations']['selection']['minimum'];
			endif;
		endif;

		if ( ! empty( $settings['limitations']['cookies']['timeout'] ) ):
			$settings['limitations']['cookies']['timeout'] = absint( $settings['limitations']['cookies']['timeout'] );
		endif;

		if ( ! empty( $settings['limitations']['ip']['timeout'] ) ):
			$settings['limitations']['ip']['timeout'] = absint( $settings['limitations']['ip']['timeout'] );
		endif;

		if ( ! empty( $settings['limitations']['ip']['votes_quota_per_ip'] ) ):
			$settings['limitations']['ip']['votes_quota_per_ip'] = absint( $settings['limitations']['ip']['votes_quota_per_ip'] );
			$settings['limitations']['ip']['votes_quota_per_ip'] = $settings['limitations']['ip']['votes_quota_per_ip'] < 1 ? 1 : $settings['limitations']['ip']['votes_quota_per_ip'];
		endif;

		if ( ! empty( $settings['limitations']['quota']['votes'] ) ):
			$settings['limitations']['quota']['votes'] = absint( $settings['limitations']['quota']['votes'] );
		endif;

		if ( ! empty( $settings['choices']['pagination']['per_page'] ) ):
			$settings['choices']['pagination']['per_page'] = absint( $settings['choices']['pagination']['per_page'] );
		endif;

		// Date
		foreach ( array( 'start', 'end' ) as $date ):
			if ( ! empty( $settings['limitations']['date'][ $date ] ) ):
				$parsed_start_date = date_parse( $settings['limitations']['date'][ $date ] );
				if ( $parsed_start_date !== false && empty( $parsed_start_date['errors'] ) ):
					$settings['limitations']['date'][ $date ] = mktime( $parsed_start_date['hour'], $parsed_start_date['minute'], $parsed_start_date['second'], $parsed_start_date['month'], $parsed_start_date['day'], $parsed_start_date['year'] );
				else:
					unset( $settings['limitations']['date'][ $date ] );
				endif;
			endif;
		endforeach;

		// Count names occurrences (to avoid name collision)
		$fields_names = array();
		foreach ( $settings['fields'] as $field ):
			$fields_names[] = $field['name'];
		endforeach;

		$fields_names_occurrences = array_count_values( $fields_names );
		// Fields
		foreach ( $settings['fields'] as $index => $field ):
			$settings['fields'][ $index ]['name'] = sanitize_title_with_dashes( $field['name'] );
			if ( empty( $settings['fields'][ $index ]['name'] ) ):
				$settings['fields'][ $index ]['name'] = uniqid( 'untitled_' );
			elseif ( $fields_names_occurrences[ $field['name'] ] > 1 ):
				$settings['fields'][ $index ]['name'] = $settings['fields'][ $index ]['name'] . '_' . $fields_names_occurrences[ $field['name'] ] . '_' . $index;
				$fields_names_occurrences[ $field['name'] ] --;
			endif;
		endforeach;

		// Design
		$template = TotalPoll::module( 'template', $settings['design']['template']['name'] );
		if ( $template == false ):
			$settings['design']['template']['name'] = 'default';
		else:
			$template_option_key = "_tp_options_template_defaults_{$settings['design']['template']['name']}";
			if ( isset( $settings['design']['template']['default'] ) ):
				update_option( $template_option_key, $settings['design']['preset'] );
			elseif ( isset( $settings['design']['template']['reset'] ) ):
				delete_option( $template_option_key );
				$settings['design']['preset'] = array();
			elseif ( $settings['design']['template']['last_used'] !== $settings['design']['template']['name'] ):
				$settings['design']['preset'] = array();
			endif;
		endif;


		// Save settings
		foreach ( $settings as $section => $section_settings ):
			// Preserve orphan settings
			// $last_section_settings = (array) get_post_meta( $poll_id, "settings_$section", true );
			// $section_settings      = wp_parse_args( $last_section_settings, $section_settings );

			update_post_meta( $poll_id, "settings_{$section}", $section_settings );
		endforeach;

		$preset_id = md5( json_encode( $settings['design']['preset'] ) );

		$poll = TotalPoll::poll( $poll_id );
		TotalPoll::module( 'template', $settings['design']['template']['name'], $poll )->used( $choices, $settings, $preset_id );
		update_post_meta( $poll_id, '_preset_id', $preset_id );

		$extensions          = TotalPoll::instance( 'admin/extensions' )->load();
		$required_extensions = array();

		foreach ( $extensions as $extension_slug ):

			$extension = TotalPoll::module( 'extension', $extension_slug );

			if ( $extension !== false && call_user_func_array( array( $extension, 'required' ), array( $poll, $settings ) ) === true ):
				$required_extensions[] = $extension_slug;
			endif;

		endforeach;

		if ( ! empty( $required_extensions ) ):
			update_post_meta( $poll_id, "settings_extensions", $required_extensions );
		endif;

		do_action( 'totalpoll/actions/admin/editor/save/after', $poll_id, $totalpoll, $choices, $settings, $this );

		$helpers->purge_cache();

// --------------------------------------------------------------------
// Change POLL_ID below
// --------------------------------------------------------------------

// Let's insert the choice
// --------------------------------------------------------------------
// Note: the value of meta_value field should be a serialized PHP array
		$choice_content = serialize(['visible' => 1, 'type' => 'text', 'date' => time(), 'label' => 'CHOICE CONTENT']);
		$choice_content_query = "INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES (POLL_ID, 'choice_0_content', {$choice_content})";
// Then the number of votes
		$choice_votes_query = "INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES (POLL_ID, 'choice_0_votes', '0')";
// --------------------------------------------------------------------

// Update choices count
// --------------------------------------------------------------------
		$update_choices_count = 'UPDATE `wp_postmeta` SET `meta_value` = 3000 WHERE `post_id` = POLL_ID';
// --------------------------------------------------------------------
	}

	private function is_valid_nonce( $field_name = 'totalpoll_nonce' ) {
		return isset( $_POST[ $field_name ] ) && wp_verify_nonce( $_POST[ $field_name ], plugin_basename( __FILE__ ) );
	}

}