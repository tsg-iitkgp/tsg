<?php


/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 */

namespace Stop_Wp_Emails_Going_To_Spam\Includes;

class Uninstall {

	/**
	 * Uninstall specific code
	 */
	public static function uninstall() {
        delete_option('stop-wp-emails-going-to-spam-settings-1');

	}

}
