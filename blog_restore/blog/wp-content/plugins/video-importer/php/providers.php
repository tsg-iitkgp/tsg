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
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/providers/youtube/youtube.php' );
require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/providers/vimeo/vimeo.php' );
// require_once( REFACTORED_VIDEO_IMPORTER_PATH . '/providers/brightcove/brightcove.php' );

add_filter( 'refactored_video_importer/providers', 'add_default_refactored_video_importer_providers' );

function add_default_refactored_video_importer_providers( $providers ) {
	$providers['youtube'] = new Refactored_YouTube_Importer();
	$providers['vimeo'] = new Refactored_Vimeo_Importer();
	// $providers['brightcove'] = new Refactored_Brightcove_Importer();
	return $providers;
}