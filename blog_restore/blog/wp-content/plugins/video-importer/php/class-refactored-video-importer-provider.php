<?php

/*  Copyright 2013 Sutherland Boswell  (email : sutherland.boswell@gmail.com)

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

class Refactored_Video_Importer_Provider {

	var $settings = array();
	var $api_error = false;

	function __construct() {

		// Add filter to add settings args if they exist and set current settings
		if ( isset( $this->settings_args ) ) {
			add_filter( 'refactored_video_importer/settings_args', array( &$this, 'add_settings_args' ) );
			$settings = get_option( 'refactored_video_importer' );
			$this->settings = $settings[$this->slug];
		}

	}

	function add_settings_args( $settings ) {
		$group['name'] = $this->name . ' Settings';
		$group['description'] = $this->settings_args['description'];
		$group['fields'] = $this->settings_args['fields'];
		$settings['options'][$this->slug] = $group;
		return $settings;
	}

	/**
	 * Displays the meta box for source options
	 * @param  int $post_id The post ID of the source
	 */
	function source_options_meta_box( $post_id ) {
		$source_information = $this->get_source_information( $post_id );
		foreach ( $this->source_information_fields as $key => $value ) {
			switch ( $value['type'] ) {
				case 'text':
					$this->text_option_field(
						$value['name'],
						$key,
						$source_information[$key]
					);
					break;

				case 'dropdown':
					$this->dropdown_option_field(
						$value['name'],
						$key,
						$value['options'],
						$source_information[$key]
					);
					break;

				default:
					# code...
					break;
			}
		}
	}

	/**
	 * Gets the source information
	 * @param  int   $source_id The ID of a video source
	 * @return array            The array of source information
	 */
	function get_source_information( $source_id ) {
		$saved_source_information = get_post_meta( $source_id, 'rfvi_source_information', true );
		$source_information = array();
		foreach ( $this->source_information_fields as $key => $value ) {
			$source_information[$key] = $value['default'];
			if ( is_array( $saved_source_information ) && isset( $saved_source_information[$key] ) ) {
				$source_information[$key] = $saved_source_information[$key];
			}
		}
		return $source_information;
	}

	/**
	 * Outputs a dropdown selection for source options
	 * @param  string $name     A user-friendly name of the option
	 * @param  string $slug     A unique slug to store the option as
	 * @param  array  $options  An array of keys and values for the options
	 * @param  string $selected An optional slug for the already-selected value
	 */
	function dropdown_option_field( $name, $slug, $options, $selected = false ) {
		echo '<label for="' . $slug . '-option" class="header">' . $name . '</label>';
		echo '<select id="' . $slug . '-option" name="rfvi_source_information[' . $slug . ']">';
		foreach ( $options as $key => $value ) {
			$selected_attr = ( $selected == $key ? 'selected="selected" ' : '' );
			echo '<option value="' . $key . '" ' . $selected_attr . '>' . $value . '</option>';
		}
		echo '</select>';
	}

	function text_option_field( $name, $slug, $value = '' ) {
		echo '<label for="' . $slug . '-option" class="header">' . $name . '</label>';
		echo '<input type="text" id="' . $slug . '-option" name="rfvi_source_information[' . $slug . ']" value="' . $value . '" />';
	}

	/**
	 * Get an array of new videos from a source
	 * @param  int   $source_id   Post ID of the source
	 * @param  array $source_info Array of information about the source (username, playlist, etc)
	 * @param  array $known_ids   Array of known video IDs
	 * @return array              An array of new videos to be imported
	 */
	function get_new_videos( $source_id, $source_info, $known_ids ) {
		$new_videos = array();
		$page = 0;
		$reached_known_video = false;
		$reached_last_page = false;
		$max_past_videos = 50;
		while ( !$reached_known_video && !$reached_last_page ) {
			$page++;
			$response = $this->retrieve_video_feed( $source_info, $page );
			if ( is_wp_error( $response ) ) {
				if ( $response->get_error_code() == 'api_error' ) {
					$this->api_error = $response;
				}
				break;
			}
			$videos = $this->standardize_video_array( $response );
			foreach ( $videos as $video ) {
				if ( !in_array( $video['id'], $known_ids ) ) {
					$new_videos[] = $video;
				} else {
					$reached_known_video = true;
					break;
				}
			}
			if ( $this->reached_last_page( $page, $max_past_videos, $response ) ) {
				$reached_last_page = true;
			}
		}
		return $new_videos;
	}

	function normalize_response( $response ) {
		return array(
			'videos'    => $this->standardize_video_array( $response ),
			'next_page' => $this->get_next_page_key( $response )
		);
	}

	/**
	 * Recursively converts an object to an array
	 * @param  object $obj The object
	 * @return array       The array
	 */
	static function object_to_array( $object, $assoc=1, $empty='' ) { 
		$out_arr = array();
		$assoc = (!empty($assoc)) ? TRUE : FALSE; 
		
		if (!empty($object)) {
			
			$arrObj = is_object($object) ? get_object_vars($object) : $object;
		
			$i=0;
			foreach ($arrObj as $key => $val) {
				$key = ($assoc !== FALSE) ? $key : $i;
					if (is_array($val) || is_object($val)) {
						$out_arr[$key] = (empty($val)) ? $empty : self::object_to_array($val);
					}
					else {
						$out_arr[$key] = (empty($val)) ? $empty : (string)$val;
					}
			$i++;
			}
	
		}
	
		return $out_arr;
	}

}