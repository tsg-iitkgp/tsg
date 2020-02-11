<?php
/**
 * Extensions Class
 *
 * @package TotalPoll/Classes/Extensions
 * @since   3.0.0
 */
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Extensions' ) ) :

	class TP_Admin_Extensions {

		/**
		 * Categories.
		 * @var array Exntesion categories
		 */
		public $categories = array(
			'all' => array(
				'name'  => 'All',
				'count' => 0,
			),
		);

		/**
		 * @var array Available Extensions.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $available = array();

		/**
		 * @var array Activated Extensions.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $activated = array();

		/**
		 * @var array Extension file headers
		 * @access private
		 * @since  3.0.0
		 */
		private $headers = array(
			'name'        => 'Extension Name',
			'website'     => 'Extension URI',
			'version'     => 'Version',
			'requires'    => 'Requires',
			'description' => 'Description',
			'author'      => 'Author',
			'authorURI'   => 'Author URI',
			'category'    => 'Category',
		);

		public function __construct() {
		}

		function must_use() {

		}

		function load() {
			// Get activated extensions
			return $this->activated = get_option( 'totalpoll_extensions', array() );
		}

		function fetch() {
			// Fetch extensions directory
			$extensions = array_merge( glob( TP_PATH . 'extensions/*', GLOB_ONLYDIR ), glob( WP_CONTENT_DIR . '/uploads/totalpoll/extensions/*', GLOB_ONLYDIR ) );
			// Iterate extensions folders
			foreach ( $extensions as $extension ):
				// Get directory name
				$slug = basename( $extension );
				// Extension must-have file "extension.php" path
				$file = $extension . '/extension.php';
				// Check existence
				if ( file_exists( $file ) ):
					// Extensions counter
					$this->categories['all']['count'] ++;
					// Get headers
					$headers = get_file_data( $file, $this->headers );
					// Attach to "All" category if the extension doesn't provide a category in headers
					if ( empty( $headers['category'] ) ):
						$headers['category'] = 'All';
					else:
						// Slugify the name of category
						$headers['category-slug'] = sanitize_title_with_dashes( $headers['category'] );
						// +1 if exists
						if ( isset( $this->categories[ $headers['category-slug'] ] ) ):
							$this->categories[ $headers['category-slug'] ] ++;
						// Create it otherwise
						else:
							$this->categories[ $headers['category-slug'] ] = array(
								'name'  => $headers['category'],
								'count' => 1,
							);
						endif;
					endif;
					// Compatibility check
					$headers['compatible'] = version_compare( $headers['requires'], TP_VERSION, '<=' );
					// Activation check
					$headers['activated'] = $headers['compatible'] && in_array( $slug, $this->activated );
					// Extension base name (we use in the edit link
					$headers['basename'] = plugin_basename( $extension . '/extension.php' );
					// Ladies and gentlemen, this is our extension.
					$this->available[ $slug ] = $headers;

				endif;
			endforeach;

			// Return available extensions
			return $this->available;
		}

		function install( $url = false ) {
			TotalPoll::instance( 'admin/installer', array( 'extensions/', 'extension', 'tp-extensions', $url ) );
		}

		function uninstall( $extensions ) {
			global $wp_filesystem;

			if ( empty( $extensions ) ) {
				return false;
			}

			$checked = array();
			foreach ( $extensions as $extension ) {
				$checked[] = 'checked[]=' . $extension;
			}

			ob_start();
			$url = wp_nonce_url( 'edit.php?post_type=poll&page=tp-extensions&action=delete&' . implode( '&', $checked ), 'edit-extensions' );
			if ( false === ( $credentials = request_filesystem_credentials( $url ) ) ) {
				$data = ob_get_contents();
				ob_end_clean();
				if ( ! empty( $data ) ) {
					include_once( ABSPATH . 'wp-admin/admin-header.php' );
					echo $data;
					include( ABSPATH . 'wp-admin/admin-footer.php' );
					exit;
				}

				return;
			}

			if ( ! WP_Filesystem( $credentials ) ):
				request_filesystem_credentials( $url, '', true ); //Failed to connect, Error and request again
				$data = ob_get_contents();
				ob_end_clean();
				if ( ! empty( $data ) ) {
					include_once( ABSPATH . 'wp-admin/admin-header.php' );
					echo $data;
					include( ABSPATH . 'wp-admin/admin-footer.php' );
					exit;
				}

				return;
			endif;

			if ( ! is_object( $wp_filesystem ) ):
				return new WP_Error( 'fs_unavailable', __( 'Could not access filesystem.' ) );
			endif;

			if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ):
				return new WP_Error( 'fs_error', __( 'Filesystem error.' ), $wp_filesystem->errors );
			endif;

			// Get the base extension folder.

			$errors = array();

			foreach ( $extensions as $extension_dir ) {

				$alt_path = WP_CONTENT_DIR . "/uploads/totalpoll/extensions/{$extension_dir}";

				$deleted = $wp_filesystem->delete( TP_PATH . "extensions/$extension_dir", true );

				if ( $wp_filesystem->is_dir( $alt_path ) ) {
					$deleted = $wp_filesystem->delete( $alt_path, true );
				}

				if ( ! $deleted ) {
					$errors[] = $extension_dir;
					continue;
				}
			}

			if ( ! empty( $errors ) ) {
				return new WP_Error( 'could_not_remove_extension', sprintf( __( 'Could not fully remove the extension(s) %s.' ), implode( ', ', $errors ) ) );
			}

			return true;
		}

		function activate( $extensions ) {

			$this->activated = array_unique( array_merge( $this->activated, (array) $extensions ) );

			return update_option( 'totalpoll_extensions', $this->activated );
		}

		function deactivate( $extensions ) {
			$this->activated = array_diff( $this->activated, (array) $extensions );

			return update_option( 'totalpoll_extensions', $this->activated );
		}

	}


endif;