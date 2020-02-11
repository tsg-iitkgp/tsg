<?php
/**
 * Templates Class
 *
 * @package TotalPoll/Classes/Templates
 * @since   3.0.0
 */
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Templates' ) ) :

	class TP_Admin_Templates {

		public $categories = array(
			'all' => array(
				'name'  => 'All',
				'count' => 0,
			),
		);

		/**
		 * @var array Available Templates.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $available = array();

		/**
		 * @var array Available Presets.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $presets = array();

		/**
		 * @var array Extension file headers
		 * @access private
		 * @since  3.0.0
		 */
		private $headers = array(
			'name'        => 'Template Name',
			'website'     => 'Template URI',
			'version'     => 'Version',
			'requires'    => 'Requires',
			'description' => 'Description',
			'author'      => 'Author',
			'authorURI'   => 'Author URI',
			'category'    => 'Category',
			'type'        => 'Type',
		);

		public function __construct() {
		}

		function load() {
			// Get activated templates
			return $this->activated = array_merge( get_option( 'totalpoll_templates', array() ), array( 'default' ) );
		}

		function fetch() {
			// Fetch templates directory
			$templates = array_merge( glob( TP_PATH . 'templates/*', GLOB_ONLYDIR ), glob( WP_CONTENT_DIR . '/uploads/totalpoll/templates/*', GLOB_ONLYDIR ) );
			if ( ! empty( $templates ) ):
				$this->load();
			endif;
			// Iterate templates folders
			foreach ( $templates as $template ):
				// Get directory name
				$slug = basename( $template );
				// Extension must-have file "template.php" path
				$file = "$template/template.php";
				// Check existence
				if ( file_exists( $file ) ):
					// Extensions counter
					$this->categories['all']['count'] ++;
					// Get headers
					$headers = get_file_data( $file, $this->headers );
					// Attach to "All" category if the template doesn't provide a category in headers
					if ( empty( $headers['category'] ) ):
						$headers['category'] = __( 'All', TP_TD );
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
					$headers['activated'] = $headers['compatible'] && ( in_array( $slug, $this->activated ) || $headers['type'] === 'built-in' );
					// Extension base name (we use in the edit link
					$headers['basename'] = plugin_basename( "$template/template.php" );
					// Ladies and gentlemen, this is our template.
					$this->available[ $slug ] = $headers;

				endif;
			endforeach;

			// Return available templates
			return $this->available;
		}

		function install( $url = false ) {
			TotalPoll::instance( 'admin/installer', array( 'templates/', 'template', 'tp-templates', $url ) );
		}

		function uninstall( $templates ) {
			global $wp_filesystem;

			if ( empty( $templates ) ) {
				return false;
			}

			$checked = array();
			foreach ( $templates as $template ) {
				$checked[] = 'checked[]=' . $template;
			}

			ob_start();
			$url = wp_nonce_url( 'edit.php?post_type=poll&page=tp-templates&action=delete&' . implode( '&', $checked ), 'edit-templates' );
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

			$errors = array();

			foreach ( $templates as $template_dir ) {

				$alt_path = WP_CONTENT_DIR . "/uploads/totalpoll/templates/{$template_dir}";

				$deleted = $wp_filesystem->delete( TP_PATH . "templates/{$template_dir}", true );

				if ( $wp_filesystem->is_dir( $alt_path ) ) {
					$deleted = $wp_filesystem->delete( $alt_path, true );
				}

				if ( ! $deleted ) {
					$deleted = $wp_filesystem->delete( WP_CONTENT_DIR . "/uploads/totalpoll/templates/{$template_dir}", true );
				}

				if ( ! $deleted ) {
					$errors[] = $template_dir;
					continue;
				}
			}

			if ( ! empty( $errors ) ) {
				return new WP_Error( 'could_not_remove_template', sprintf( __( 'Could not fully remove the template(s) %s.' ), implode( ', ', $errors ) ) );
			}

			return true;
		}

		function activate( $templates ) {

			$this->activated = array_unique( array_merge( $this->activated, (array) $templates ) );

			return update_option( 'totalpoll_templates', $this->activated );
		}

		function deactivate( $templates ) {
			$this->activated = array_diff( $this->activated, (array) $templates );

			return update_option( 'totalpoll_templates', $this->activated );
		}

	}


endif;