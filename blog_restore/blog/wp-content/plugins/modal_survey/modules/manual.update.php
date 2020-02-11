<?php
defined( 'ABSPATH' ) OR exit;
if ( ! class_exists( 'manual_plugin_updater' ) ) {
	class manual_plugin_updater {
		protected static $instance = null;
		var $pluginname = false;
		var $newpluginname = false;
		var $plugindatas = array();
		var $newplugindatas = array();
		var $uploaded = array();
		var $action = "";
		var $temp_path = "";
		var $error = false;
		var $removedfiles = array();
		var $nonce = "";

		/**
		 * Construct the plugin object
		 */
		private function __construct( $pluginname, $newpluginname, $removedfiles = array(), $classname = "" ) {
			wp_enqueue_style( 'manual-updater-style', plugins_url( 'manual.update.css', __FILE__ ) );
			$this->pluginname = $pluginname;
			$this->newpluginname = $newpluginname;
			$this->temp_path = plugin_dir_path( __FILE__ ) . "/mpu-temp";
			$this->removedfiles = $removedfiles;
			$this->classname = $classname;
			$all_plugins = get_plugins();
			foreach( $all_plugins as $key=>$ap ) {
				if ( $pluginname == $key ) {
					$this->plugindatas = $ap;
					break;
				}
			}
			if ( isset( $_REQUEST[ 'manual_plugin_updater' ] ) ) {
				$this->action = sanitize_text_field( $_REQUEST[ 'manual_plugin_updater' ] );
			}
			if ( isset( $_REQUEST[ 'nonce' ] ) ) {
				$this->nonce = sanitize_text_field( $_REQUEST[ 'nonce' ] );
			}
			switch ( $this->action ) {
				case 'upload':
					if ( ! wp_verify_nonce( $this->nonce, 'manual-plugin-updater-upload' ) ) $this->throw_error(__( 'Security check failed, Update process terminated.', 'manual_plugin_updater' ));
					break;
				case 'remove':
					if ( ! wp_verify_nonce( $this->nonce, 'manual-plugin-updater-remove' ) ) $this->throw_error(__( 'Security check failed, Update process terminated.', 'manual_plugin_updater' ),$this->temp_path);
					break;
				case 'update':
					if ( ! wp_verify_nonce( $this->nonce, 'manual-plugin-updater-update' ) ) $this->throw_error(__( 'Security check failed, Update process terminated.', 'manual_plugin_updater' ),$this->temp_path);
					break;
			}
			if ( ! $this->error ) {
				if ( $this->action == "upload" ) {
					if ( ! empty( $_FILES[ 'pluginupdate_file' ][ 'name' ] ) ) {
						$this->uploaded = $this->upload_zip( $_FILES[ 'pluginupdate_file' ] );
					}
					else {
						$this->throw_error( __( "File doesn't exists or damaged.", 'manual_plugin_updater' ) );
					}
					if ( isset( $this->uploaded[ 'error' ] ) ) {
						$this->throw_error( $this->uploaded[ 'error' ], 'manual_plugin_updater' );
					}
					if ( ! $this->error ) {
						print( '<p>' . __( 'ZIP Uploaded...', 'manual_plugin_updater' ) . '</p>');
						$this->unzip_update( $this->uploaded );
					}
				}
				elseif ($this->action=="remove") $this->remove();
				elseif ($this->action=="update") $this->update();
				else {
				$this->update_screen();
				}
			}
		}
		
		public static function getInstance( $pluginname, $newpluginname, $removedfiles = array(), $classname ) {
			if ( ! isset( $instance ) ) {
				$instance = new manual_plugin_updater( $pluginname, $newpluginname, $removedfiles, $classname );
			}
		return $instance;
		}
		
		
		private function unzip_update($zip)
		{
			WP_Filesystem();
			if(!file_exists($this->temp_path))
			{
				if (!mkdir($this->temp_path, 0777, true)) 
				{
					$this->throw_error(__( 'Failed to create folders...', 'manual_plugin_updater' ));
				}
			}
			$unzipfile = unzip_file( $zip['file'], $this->temp_path);
			   if ( $unzipfile ) {
				@unlink( $zip[ 'file' ] );
				print( '<p>' . __( 'ZIP unpacked, collecting datas...', 'manual_plugin_updater' ) . '</p>' );
				$newversion_path = plugin_dir_path( __FILE__ ) . "mpu-temp/" . $this->newpluginname;
					if ( file_exists( $newversion_path ) ) {
						$this->newplugindatas = get_plugin_data( $newversion_path );
					}
					if ( ! isset( $this->newplugindatas[ 'Name' ] ) && ! $this->error ) {
					   $this->throw_error( __( 'ZIP file is not compatible with this plugin or it is not a valid WordPress Plugin', 'manual_plugin_updater' ), $this->temp_path );
					}
				    if ( ! $this->error ) {
						if ( $this->plugindatas[ 'Name' ] != $this->newplugindatas[ 'Name' ] ) {
							$this->throw_error( __( 'Plugin name doesn\'t match. Is this the same plugin?', 'manual_plugin_updater' ), $this->temp_path );
						}
					}
				   if ( ! $this->error ) {
						$newdir = explode( '/', $this->newpluginname );
						$output = "<div class='manual-plugin-updater-update-informations'>";
						$output .= "<p><strong>" . __( 'Current Version: ', 'manual_plugin_updater' ) . "</strong>" . $this->plugindatas[ 'Version' ] . "</p>";
						$output .= "<p><strong>" . __( 'Uploaded Version: ', 'manual_plugin_updater' ) . "</strong>" . $this->newplugindatas[ 'Version' ] . "</p>";
						$output .= "</div>";
						$output .= "<div class='manual-plugin-updater-buttons'><form class='update_plugin_form' method='post'><input type='hidden' name='manual_plugin_updater' value='update'><input type='submit' class='button button-primary button-small' value='".__( 'START UPDATE', 'manual_plugin_updater' )."'><input type='hidden' name='nonce' value='".wp_create_nonce('manual-plugin-updater-update')."'></form><form class='update_plugin_form' method='post'><input type='hidden' name='manual_plugin_updater' value='remove'><input type='submit' class='button button-secondary button-small' value='".__( 'DO NOT UPDATE (Delete uploaded temp files)', 'manual_plugin_updater' )."'><input type='hidden' name='nonce' value='".wp_create_nonce('manual-plugin-updater-remove')."'></form></div>";
						if (file_exists(str_replace($newdir[1],'',$newversion_path).'changelog.txt')) 
						{
							$changelog = file_get_contents(str_replace($newdir[1],'',$newversion_path).'changelog.txt');
							$output .= "<div class='manual-plugin-updater-changelog'><h3>".__( 'Changelog', 'manual_plugin_updater' )."</h3>".nl2br($changelog)."</div>";
						}
						return print($output);
					}
			   } else $this->throw_error(__( 'There was an error unzipping the file. Is it a valid ZIP file?', 'manual_plugin_updater' ),$this->temp_path);
		}
		
		private function throw_error($message, $deletedir = false)
		{
			$this->update_screen();
			print( $message );
			if ( $deletedir != false ) {
				$this->erasedir( $deletedir );
			}
			$this->error = $message;
		}
		
		private function upload_zip($zip)
		{
			return wp_handle_upload( $zip, array( 'test_form' => false ));
		}
		
		private function remove() {
			$this->erasedir( $this->temp_path );
			$this->update_screen();
			print( "<p>" . __( 'Update Files has been successfully removed.', 'manual_plugin_updater' ) . "</p>" );
		}
		
		private function update() {
			$newdir = explode( '/', $this->pluginname );
			print("<p>" . __( 'Copying files...', 'manual_plugin_updater' ) . "</p>");
			if ( $this->copy_files( $this->temp_path . '/' . $newdir[ 0 ], WP_PLUGIN_DIR . '/' . $newdir[ 0 ] ) ) {
				$this->erasedir($this->temp_path);
			}
			print( "<p>" . __( 'Removing temp folder...', 'manual_plugin_updater' ) . "</p>");
			if ( ! empty( $this->removedfiles ) ) {
				$this->delete_files( WP_PLUGIN_DIR . '/' . $newdir[ 0 ], $this->removedfiles );
			}
			print( "<p>" . __( 'Plugin files has been successfully updated...', 'manual_plugin_updater' ) . "</p>" );
			if ( $this->classname == "" ) {
				$this->classname = $newdir[ 0 ];
			}
			$class = new $this->classname;
			if ( class_exists( $this->classname ) ) {
				$class->activate();
				if ( function_exists( $class->update_modal_survey_db() ) ) {
					$class->update_modal_survey_db();
				}
				print( "<p>" . __( 'Plugin Activation Initialized Successfully...', 'manual_plugin_updater' ) . "</p>");
			}
		}
		
		private function update_screen() {
			print('<p>' . __( 'After uploaded the ZIP file you can decide to continue the update process or discard the changes.', 'manual_plugin_updater' ) . '</p>
			<form class="update_plugin_form" enctype="multipart/form-data" method="post">		
			<input type="hidden" name="manual_plugin_updater" value="upload">
			<input type="hidden" name="nonce" value="' . wp_create_nonce( "manual-plugin-updater-upload" ) . '">		
			' . __( 'Browse the new version ZIP file:', 'manual_plugin_updater' ) . ' <input type="file" name="pluginupdate_file">
			<input type="submit" class="button button-secondary button-small" class="update_plugin" value="' . __( 'UPLOAD', 'manual_plugin_updater' ) . '">
			</form>');
		}
		
		private function copy_files( $src, $dst, $exception = array() ) {
			$dir = opendir( $src ); 
			@mkdir( $dst ); 
			while( false !== ( $file = readdir( $dir ) ) ) { 
				if ( ( $file != '.' ) && ( $file != '..' ) ) { 
					if ( is_dir( $src . '/' . $file ) ) { 
						$this->copy_files( $src . '/' . $file, $dst . '/' . $file ); 
					} 
					else { 
						copy( $src . '/' . $file, $dst . '/' . $file ); 
					} 
				} 
			} 
			closedir( $dir );
			return true;
		}

		private function delete_files( $src, $targets = array() ) {
			foreach ( $targets as $trg ) {
				if ( file_exists( $src . '/' . $trg ) ) {
					if ( is_dir( $src . '/' . $trg ) ) {
						$this->erasedir( $src . '/' . $trg );
					}
					else {
						@unlink( $src . '/' . $trg );
					}
				}
			}
		}

		private function erasedir( $dir, $exception = array() ) {
		  if ( is_dir( $dir ) ) {
			if ( ! in_array( $dir, $exception ) ) {
				$objects = scandir( $dir );
				foreach ( $objects as $object ) {
				  if ( $object != "." && $object != ".." ) {
					if ( filetype( $dir . "/" . $object ) == "dir" ) {
					   $this->erasedir( $dir . "/" . $object ); 
					}
					else {
						if ( file_exists( $dir . "/" . $object ) ) {
							@unlink( $dir . "/" . $object );
						}
					}
				  }
				}
				reset( $objects );
				if ( file_exists( $dir ) ) {
					@rmdir( $dir );
				}
			}
		  }
		}	
	}
}
?>