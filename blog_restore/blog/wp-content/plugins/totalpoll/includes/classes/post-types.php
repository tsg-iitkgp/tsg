<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Post_Types' ) ) :

	/**
	 * Post types Class
	 *
	 * @package TotalPoll/Classes/PostTypes
	 * @since   3.0.0
	 */
	class TP_Post_Types {

		/**
		 * Post types constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'poll' ) );
			add_filter( 'post_updated_messages', array( $this, 'poll_messages' ) );
		}

		/**
		 * Poll post type
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function poll() {
			global $wp_version;

			$labels = array(
				'name'               => __( 'Polls', TP_TD ),
				'singular_name'      => __( 'Poll', TP_TD ),
				'add_new'            => __( 'Add New', TP_TD ),
				'add_new_item'       => __( 'Add New Poll', TP_TD ),
				'edit_item'          => __( 'Edit Poll', TP_TD ),
				'new_item'           => __( 'New Poll', TP_TD ),
				'all_items'          => __( 'All Polls', TP_TD ),
				'view_item'          => __( 'View Poll', TP_TD ),
				'search_items'       => __( 'Search Polls', TP_TD ),
				'not_found'          => __( 'No polls found', TP_TD ),
				'not_found_in_trash' => __( 'No polls found in Trash', TP_TD ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Polls', TP_TD ),
			);

			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => _x( 'poll', 'slug', TP_TD ) ),
				'capability_type'    => 'post',
				'has_archive'        => _x( 'polls', 'slug', TP_TD ),
				'menu_position'      => null,
				'hierarchical'       => false,
				'menu_icon'          => version_compare( $wp_version, '3.8', '>=' ) ? 'dashicons-chart-bar' : TP_URL . 'assets/images/fallback-icon.png',
				'supports'           => array( 'title', 'excerpt', 'thumbnail', 'revisions', 'comments' ),
			);

			/**
			 * Post type registration arguments.
			 *
			 * @param array $args Args.
			 *
			 * @since  3.0.0
			 * @filter totalpoll/filters/post-type/args
			 */
			register_post_type( 'poll', apply_filters( 'totalpoll/filters/post-type/args', $args ) );
		}

		/**
		 * Back-office messages.
		 *
		 * @param $messages Messages
		 *
		 * @return mixed messages
		 */
		function poll_messages( $messages ) {
			global $post, $post_ID;

			$messages['poll'] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( 'Poll updated. <a href="%s">View poll</a>', TP_TD ), esc_url( get_permalink( $post_ID ) ) ),
				2  => __( 'Custom field updated.', TP_TD ),
				3  => __( 'Custom field deleted.', TP_TD ),
				4  => __( 'Poll updated.', TP_TD ),
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Poll restored to revision from %s', TP_TD ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Poll published. <a href="%s">View poll</a>', TP_TD ), esc_url( get_permalink( $post_ID ) ) ),
				7  => __( 'Poll saved.', TP_TD ),
				8  => sprintf( __( 'Poll submitted. <a target="_blank" href="%s">Preview poll</a>', TP_TD ),
					esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
				9  => sprintf( __( 'Poll scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview poll</a>', TP_TD ),
					date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
				10 => sprintf( __( 'Poll draft updated. <a target="_blank" href="%s">Preview poll</a>', TP_TD ),
					esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			);

			return $messages;
		}

	}


endif;