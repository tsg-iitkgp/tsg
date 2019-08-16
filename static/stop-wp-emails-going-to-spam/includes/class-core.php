<?php


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 */

namespace Stop_Wp_Emails_Going_To_Spam\Includes;

use Stop_Wp_Emails_Going_To_Spam\Admin\Admin;
use Stop_Wp_Emails_Going_To_Spam\Admin\Admin_Settings;



class Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;
	protected $freemius;
	protected $domain;
	protected $options;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param \Freemius $freemius Object for freemius.
	 */
	public function __construct( $freemius ) {

		$this->plugin_name = 'stop-wp-emails-going-to-spam';

		$this->version = STOP_WP_EMAILS_GOING_TO_SPAM_PLUGIN_VERSION;

		$this->freemius = $freemius;
		$this->domain = esc_html( str_ireplace( 'www.', '', parse_url( get_site_url(), PHP_URL_HOST ) ) );

		$this->options = get_option( 'stop-wp-emails-going-to-spam-settings-1' );

		$this->loader = new Loader();
		$this->set_locale();
		$this->settings_pages();
		$this->define_admin_hooks();;

	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Register all of the hooks related to the admin settings area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function settings_pages() {

		$settings = new Admin_Settings( $this->get_plugin_name(), $this->get_version(), $this->freemius , $this->domain, $this->options);
		// options set up
		if (!get_option('stop-wp-emails-going-to-spam-settings-1')) {
			update_option( 'stop-wp-emails-going-to-spam-settings-1', $settings->option_defaults( 'stop-wp-emails-going-to-spam-settings-1' ) );
		}

		$this->loader->add_action( 'admin_menu', $settings, 'settings_setup' );

	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Responsible for defining all actions that occur in the admin area.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version() , $this->domain, $this->options);


		$this->loader->add_action( 'phpmailer_init', $plugin_admin, 'set_envelope_sender' );
		$this->loader->add_action( 'wp_mail_from', $plugin_admin, 'wp_mail_from' );
		$this->loader->add_action( 'wp_mail_from_name', $plugin_admin, 'wp_mail_from_name' );



	}


	/**
	 * Run the loader to execute all of the hooks with WordPress
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function run() {
		$this->loader->run();
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function get_loader() {
		return $this->loader;
	}

}
