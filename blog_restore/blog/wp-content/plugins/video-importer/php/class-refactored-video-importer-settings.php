<?php
/*  Copyright 2014 Sutherland Boswell  (email : hello@sutherlandboswell.com)

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

if ( !class_exists( 'Refactored_Video_Importer_Settings' ) ) :

class Refactored_Video_Importer_Settings extends Refactored_Settings_0_4_2 {

	/**
	 * Adds the options page to the admin menu
	 */
	function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=rf_video_source',
			__( 'Video Importer Settings', 'refactored-video-importer' ),
			__( 'Settings', 'refactored-video-importer' ),
			'manage_options',
			'video_importer_settings',
			array( &$this, 'options_page' )
		);
	}

	function settings_field_callback( $args ) {

		if ( $args['group'] == 'general' && $args['slug'] == 'logging' ) {
			parent::settings_field_callback( $args );
			if ( $this->options[$args['group']][$args['slug']] == 'enabled' ) {
				$this->show_log();
			}
		} else {
			parent::settings_field_callback( $args );
		}

	}

	function show_log() {
		echo '<p><textarea readonly onclick="this.focus();this.select()" cols="70" rows="10">' . get_option( 'rfvi_log', __( 'The log will appear here and will be deleted when disabled.', 'refactored-video-importer' ) ) . '</textarea></p>';
	}

}

endif;