<?php
/*
Plugin Name: WordPress Database Reset
Plugin URI: https://github.com/chrisberthe/wordpress-database-reset
Description: A plugin that allows you to skip the 5 minute installation and reset WordPress's database back to its original state.
Version: 3.0.2
Author: Chris Berthe
Author URI: https://github.com/chrisberthe
License: GNU General Public License
Text-domain: wp-reset
*/

define( 'DB_RESET_VERSION', '3.0.2' );
define( 'DB_RESET_PATH', dirname( __FILE__ ) );
define( 'DB_RESET_NAME', basename( DB_RESET_PATH ) );
define( 'AUTOLOADER', DB_RESET_PATH . '/lib/class-plugin-autoloader.php' );

require_once( DB_RESET_PATH . '/lib/helpers.php' );

register_activation_hook( __FILE__, 'db_reset_activate' );

load_plugin_textdomain( 'wordpress-database-reset', false, DB_RESET_NAME . '/languages/' );

if ( file_exists( AUTOLOADER ) ) {
  require_once( AUTOLOADER );
  new Plugin_Autoloader( DB_RESET_PATH );

  add_action(
    'wp_loaded',
    array ( new DB_Reset_Manager( DB_RESET_VERSION ), 'run' )
  );
}

if ( is_command_line() ) {
  require_once( __DIR__ . '/class-db-reset-command.php' );
}
