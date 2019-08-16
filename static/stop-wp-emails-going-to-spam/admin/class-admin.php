<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */

namespace Stop_Wp_Emails_Going_To_Spam\Admin;


class Admin {

	/**
	 * The ID of this plugin.
	 *
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 */
	private $version;

	private $options;

	private $domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct( $plugin_name, $version, $domain , $options ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = $options;
		$this->domain = $domain;

	}

	public function set_envelope_sender( $phpmailer ) {

		if ( ! is_email( $phpmailer->Sender ) ) {
			if ( 'from' == $this->options['envelope'] ) {
				$email = $phpmailer->From;
			} else {
				$email = $this->get_email();
				if ( 'envelope' == $this->options['envelope'] ) {
					$phpmailer->From = $email;
				}
			}
			$phpmailer->Sender = $email;
		}
	}

	public function wp_mail_from_name( $name ) {

		if ( 'WordPress' == $name && isset( $this->options['wordpressname'] ) && ! empty( $this->options['wordpressname'] ) ) {   // and option has value
			return $this->options['wordpressname'];
		}

		return $name;
	}

	public function wp_mail_from( $email ) {

		if ( 'wordpress' == strtok( $email, '@' ) ) {
			if ( isset( $this->options['wordpresschoice'] ) ) {
				if ( "envelope" == $this->options['wordpresschoice'] ) {
					return $this->get_email();
				}
				if ( "custom" == $this->options['wordpresschoice'] ) {
					return $this->options['wordpressemail'] . '@' . $this->domain;
				}
			}
		}

		return $email;
	}

	private function get_email() {
		if ( 'admin' == $this->options['email'] ) {
			return get_bloginfo( 'admin_email' );
		}

		return $this->options['emailname'] . '@' . $this->domain;

	}



}
