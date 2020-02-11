<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

global $wp_version;

require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
if ( version_compare( $wp_version, '3.7', '>' ) ):
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader-skins.php' );
endif;

/**
 * Extensions & Templates Installer.
 *
 * @since   2.0.0
 * @package TotalPoll\Installer
 */
if ( ! class_exists( 'TP_Admin_Installer' ) && class_exists( 'Theme_Upgrader' ) && class_exists( 'File_Upload_Upgrader' ) ):

	class TP_Admin_Installer extends Theme_Upgrader {

		/**
		 * Type of installation (extension or template).
		 *
		 * @since  2.0.0
		 * @access public
		 * @type string
		 */
		public $type;

		/**
		 * Initializing.
		 *
		 * @param object $skin Skin object
		 * @param string $type Installation type
		 *
		 * @return void
		 */
		public function __construct( $destination, $type = 'template', $page = 'tp-templates', $url = false ) {

			if ( ! is_dir( WP_CONTENT_DIR . '/uploads/totalpoll/' ) ) {
				@mkdir( WP_CONTENT_DIR . '/uploads/' );
				@mkdir( WP_CONTENT_DIR . '/uploads/totalpoll/' );
				@mkdir( WP_CONTENT_DIR . '/uploads/totalpoll/extensions' );
				@mkdir( WP_CONTENT_DIR . '/uploads/totalpoll/templates' );
			}

			$this->destination = WP_CONTENT_DIR . "/uploads/totalpoll/{$destination}";
			$this->type        = $type;

			// File upgrader
			if ( empty( $url ) ):
				$archive = new File_Upload_Upgrader( "{$type}zip", 'package' );
			endif;

			// Skin
			$this->skin = new TP_Installer_Skin( array(
				'title' => __( 'Upload package', TP_TD ),
				'type'  => 'upload',
				'url'   => esc_url_raw( add_query_arg( array( 'package' => ! empty( $url ) ? $url : $archive->id ), "edit.php?post_type=poll&page={$page}" ) ),
				'nonce' => "install-{$type}",
			) );

			// Capture results
			$result = $this->install( ! empty( $url ) ? $url : $archive->package );

			// Check if there is an error
			if ( ( $result || is_wp_error( $result ) ) && isset( $archive ) ) {
				$archive->cleanup();
			} // Remove the uploaded file

			exit;
		}

		/**
		 * Initialize the upgrade strings.
		 *
		 * @since  2.8.0
		 * @access public
		 */
		public function upgrade_strings() {
			$this->strings['up_to_date']           = __( 'The package is at the latest version.', TP_TD );
			$this->strings['no_package']           = __( 'Update package not available.', TP_TD );
			$this->strings['downloading_package']  = __( 'Downloading update from <span class="code">%s</span>&#8230;', TP_TD );
			$this->strings['unpack_package']       = __( 'Unpacking the update&#8230;', TP_TD );
			$this->strings['remove_old']           = __( 'Removing the old version of the package&#8230;', TP_TD );
			$this->strings['remove_old_failed']    = __( 'Could not remove the old package.', TP_TD );
			$this->strings['process_bulk_success'] = __( 'Packages updated successfully.', TP_TD );
		}

		/**
		 * Installation strings.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function install_strings() {
			$this->strings['no_package']                      = __( 'Install package not available.', TP_TD );
			$this->strings['unpack_package']                  = __( 'Unpacking the package&#8230;', TP_TD );
			$this->strings['installing_package']              = __( 'Installing the package&#8230;', TP_TD );
			$this->strings['downloading_package']             = __( 'Downloading install package from <span class="code">%s</span>&#8230;' );
			$this->strings['no_files']                        = __( 'The package contains no files.', TP_TD );
			$this->strings['process_failed']                  = __( 'Package install failed.', TP_TD );
			$this->strings['process_success']                 = __( 'Package installed successfully.', TP_TD );
			$this->strings['process_success_specific']        = __( 'Successfully installed the package <strong>%1$s %2$s</strong>.', TP_TD );
			$this->strings['parent_template_prepare_install'] = __( 'Preparing to install <strong>%1$s %2$s</strong>&#8230;', TP_TD );
		}

		/**
		 * Install a template or an extension.
		 *
		 * @global array $wp_theme_directories Themes directories
		 *
		 * @param object $package              Uploaded package object
		 *
		 * @return \WP_Error|boolean
		 */
		public function install( $package, $args = array() ) {

			// A quick workaround to avoid implementing a modified version of install_package. (See class-wp-upgrader.php:402)
			global $wp_theme_directories;
			$_wp_theme_directories  = $wp_theme_directories;
			$wp_theme_directories[] = $this->destination;

			$this->init();
			$this->install_strings();
			$this->upgrade_strings();

			add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

			$this->run( array(
				'package'                     => $package,
				'destination'                 => $this->destination,
				'clear_destination'           => true,
				'abort_if_destination_exists' => false,
				'clear_working'               => true,
			) );

			remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );
			$wp_theme_directories = $_wp_theme_directories;

			if ( ! $this->result || is_wp_error( $this->result ) ) {
				return $this->result;
			}

			return true;
		}

		/**
		 * Check package validity.
		 *
		 * @global object $wp_filesystem WP Filesystem object
		 *
		 * @param object  $source        Path to uploaded package
		 *
		 * @return \WP_Error
		 */
		public function check_package( $source ) {
			global $wp_filesystem;

			if ( is_wp_error( $source ) ) {
				return $source;
			}

			// Check the folder contains a valid package
			$working_directory = str_replace( $wp_filesystem->wp_content_dir(), trailingslashit( WP_CONTENT_DIR ), $source );

			if ( ! is_dir( $working_directory ) ): // Sanity check, if the above fails, lets not prevent installation.
				return $source;
			endif;

			if ( $this->type == 'template' ):

				// A proper archive should have a template.php file in the single subdirectory
				if ( ! file_exists( $working_directory . 'template.php' ) ):
					return new WP_Error( 'incompatible_archive_template_no_style', $this->strings['incompatible_archive'],
						__( 'The template is missing the <code>template.php</code> stylesheet.', TP_TD ) );
				endif;

				$info = get_file_data( $working_directory . 'template.php', array( 'name' => 'Template Name', 'requires' => 'Requires' ) );

				if ( empty( $info['name'] ) ):
					return new WP_Error( 'incompatible_archive_template_no_name', $this->strings['incompatible_archive'],
						__( "The <code>template.php</code> doesn't contain a valid template header.", TP_TD ) );
				endif;

				// it must have at least an vote.php and results.php to be legit.
				foreach ( array( 'vote.php', 'results.php', 'hidden_results.php', 'header.php', 'footer.php', 'before_vote.php', 'after_vote.php' ) as $file ):
					if ( ! file_exists( $working_directory . $file ) ):
						return new WP_Error( "incompatible_archive_template_no_{$file}", $this->strings['incompatible_archive'],
							sprintf( __( 'The template is missing the <code>%s</code> file.', TP_TD ), $file ) );
					endif;
				endforeach;

				if ( empty( $info['requires'] ) ):
					return new WP_Error( 'incompatible_archive_template_no_required_version', $this->strings['incompatible_archive'],
						__( "The <code>template.php</code> file doesn't contain the minimum required version.", TP_TD ) );
				endif;

				if ( ! empty( $info['name'] ) && version_compare( $info['requires'], TP_VERSION, '>' ) ):
					return new WP_Error( 'incompatible_archive_template_incompatible_version', $this->strings['incompatible_archive'],
						sprintf( __( "This template requires TotalPoll version %s or higher", TP_TD ), $info['required'] ) );
				endif;

			elseif ( $this->type == 'extension' ):

				// A proper archive should have a extension.php file in the single subdirectory
				if ( ! file_exists( $working_directory . 'extension.php' ) ):
					return new WP_Error( 'incompatible_archive_no_extension_file', $this->strings['incompatible_archive'],
						__( 'The extension is missing the <code>extension.php</code> essential file.', TP_TD ) );
				endif;

				$info = get_file_data( $working_directory . 'extension.php', array( 'name' => 'Extension Name', 'requires' => 'Requires' ) );

				if ( empty( $info['name'] ) ):
					return new WP_Error( 'incompatible_archive_extension_no_name', $this->strings['incompatible_archive'],
						__( "The <code>extension.php</code> file doesn't contain a valid extension header.", TP_TD ) );
				endif;

				if ( empty( $info['requires'] ) ):
					return new WP_Error( 'incompatible_archive_extension_no_required_version', $this->strings['incompatible_archive'],
						__( "The <code>extension.php</code> file doesn't contain the minimum required version.", TP_TD ) );
				endif;

				if ( ! empty( $info['name'] ) && version_compare( $info['requires'], TP_VERSION, '>' ) ):
					return new WP_Error( 'incompatible_archive_extension_incompatible_version', $this->strings['incompatible_archive'],
						sprintf( __( "This extension requires TotalPoll version %s or higher", TP_TD ), $info['required'] ) );
				endif;

			endif;

			return $source;
		}

		public function current_after( $return, $theme ) {

		}

		public function bulk_upgrade( $language_updates = array(), $args = array() ) {

		}

		public function upgrade( $update = false, $args = array() ) {

		}

	}

endif;

if ( ! class_exists( 'TP_Installer_Skin' ) && class_exists( 'Theme_Installer_Skin' ) ):

	class TP_Installer_Skin extends Theme_Installer_Skin {

		/**
		 * Return URL.
		 * @since 2.0.0
		 * @return void
		 */
		public function after() {
			if ( $this->upgrader->type == 'template' ):
				$this->feedback( '<a href="' . self_admin_url( 'edit.php?post_type=poll&page=tp-templates' ) . '" target="_parent">' . __( 'Return to Templates',
						TP_TD ) . '</a>' );
			else:
				$this->feedback( '<a href="' . self_admin_url( 'edit.php?post_type=poll&page=tp-extensions' ) . '" target="_parent">' . __( 'Return to extensions',
						TP_TD ) . '</a>' );
			endif;
		}

	}

endif;