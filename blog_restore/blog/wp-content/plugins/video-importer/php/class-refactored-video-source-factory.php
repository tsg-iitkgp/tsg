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

class Refactored_Video_Source_Factory {

	/**
	 * Construct a video source instance
	 * @param  int $id The source ID
	 * @return Refactored_Video_Source
	 */
	public static function make( $id ) {
		return new Refactored_Video_Source( $id, Refactored_Video_Source_Factory::get_provider_object( $id ) );
	}

	/**
	 * Get the provider object needed for the source ID
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	private static function get_provider_object( $id ) {
		global $refactored_video_importer;
		$provider_slug = Refactored_Video_Source_Factory::get_provider_slug( $id );
		if ( $provider_slug == '' ) {
			return new Refactored_Video_Importer_Provider();
		}
		return $refactored_video_importer->providers[$provider_slug];
	}

	private static function get_provider_slug( $id ) {
		return get_post_meta( $id, 'rfvi_provider', true );
	}

}