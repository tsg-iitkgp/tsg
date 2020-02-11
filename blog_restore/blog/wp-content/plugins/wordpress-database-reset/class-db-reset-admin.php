<?php

if ( ! class_exists( 'DB_Reset_Admin' ) ) :

  class DB_Reset_Admin {

    private $code;
    private $notice_error;
    private $notice_success;
    private $request;
    private $resetter;
    private $user;
    private $version;
    private $wp_tables;

    public function __construct( $version ) {
      $this->resetter = new DB_Resetter();
      $this->version = $version;

      $this->set_request( $_REQUEST );
      $this->set_view_variables();
    }

    private function set_request( array $request ) {
      $this->request = $request;
    }

    private function set_view_variables() {
      $this->set_code();
      $this->set_user();
      $this->set_wp_tables();
    }

    private function set_code() {
      $this->code = $this->generate_code();
    }

    private function set_user() {
      $this->user = $this->resetter->get_user();
    }

    private function set_wp_tables() {
      $this->wp_tables = $this->resetter->get_wp_tables();
    }

    private function generate_code( $length = 5 ) {
      return substr( md5( time() ), 1, $length );
    }

    public function run() {
      add_action( 'admin_init', array( $this, 'reset' ) );
      add_action( 'admin_menu', array( $this, 'add_tools_menu' ) );
    }

    public function reset() {
      if ( $this->form_is_safe_to_submit() ) {
        try {
          $this->resetter->set_reactivate( $this->request[ 'db-reset-reactivate-theme-data' ] );
          $this->resetter->reset( $this->request[ 'db-reset-tables' ] );
          $this->handle_after_reset();
        } catch ( Exception $e ) {
          $this->notice_error = $e->getMessage();
        }
      }
    }

    private function form_is_safe_to_submit() {
      return isset( $this->request['db-reset-code-confirm'] ) &&
             $this->assert_request_variables_not_empty() &&
             $this->assert_correct_code();
    }

    private function handle_after_reset() {
      if ( empty( $this->request[ 'db-reset-reactivate-theme-data' ] ) ) {
        wp_redirect( admin_url() );
        exit;
      }

      $this->notice_success = __( 'The selected tables were reset', 'wordpress-database-reset' );
    }

    private function assert_request_variables_not_empty() {
      $this->set_empty_request_key( 'db-reset-tables', array() );
      $this->set_empty_request_key( 'db-reset-reactivate-theme-data', false );

      return true;
    }

    private function set_empty_request_key( $key, $default ) {
      if ( ! array_key_exists( $key, $this->request ) ) {
        $this->request[ $key ] = $default;
      }
    }

    private function assert_correct_code() {
      if ( $this->request['db-reset-code'] !==
           $this->request['db-reset-code-confirm'] ) {
        $this->notice_error = __( 'You entered the wrong security code', 'wordpress-database-reset' );
        return false;
      }

      return true;
    }

    public function add_tools_menu() {
      $plugin_page = add_management_page(
        __( 'Database Reset', 'wordpress-database-reset' ),
        __( 'Database Reset', 'wordpress-database-reset' ),
        'manage_options',
        'database-reset',
        array( $this, 'render' )
      );

      add_action( 'load-' . $plugin_page, array( $this, 'load_assets' ) );
    }

    public function render() {
      require_once( DB_RESET_PATH . '/views/index.php' );
    }

    public function load_assets() {
      $this->load_stylesheets();
      $this->load_javascript();
    }

    private function load_stylesheets() {
      wp_enqueue_style(
        'bsmselect',
        plugins_url( 'assets/css/bsmselect.css', __FILE__ ),
        array(),
        $this->version
      );

      wp_enqueue_style(
        'database-reset',
        plugins_url( 'assets/css/database-reset.css', __FILE__ ),
        array('bsmselect'),
        $this->version
      );
    }

    private function load_javascript() {
      wp_enqueue_script(
        'bsmselect',
        plugins_url( 'assets/js/bsmselect.js', __FILE__ ),
        array( 'jquery' ),
        $this->version,
        true
      );

      wp_enqueue_script(
        'bsmselect-compatibility',
        plugins_url( 'assets/js/bsmselect.compatibility.js', __FILE__ ),
        array( 'bsmselect' ),
        $this->version,
        true
      );

      wp_enqueue_script(
        'database-reset',
        plugins_url( 'assets/js/database-reset.js', __FILE__ ),
        array( 'bsmselect', 'bsmselect-compatibility' ),
        $this->version,
        true
      );

      wp_localize_script(
        'database-reset',
        'dbReset',
        $this->load_javascript_vars()
      );
    }

    private function load_javascript_vars() {
      return array(
        'confirmAlert' => __( 'Are you sure you want to continue?', 'wordpress-database-reset' ),
        'selectTable' => __( 'Select Tables', 'wordpress-database-reset' )
      );
    }

  }

endif;
