<?php
/**
 * Created
 * User: alan
 * Date: 04/04/18
 * Time: 13:45
 */

namespace Stop_Wp_Emails_Going_To_Spam\Admin;


class Admin_Settings extends Admin_Pages {

	protected $settings_page;
	// protected $settings_page_id = 'toplevel_page_stop-wp-emails-going-to-spam';  // top level
	protected $settings_page_id = 'settings_page_stop-wp-emails-going-to-spam-settings';
	protected $option_group = 'stop-wp-emails-going-to-spam';
	protected $settings_title;
	protected $domain;
	protected $options;

	/**
	 * Settings constructor.
	 *
	 * @param string $plugin_name
	 * @param string $version plugin version.
	 * @param \Freemius $freemius Freemius SDK.
	 */

	public function __construct( $plugin_name, $version, $freemius, $domain, $options) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->freemius    = $freemius;
		$this->domain      = $domain;
		$this->options = $options;


		$this->settings_title = esc_html__( 'Stop WP Emails Going to Spam', 'stop-wp-emails-going-to-spam' );
		parent::__construct();
	}

	public function register_settings() {
		/* Register our setting. */
		register_setting(
			$this->option_group,                         /* Option Group */
			'stop-wp-emails-going-to-spam-settings-1',                   /* Option Name */
			array( $this, 'sanitize_settings_1' )          /* Sanitize Callback */
		);


		/* Add settings menu page */
		$this->settings_page = add_submenu_page(
			'stop-wp-emails-going-to-spam',
			'Settings', /* Page Title */
			'Settings',                       /* Menu Title */
			'manage_options',                 /* Capability */
			'stop-wp-emails-going-to-spam',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);

		register_setting(
			$this->option_group,                         /* Option Group */
			"{$this->option_group}-reset",                   /* Option Name */
			array( $this, 'reset_sanitize' )          /* Sanitize Callback */
		);

	}


	public function delete_options() {
		update_option( 'stop-wp-emails-going-to-spam-settings-1', self::option_defaults( 'stop-wp-emails-going-to-spam-settings-1' ) );

	}

	public static function option_defaults( $option ) {
		switch ( $option ) {
			case 'stop-wp-emails-going-to-spam-settings-1':
				return array(
					// set defaults
					'email'     => 'admin',
					'emailname' => '',
					'envelope'  => 'envelope',
                    'wordpresschoice' => 'envelope',
                    'wordpressname' => 'WordPress',
                    'wordpressemail' => 'wordpress'
				);
			default:
				return false;
		}
	}

	public function add_meta_boxes() {
		add_meta_box(
			'settings-info',                  /* Meta Box ID */
			__( 'Information', 'stop-wp-emails-going-to-spam' ),               /* Title */
			array( $this, 'meta_box_info' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		add_meta_box(
			'settings-1',                  /* Meta Box ID */
			__( 'Envelope Sender', 'stop-wp-emails-going-to-spam' ),               /* Title */
			array( $this, 'meta_box_1' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		add_meta_box(
			'settings-2',                  /* Meta Box ID */
			__( 'Sender Permitted From ( SPF )', 'stop-wp-emails-going-to-spam' ),               /* Title */
			array( $this, 'meta_box_2' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);


	}

	public function meta_box_info() {
		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'About this Plugin', 'stop-user-enumeration' ); ?></th>
                <td>
					<?php _e( '<p>This plugin tries to help you stop emails being spammed when sent from your WordPress website.</p>
                    <p>When using the default PHP mailer on shared hosts WordPress does not correctly set the "envelope sender".</p>
                    <p>Use the settings to select the email that you want as the "envelope sender"</p><br>
                    <p>For best results the "envelope sender" domain should have a SPF record, see the SPF section, and the email address should exist</p>
                    <p>This plugin will only set the "envelope sender" is other plugins have not.</p><br>
                    <p>You do not need this plugin if you are using an SMTP email plugin or using an API based email solution.</p>', 'stop-user-enumeration' ); ?>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function meta_box_1() {
		?>
        <p>
            <span class="description"><?php printf( __( 'This sets envelope sender of the message, if not set by another program.<br>This will usually be turned into a Return-Path header by the receiver, and is the address that bounces will be sent to', 'stop-wp-emails-going-to-spam' ), $this->domain ); ?></span>
        </p>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Use Admin Email', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
                    <label for="stop-wp-emails-going-to-spam-settings-1[email]"><input type="radio"
                                                                                        name="stop-wp-emails-going-to-spam-settings-1[email]"
                                                                                        id="stop-wp-emails-going-to-spam-settings-1[email]"
                                                                                        value="admin"
							<?php checked( 'admin', $this->options['email'] ); ?>>
						<?php echo get_bloginfo( 'admin_email' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Use another Domain email', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
                    <label for="stop-wp-emails-going-to-spam-settings-1[email]"><input type="radio"
                                                                                        name="stop-wp-emails-going-to-spam-settings-1[email]"
                                                                                        id="stop-wp-emails-going-to-spam-settings-1[email]"
                                                                                        value="domain"
							<?php checked( 'domain', $this->options['email'] ); ?>>
                        <input type="text"
                               style="text-align: right"
                               class="medium-text"
                               name="stop-wp-emails-going-to-spam-settings-1[emailname]"
                               id="stop-wp-emails-going-to-spam-settings-1[emailname]"
                               value="<?php echo $this->options['emailname'] ?>">@<?php echo $this->domain; ?>
                    </label>
                    <p>
                        <span class="description"><?php printf( __( 'You can use an email like noreply@%s, but make sure the email account exists.', 'stop-wp-emails-going-to-spam' ), $this->domain ); ?></span>
                    </p>

                </td>
            <tr valign="top">
                <th scope="row"><?php _e( 'From Address', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
                    <p>
                        <span class="description"><?php printf( __( 'Set the relationship between From address and Envelope address', 'stop-wp-emails-going-to-spam' ), $this->domain ); ?></span>
                    </p>
                    <label for="stop-wp-emails-going-to-spam-settings-1[envelope]"><input type="radio"
                                                                                           name="stop-wp-emails-going-to-spam-settings-1[envelope]"
                                                                                           id="stop-wp-emails-going-to-spam-settings-1[envelope]"
                                                                                           value="envelope"
							<?php checked( 'envelope', $this->options['envelope'] ); ?>>
						<?php _e( 'Tick to set the From to the same as Envelope (above) recommended', 'stop-wp-emails-going-to-spam' ); ?>
                    </label><br>
                    <label for="stop-wp-emails-going-to-spam-settings-1[envelope]"><input type="radio"
                                                                                           name="stop-wp-emails-going-to-spam-settings-1[envelope]"
                                                                                           id="stop-wp-emails-going-to-spam-settings-1[envelope]"
                                                                                           value="from"
							<?php checked( 'from', $this->options['envelope'] ); ?>>
						<?php _e( 'Tick to set the Envelope to the From, not recommended unless all your forms use a From address of your domain, however the SPF check below is ignored', 'stop-wp-emails-going-to-spam' ); ?>
                    </label><br>
                    <label for="stop-wp-emails-going-to-spam-settings-1[envelope]"><input type="radio"
                                                                                           name="stop-wp-emails-going-to-spam-settings-1[envelope]"
                                                                                           id="stop-wp-emails-going-to-spam-settings-1[envelope]"
                                                                                           value="none"
							<?php checked( 'none', $this->options['envelope'] ); ?>>
						<?php _e( 'Tick to leave the From address alone - this may raise warnings in email clients when different from Envelope, not generally recommended', 'stop-wp-emails-going-to-spam' ); ?>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'WordPress default mail address', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
                    <p>
                        <span class="description"><?php printf(__( 'WordPress default system messages come from an account WordPress &lt;wordpress@%s&gt;  you can control that with the following settings', 'stop-wp-emails-going-to-spam' ),$this->domain); ?>
            </span>
                    </p>
                    <label for="stop-wp-emails-going-to-spam-settings-1[wordpresschoice]"><input type="radio"
                                                                                           name="stop-wp-emails-going-to-spam-settings-1[wordpresschoice]"
                                                                                           id="stop-wp-emails-going-to-spam-settings-1[wordpresschoice]"
                                                                                           value="envelope"
				            <?php checked( 'envelope', $this->options['wordpresschoice'] ); ?>>
			            <?php _e( 'Tick to set the WP default to the same as the email set above - recommended', 'stop-wp-emails-going-to-spam' ); ?>
                    </label><br>
                    <label for="stop-wp-emails-going-to-spam-settings-1[wordpresschoice]"><input type="radio"
                                                                                           name="stop-wp-emails-going-to-spam-settings-1[wordpresschoice]"
                                                                                           id="stop-wp-emails-going-to-spam-settings-1[wordpresschoice]"
                                                                                           value="custom"
				            <?php checked( 'custom', $this->options['wordpresschoice'] ); ?>>
                        <input type="text"
                               style="text-align: right"
                               class="medium-text"
                               name="stop-wp-emails-going-to-spam-settings-1[wordpressemail]"
                               id="stop-wp-emails-going-to-spam-settings-1[wordpressemail]"
                               value="<?php echo $this->options['wordpressemail'] ?>">@<?php echo $this->domain; ?><br>
			            <?php _e( 'Tick and set an email name on your domain for the default email', 'stop-wp-emails-going-to-spam' ); ?>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'WordPress default name', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
                    <input type="text"
                           class="medium-text"
                           name="stop-wp-emails-going-to-spam-settings-1[wordpressname]"
                           id="stop-wp-emails-going-to-spam-settings-1[wordpressname]"
                           value="<?php echo $this->options['wordpressname'] ?>">
                    <p>
                        <span class="description"><?php printf( __( 'You can change the display name associated with the default WordPress email, this is cosmetic only', 'stop-wp-emails-going-to-spam' ), $this->domain ); ?></span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function sanitize_settings_1( $settings ) {
	    $err=false;
		if ( ! isset( $settings['email'] ) ) {
			$settings['email'] = 'admin';  // always set checkboxes of they dont exist
		}
		if ( 'domain' == $settings['email'] ) {

			if ( ! is_email( $settings['emailname'] . '@' . $this->domain )  ) {
			    $err[] = __( 'Invalid email for Envelope', 'stop-wp-emails-going-to-spam' );
			}
		}

		if ( ! isset( $settings['wordpresschoice'] ) ) {
		    $settings['wordpresschoice'] = 'envelope';
		}
		if ( 'custom' == $settings['wordpresschoice'] ) {

			if (  ! is_email( $settings['wordpressemail'] . '@' . $this->domain )  ) {
				$err[] = __( 'Invalid email for WordPress default', 'stop-wp-emails-going-to-spam' );
			}
        }

        if ( $err ) {
	        add_settings_error(
		        'pses1',
		        'pses1',
		        implode('<br>',$err),
		        'error'
	        );
	        return $this->options;
        }


		return $settings;
	}

	public function sanitize_settings_2( $settings ) {

		return $settings;
	}


	public function meta_box_2() {
		$ip      = $_SERVER['SERVER_ADDR'];
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$ip4 = true;
		} else {
			$ip4 = false;
		}
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
			$ip6 = true;
		} else {
			$ip6 = false;
		}
		if ( 'admin' == $this->options['email'] ) {
			$domain = substr( strrchr( get_bloginfo( 'admin_email' ), "@" ), 1 );
		} else {
			$domain = $this->domain;
		}

		$dns = @dns_get_record( $domain, DNS_ALL );
		$spf = false;
		if ( $dns ) {
			foreach ( $dns as $dnstxt ) {
				if ( 'TXT' == $dnstxt['type'] ) {
					if ( isset( $dnstxt['txt'] ) ) {
						if ( 'v=spf' == substr( $dnstxt['txt'], 0, 5 ) ) {
							$spf = $dnstxt['txt'];
							break;
						}
					}
				}
			}
		}

		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Server Info', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
					<?php if ( $ip4 || $ip6 ) {
						?>
                        <p>Server IP
                            Address: <?php echo esc_html( $ip ); ?>  <?php echo ( $ip4 ) ? __( 'IPv4', 'stop-wp-emails-going-to-spam' ) : __( 'IPv6', 'stop-wp-emails-going-to-spam' ); ?></p>
						<?php
					} else {
						?>
                        <p><?php _e( '<p class="notice notice-error">Cannot identify a valid IP address - you may want to check with your hosting company</p>', 'stop-wp-emails-going-to-spam' ); ?></p>
						<?php
					}
					?>
                </td>
            </tr>
            <tr>
                <th scope="row" class="alternate"><?php _e( 'SPF Record', 'stop-wp-emails-going-to-spam' ); ?></th>
                <td>
					<?php
					if ( ! $dns ) {
						_e( '<p class="notice notice-error">Cannot get DNS records - refresh this page - if you still get this message after a few refreshes you may want to check your domain DNS control panel</p>', 'stop-wp-emails-going-to-spam' );
					} else {
						if ( false == $spf ) {
							printf( __( '<p class="notice notice-error">No SPF record found for %s, the following SPF record is recommended', 'stop-wp-emails-going-to-spam' ), $domain );
							if ( $ip4 || $ip6 ) {
								printf( ' v=spf1 +a +mx %s:%s ~all', ( $ip4 ) ? 'ip4' : 'ip6', esc_html( $ip ) );
							} else {
								echo 'v=spf1 +a +mx ~all';
							}
							echo '</p>';
						} else {
							printf( __( 'Current record SPF record for %s: <br><strong>%s</strong><br><br>', 'stop-wp-emails-going-to-spam' ), $domain, $spf );
							if ( strpos( $spf, $ip ) ) {
								_e( '<p class="notice notice-success">Good!, this contains your server IP address</p>', 'stop-wp-emails-going-to-spam' );
							} else {
								printf( __( '<p class="notice notice-warning">Recommend you add +%s:%s to your SPF record</p>', 'stop-wp-emails-going-to-spam' ), ( $ip4 ) ? 'ip4' : 'ip6', esc_html( $ip ) );
							}

						}


					}

					?>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}



}

