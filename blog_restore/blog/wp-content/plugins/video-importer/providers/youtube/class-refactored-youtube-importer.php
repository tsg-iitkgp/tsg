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

class Refactored_YouTube_Importer extends Refactored_Video_Importer_Provider {

	public $name = 'YouTube';
	public $slug = 'youtube';

	var $settings_args = array(
		'description' => '<strong>Required for using YouTube</strong>: Create a public API key for YouTube in the <a href="https://console.developers.google.com/project">Google Developers Console</a> and copy your API key. <a href="http://wie.ly/u/ytkey" target="_blank">View Tutorial &rarr;</a>',
		'fields'      => array(
			'api_key' => array(
				'name' => 'API Key',
				'type' => 'text',
				'default' => '',
				'description' => ''
			)
		)
	);

	var $source_information_fields = array(
		'type' => array(
			'name' => 'Type',
			'type' => 'dropdown',
			'options' => array(
				'user'     => 'User',
				'playlist' => 'Playlist'
			),
			'default' => 'user'
		),
		'name' => array(
			'name' => 'Name/ID',
			'type' => 'text',
			'default' => ''
		)
	);

	/**
	 * Executes an API request
	 * @param  string $url The requested URL
	 * @return mixed       WP_Error or decoded Json
	 */
	private function execute_request( $url ) {
		$response = wp_remote_get( $url );
		if ( !is_wp_error( $response ) ) {
			$response = wp_remote_retrieve_body( $response );
			$json = json_decode( $response );
			if ( isset( $json->error ) ) {
				$json = new WP_Error( 'api_error', $json->error->message );
			}
		} else {
			$json = $response;
		}
		return $json;
	}

	/**
	 * Retrieve info about a channel from the API
	 * @param  string $name Channel username
	 * @return mixed        WP_Error or decoded Json
	 */
	private function retrieve_channel_info( $name ) {
		$endpoint = 'https://www.googleapis.com/youtube/v3/channels';
		$args = array(
			'part'        => 'contentDetails',
			'forUsername' => $name
		);
		return $this->execute_request( $this->build_request_url( $endpoint, $args ) );
	}

	/**
	 * Retrieve a user's uploads from the API
	 * @param  string $name       Channel username
	 * @param  string $page_token A page token
	 * @return mixed              WP_Error or decoded Json
	 */
	private function retrieve_user_uploads( $name, $page_token ) {
		$channel_info = $this->retrieve_channel_info( $name );
		if ( isset( $channel_info->items[0]->contentDetails->relatedPlaylists->uploads ) ) {
			return $this->retrieve_playlist_videos( $channel_info->items[0]->contentDetails->relatedPlaylists->uploads, $page_token );
		}
	}

	/**
	 * Retrieve a playlist's videos from the API
	 * @param  string $id         A playlist ID
	 * @param  string $page_token A page token
	 * @return mixed              WP_Error or decoded Json
	 */
	private function retrieve_playlist_videos( $id, $page_token = false ) {
		$endpoint = 'https://www.googleapis.com/youtube/v3/playlistItems';
		$args = array(
			'part'       => 'snippet',
			'playlistId' => $id,
			'maxResults' => 50
		);
		if ( $page_token && $page_token !== 1 ) {
			$args['pageToken'] = $page_token;
		}
		return $this->execute_request( $this->build_request_url( $endpoint, $args ) );
	}

	/**
	 * Builds a full URL for an endpoint with args. Also makes sure the API key is included.
	 * @param  string $endpoint An API endpoint
	 * @param  array  $args     An array of paramenters
	 * @return string           The full URL with parameters
	 */
	private function build_request_url( $endpoint, $args ) {
		if ( !isset( $args['key'] ) ) {
			$args['key'] = $this->settings['api_key'];
		}
		return $endpoint . '?' . http_build_query( $args );
	}

	/**
	 * Retrieves a video feed from YouTube
	 * @param  string $source_info Array of information about the source (username, playlist, etc)
	 * @param  int    $page        Which page to retrieve
	 * @return stdClass            The video feed
	 */
	function retrieve_video_feed( $source_info, $page = false ) {

		switch ( $source_info['type'] ) {
			case 'user':
				$videos = $this->retrieve_user_uploads( $source_info['name'], $page );
				break;

			case 'playlist':
				$videos = $this->retrieve_playlist_videos( $source_info['name'], $page );
				break;

			default:
				# code...
				break;
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
		if ( ( $page * $response->pageInfo->resultsPerPage ) >= $response->pageInfo->totalResults ||
			 ( $page * $response->pageInfo->resultsPerPage ) >= $max_past_videos ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Converts a response from YouTube into a standardized video array
	 * @param  stdClass $response A response from vimeo
	 * @return array              A standardized array of videos for RFVI
	 */
	function standardize_video_array( $response ) {
		$result = array();
		if ( isset( $response->items ) ) {
			foreach ( $response->items as $video ) {
				if ( isset( $video->snippet ) ) $video = $video->snippet;
				if ( !isset( $video->description ) ) continue;
				$tags = array();
				$date = date( 'Y-m-d H:i:s', strtotime( $video->publishedAt ) );
				$result[] = array(
					'title'       => $video->title,
					'id'          => $video->resourceId->videoId,
					'url'         => 'http://youtu.be/' . $video->resourceId->videoId,
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
		if ( isset( $response->nextPageToken ) ) {
			$key = $response->nextPageToken;
		}
		return $key;
	}

}