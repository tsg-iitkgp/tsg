<?php

/*  Copyright 2015 Sutherland Boswell  (email : sutherland.boswell@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Refactored_Video_Source {

	var $id;
	var $provider;

	private $_info;
	private $_import_options;

	function __construct( $id, Refactored_Video_Importer_Provider $provider ) {
		$this->id = $id;
		$this->provider = $provider;
	}

	public function __get( $var ) {
		if ( $var == 'info' ) {
			return $this->get_info();
		} else if ( $var == 'import_options' ) {
			return $this->get_import_options();
		} else {
			throw "Undefined variable $var";
		}
	}

	/**
	 * Getter for the source info
	 * @return string A slug for the provider
	 */
	private function get_info() {
		if ( is_null( $this->_info ) ) {
			$this->_info = get_post_meta( $this->id, 'rfvi_source_information', true );
		}
		return $this->_info;
	}

	/**
	 * Gets import options for a video source
	 * @param  int   $source_id The post ID of a video source
	 * @return array            An array of import options
	 */
	private function get_import_options() {
		if ( is_null( $this->_import_options ) ) {
			$current_options = get_post_meta( $this->id, 'rfvi_import_options', true );
			$defaults = array(
					'post_type'       => 'post',
					'post_status'     => 'publish',
					'import_date'     => 'published',
					'categories'      => array(),
					'auto_tags'       => 1,
					'additional_tags' => '',
					'author'          => false
				);
			if ( is_array( $current_options ) ) {
				// Loop through defaults to fill any undefined indexes
				$this->_import_options = array();
				foreach ( $defaults as $key => $value ) {
					$this->_import_options[$key] = ( isset( $current_options[$key] ) ? $current_options[$key] : $defaults[$key] );
				}
			} else {
				$this->_import_options = $defaults;
			}
		}
		return $this->_import_options;
	}

	/**
	 * Gets the post IDs of all posts imported by a video source
	 * @param  int   $id An optional source ID if called statically
	 * @return array            An array of post IDs
	 */
	static function get_imported_video_ids( $id = false ) {
		if ( !$id ) {
			$id = $this->id;
		}
		$args = array(
			'post_type'      => 'any',
			'posts_per_page' => '-1',
			'meta_key'       => 'rfvi_source_id',
			'meta_value'     => $id,
			'fields'         => 'ids'
		);
		$query = new WP_Query( $args );
		return $query->posts;
	}

	/**
	 * Get the ID of the latest video from the source
	 * @param  int $id An optional source ID if called statically
	 * @return int            The ID of the latest video or 0 if there is none
	 */
	static function get_latest_video_id( $id = false ) {
		if ( !$id ) {
			$id = $this->id;
		}
		global $wpdb;
		$new_post_id = $wpdb->get_var( $wpdb->prepare(
			"
				SELECT $wpdb->posts.ID
				FROM $wpdb->posts
				INNER JOIN $wpdb->postmeta
				ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
				WHERE (
					($wpdb->postmeta.meta_key = 'rfvi_source_id'
					AND CAST($wpdb->postmeta.meta_value AS SIGNED) = %d)
				)
				GROUP BY $wpdb->posts.ID
				ORDER BY $wpdb->posts.post_date
				DESC
				LIMIT 0, 1
			",
			$id
		) );
		return $new_post_id;
	}

	/**
	 * Gets the date of the most recent video from a video source
	 * @param  int $source_id An optional source ID if called statically
	 * @return int            The unix timestamp or 0 if no videos
	 */
	static function get_latest_video_date( $source_id ) {
		if ( !$source_id ) {
			$source_id = $this->source_id;
		}
		$video_id = self::get_latest_video_id( $source_id );
		if ( $video_id != 0 ) {
			$timestamp = intval( get_post_meta( $video_id, 'rfvi_video_date', true ) );
		} else {
			$timestamp = 0;
		}
		return $timestamp;
	}

	/**
	 * Counts the number of videos imported from a  source
	 * @param  int $source_id An optional source ID if called statically
	 * @return int            The number of videos
	 */
	static function count_imported_videos( $source_id = false ) {
		if ( !$source_id ) {
			$source_id = $this->source_id;
		}
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare(
			"
				SELECT COUNT(*)
				FROM $wpdb->postmeta
				WHERE meta_key='rfvi_source_id'
				AND meta_value=%d
			",
			$source_id
		) );
		return $count;
	}

	function get_page( $page ) {
		$response = $this->provider->retrieve_video_feed( $this->info, $page );
		$normalized = $this->provider->normalize_response( $response );
		return $normalized;
	}

	/**
	 * Imports new videos from a single source
	 * @return array            An array of newly imported post IDs
	 */
	function import_new_videos() {
		// TODO: $this->log( 'Starting import for source ID ' . $this->id );
		// Check the provider for API errors
		if ( $this->provider->api_error !== false ) {
			// TODO: $this->log( 'Skipping source ID ' . $this->id . ' (' . $this->provider->name . ' API error encountered)' );
			return array();
		}
		// Get array of known video IDs
		$known_ids = Refactored_Video_Importer::get_known_video_ids( $this->id );
		// Get an array of new videos for this source
		$new_videos = $this->provider->get_new_videos( $this->id, $this->info, $known_ids );
		// Update the last checked info
		update_post_meta( $this->id, 'rfvi_last_checked', time() );
		// Loop through the array and make new posts
		$imported_ids = array();
		foreach( $new_videos as $video ) {
			$imported_ids[] = $this->import_video( $video );
		}
		// TODO: $this->log( 'Imported ' . count( $imported_ids ) . ' videos from source ID ' . $this->id );
		return $imported_ids;
	}

	/**
	 * Builds an array to be used in wp_insert_post()
	 * @param  array $video_array An array containing video information
	 * @return array              An array suitable for wp_insert_post()
	 */
	private function build_post_array( $video_array ) {
		$date = ( $this->import_options['import_date'] == 'published' ? $video_array['date'] : '0000-00-00 00:00:00' );
		// Determine any tags to use
		$tags = explode( ',', $this->import_options['additional_tags'] );
		if ( $this->import_options['auto_tags'] ) {
			if ( !isset( $video_array['tags'] ) ) $video_array['tags'] = array();
			$tags = array_merge( $tags, $video_array['tags'] );
		}
		$tags = implode( ', ', $tags );
		// Setup our post array
		$post = array(
			'post_author'  => $this->import_options['author'],
			'post_content' => $video_array['url'] . PHP_EOL . PHP_EOL . $video_array['description'],
			'post_date'    => $date,
			'post_status'  => $this->import_options['post_status'],
			'post_title'   => $video_array['title'],
			'post_type'    => $this->import_options['post_type'],
			'tags_input'   => $tags
		);
		// Filter the post array to allow any additional modifications
		return apply_filters( 'refactored_video_importer/post_array', $post, $provider, $video_array, $source_id, $this->import_options );
	}

	/**
	 * Sets custom meta fields for video posts
	 * @param int   $post_id     The post ID
	 * @param array $video_array A video array
	 */
	private function set_post_meta( $post_id, $video_array ) {
		update_post_meta( $post_id, 'rfvi_source_id', $this->id );
		update_post_meta( $post_id, 'rfvi_provider', $this->provider );
		update_post_meta( $post_id, 'rfvi_video_id', $video_array['id'] );
		update_post_meta( $post_id, 'rfvi_video_date', strtotime( $video_array['date'] ) );
		update_post_meta( $post_id, 'rfvi_import_date', time() );
		update_post_meta( $post_id, 'rfvi_import_version', REFACTORED_VIDEO_IMPORTER_VERSION );
		// Add raw data if it exists
		if ( isset( $video_array['raw_data'] ) && $video_array['raw_data'] != '' ) {
			update_post_meta( $post_id, 'rfvi_raw_data', $video_array['raw_data'] );
		}
	}

	/**
	 * Import video as a post
	 * @param  array  $video_array    An array of key/value pairs about the video
	 * @return int                    The ID of the imported post
	 */
	function import_video( $video_array ) {
		// TODO: $this->log( 'Importing video from source ID ' . $this->id );
		// Build the post array
		$post = $this->build_post_array( $video_array );
		// Insert the post
		$post_id = wp_insert_post( $post );
		// Set the categories
		wp_set_post_terms( $post_id, $this->import_options['categories'], 'category' );
		// Add post meta
		$this->set_post_meta( $post_id, $video_array );
		// Execute an action hook after the video is imported
		do_action( 'refactored_video_importer/single_video_imported', $post_id, $this->provider->slug, $video_array, $this->id, $this->import_options );
		// Log
		// TODO: $this->log( 'Imported new video with a post ID of ' . $post_id );
		// Return the new post's ID
		return $post_id;
	}

}