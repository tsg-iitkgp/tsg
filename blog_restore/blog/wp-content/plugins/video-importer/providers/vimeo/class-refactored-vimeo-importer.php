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

require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/php/class-refactored-video-importer-provider.php' );

class Refactored_Vimeo_Importer extends Refactored_Video_Importer_Provider {

	public $name = 'Vimeo';
	public $slug = 'vimeo';

	var $api = false;

	var $settings_args = array(
		'description' => '<strong>Required for using Vimeo</strong>: <a href="https://developer.vimeo.com/apps">Register an app</a> with Vimeo for free and enter your keys below.',
		'fields'      => array(
			'client_id' => array(
				'name' => 'Client ID',
				'type' => 'text',
				'default' => '',
				'description' => 'Also known as Consumer Key or API Key'
			),
			'client_secret' => array(
				'name' => 'Client Secret',
				'type' => 'text',
				'default' => '',
				'description' => 'Also known as Consumer Secret or API Secret'
			)
		)
	);

	var $source_information_fields = array(
		'type' => array(
			'name' => 'Type',
			'type' => 'dropdown',
			'options' => array(
				'user'    => 'User',
				'group'   => 'Group',
				'channel' => 'Channel'
			),
			'default' => 'user'
		),
		'name' => array(
			'name' => 'Name',
			'type' => 'text',
			'default' => ''
		)
	);

	/**
	 * Gets an instance for the phpVimeo class
	 * @return phpVimeo The phpVimeo API class
	 */
	function get_api_instance() {
		if ( $this->api == false ) {
			$this->api = new phpVimeo( $this->settings['client_id'], $this->settings['client_secret'] );
		}
		return $this->api;
	}

	/**
	 * Retrieves a video feed from Vimeo
	 * @param  string $source_info Array of information about the source (username, playlist, etc)
	 * @param  int    $page        Which page to retrieve
	 * @return stdClass            The video feed
	 */
	function retrieve_video_feed( $source_info, $page = 1 ) {
		$args = array(
			'full_response' => true,
			'per_page'      => 50,
			'page'          => $page
		);
		switch ( $source_info['type'] ) {
			case 'user':
				$endpoint = 'vimeo.videos.getUploaded';
				$args['user_id'] = $source_info['name'];
				break;

			case 'group':
				$endpoint = 'vimeo.groups.getVideos';
				$args['group_id'] = $source_info['name'];
				break;

			case 'channel':
				$endpoint = 'vimeo.channels.getVideos';
				$args['channel_id'] = $source_info['name'];
				break;
			
			default:
				# code...
				break;
		}
		try {
			$videos = $this->get_api_instance()->call( $endpoint, $args );
		} catch ( Exception $e ) {
			$videos = new WP_Error( 'api_error', $e->getMessage() );
		}
		return $videos;
	}

	/**
	 * Test if the last page needed has been reached
	 * @param  int     $page            The page number
	 * @param  int     $max_past_videos The maximum number of past videos to collect
	 * @param  json    $response        The most recent API response
	 * @return boolean                  A boolean telling if the last page was reached
	 */
	function reached_last_page( $page, $max_past_videos, $response ) {
		if ( ( $response->videos->page * $response->videos->perpage ) >= $response->videos->total ||
			 ( $response->videos->page * $response->videos->perpage ) >= $max_past_videos ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Converts a Vimeo API response into a standardized video array
	 * @param  stdClass $response A response from vimeo
	 * @return array              A standardized array of videos for RFVI
	 */
	function standardize_video_array( $response ) {
		$result = array();
		if ( isset( $response->videos->video ) ) {
			foreach ( $response->videos->video as $video ) {
				$tags = array();
				if ( isset( $video->tags->tag ) ) {
					foreach ( $video->tags->tag as $tag ) {
						$tags[] = $tag->_content;
					}
				}
				$date = date( 'Y-m-d H:i:s', strtotime( $video->upload_date ) );
				$result[] = array(
					'title'       => $video->title,
					'id'          => $video->id,
					'url'         => 'http://vimeo.com/' . $video->id,
					'description' => $video->description,
					'date'        => $date,
					'tags'        => $tags,
					'raw_data'    => self::object_to_array( $video )
				);
			}
		}
		return $result;
	}

	function get_next_page_key( $response ) {
		$key = false;
		if ( ( $response->videos->page * $response->videos->perpage ) < $response->videos->total ) {
			$key = $response->videos->page + 1;
		}
		return $key;
	}

}