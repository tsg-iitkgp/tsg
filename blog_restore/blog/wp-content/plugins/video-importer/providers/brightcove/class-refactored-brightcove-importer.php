<?php

/*  Copyright 2014 Sutherland Boswell  (email : sutherland.boswell@gmail.com)

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

class Refactored_Brightcove_Importer extends Refactored_Video_Importer_Provider {

	function __construct() {

		parent::__construct();

		// These filters enable thumbnail support when Video Thumbnails is installed
		add_filter( 'new_video_thumbnail_url', array( &$this, 'filter_video_thumbnail_url' ), 10, 2 );
		add_action( 'refactored_video_importer/single_video_imported', array( &$this, 'get_imported_video_thumbnail' ), 10, 2 );

	}

	public $name = 'Brightcove';
	public $slug = 'brightcove';

	var $settings_args = array(
		'description' => '<strong>Required for using Brightcove</strong>: Brightcove requires specifying your embed code.',
		'fields'      => array(
			'embed_code' => array(
				'name' => 'Embed Code',
				'type' => 'textarea',
				'default' => '',
				'class' => 'code',
				'description' => 'Enter the Brightcove embed code to use in the shortcode. Use <code>%%id%%</code> in place of the video ID or <code>%%ref_id%%</code> in place of the reference ID.'
			)
		)
	);

	var $source_information_fields = array(
		'type' => array(
			'name' => 'Type',
			'type' => 'dropdown',
			'options' => array(
				'all_videos' => 'All Videos'
			),
			'default' => 'user'
		),
		'read_token' => array(
			'name' => 'Read Token',
			'type' => 'text',
			'default' => ''
		)
	);

	/**
	 * Retrieves a video feed from Brightcove
	 * @param  string $source_info Array of information about the source (username, playlist, etc)
	 * @param  int    $page        Which page to retrieve
	 * @return stdClass            The video feed
	 */
	function retrieve_video_feed( $source_info, $page = 1 ) {
		// Brightcove uses zero-based page numbers
		$zero_based_page = $page - 1;
		// Verify a read token is set
		if ( !$source_info['read_token'] ) {
			return new WP_Error( 'brightcove_api_key_error', 'Be sure you have the entered a <a href="http://support.brightcove.com/en/video-cloud/docs/managing-media-api-tokens#get">read token</a>.' );
		}
		// The API endpoint
		$endpoint = 'http://api.brightcove.com/services/library';
		// Arguments for the API
		$args = array(
			'page_size'      => 50,
			'video_fields'   => 'id,name,shortDescription,longDescription,creationDate,publishedDate,lastModifiedDate,linkURL,linkText,tags,customFields,cuePoints,videoStillURL,thumbnailURL,referenceId,length,economics,playsTotal,playsTrailingWeek,FLVURL,renditions,HLSURL,FLVFullLength,videoFullLength,iOSRenditions,captioning,adKeys,digitalMaster,logoOverlay,thumbnail,videoPreview,videoStill,accountId,itemState,startDate,endDate,geoRestricted,geoFilteredCountries,geoFilterExclude,WVMRenditions',
			'media_delivery' => 'default',
			'sort_by'        => 'PUBLISH_DATE',
			'sort_order'     => 'DESC',
			'page_number'    => $zero_based_page,
			'get_item_count' => 'true',
			'token'          => $source_info['read_token']
		);
		// Command type
		switch ( $source_info['type'] ) {
			case 'all_videos':
				$args['command'] = 'find_all_videos';
				break;
			
			default:
				$args['command'] = 'find_all_videos';
				break;
		}
		$remote_get_args = array(
			'timeout' => 10
		);
		$response = wp_remote_get( $endpoint . '?' . http_build_query( $args ), $remote_get_args );
		// $retries = 0;
		// while ( is_wp_error( $response ) && $retries < 5 ) {
		// 	$response = wp_remote_get( $endpoint . '?' . http_build_query( $args ), $remote_get_args );
		// 	$retries++;
		// }
		$response = wp_remote_retrieve_body( $response );
		$videos = json_decode( $response );
		if ( isset( $videos->error ) ) {
			$videos = new WP_Error( 'api_error', $videos->error );
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
		if ( ( $page * $response->page_size ) >= $response->total_count ||
			 ( $page * $response->page_size ) >= $max_past_videos ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Converts a Brightcove API response into a standardized video array
	 * @param  stdClass $response A response from vimeo
	 * @return array              A standardized array of videos for RFVI
	 */
	function standardize_video_array( $response ) {
		$result = array();
		if ( isset( $response->items ) ) {
			foreach ( $response->items as $video ) {
				$tags = array();
				if ( isset( $video->tags ) ) {
					foreach ( $video->tags as $tag ) {
						$tags[] = $tag;
					}
				}
				$date = date( 'Y-m-d H:i:s', substr( $video->publishedDate, 0, -3 ) );
				$result[] = array(
					'title'       => $video->name,
					'id'          => $video->id,
					'url'         => $video->linkURL,
					'description' => $video->longDescription,
					'date'        => $date,
					'tags'        => $tags,
					'raw_data'    => self::object_to_array( $video )
				);
			}
		}
		return $result;
	}

	/**
	 * Filters the new thumbnail URL for video thumbnails
	 * @param  mixed $new_thumbnail Either null or a string containing a new thumbnail URL
	 * @param  int   $post_id       The post ID
	 * @return mixed                Null if no thumbnail or a string with a remote URL
	 */
	function filter_video_thumbnail_url( $new_thumbnail, $post_id ) {
		if ( $new_thumbnail == null ) {
			$raw_data = get_post_meta( $post_id, 'rfvi_raw_data', true );
			if ( is_array( $raw_data ) && isset( $raw_data['videoStillURL'] ) ) {
				$new_thumbnail = $raw_data['videoStillURL'];
			}
		}
		return $new_thumbnail;
	}

	/**
	 * Action function run after a video is importer to get a thumbnail when Video Thumbnails is enabled
	 * @param  int    $post_id  The post ID
	 * @param  string $provider The provider slug
	 */
	function get_imported_video_thumbnail( $post_id, $provider ) {
		if ( $provider == 'brightcove' && function_exists( 'get_video_thumbnail' ) ) {
			get_video_thumbnail( $post_id );
		}
	}

}