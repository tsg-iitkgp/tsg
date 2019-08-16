<?php
/**
 * Class to load freemius configuration
 */

namespace Stop_Wp_Emails_Going_To_Spam\Includes;


class Freemius_Config {

	public function init() {

		global $stop_wp_emails_going_to_spam_fs;

		if ( ! isset( $stop_wp_emails_going_to_spam_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';

			$stop_wp_emails_going_to_spam_fs = fs_dynamic_init( array(
				'id'                  => '2850',
				'slug'                => 'stop-wp-emails-going-to-spam',
				'type'                => 'plugin',
				'public_key'          => 'pk_e217666001b5a5f7a75a93e55e3f5',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => 'stop-wp-emails-going-to-spam-settings',
					'account'        => false,
					'contact'        => false,
					'parent'         => array(
						'slug' => 'options-general.php',
					),
				),
			) );
		}

        $stop_wp_emails_going_to_spam_fs->add_filter('is_submenu_visible', array($this,'_fs_show_support_menu'), 10, 2);

		return $stop_wp_emails_going_to_spam_fs;
	}
    public function _fs_show_support_menu( $is_visible, $menu_id ) {
        /** @var \Freemius $stop_wp_emails_going_to_spam_fs Freemius global object. */
        global $stop_wp_emails_going_to_spam_fs;
        if ( 'support' === $menu_id ) {
            return $stop_wp_emails_going_to_spam_fs->is_free_plan();
        }
        return $is_visible;
    }


}