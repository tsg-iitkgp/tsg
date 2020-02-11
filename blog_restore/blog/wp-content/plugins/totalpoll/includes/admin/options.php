<?php
/**
 * Template Admin Options Class
 *
 * @package TotalPoll/Classes/TP_Admin_Options
 * @since   3.0.0
 */
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Options' ) ) :

	class TP_Admin_Options {

		public $poll;
		public $defaults;
		public $default_settings;
		public $options;

		public function __construct() {
			$this->poll             = TotalPoll::poll( 0 );
			$this->default_settings = get_option( 'totalpoll_default_settings', array() );
			$this->options          = get_option( 'totalpoll_options', array() );


			$this->defaults = TotalPoll::instance( 'admin/bootstrap' )->get_default_settings();

			if ( empty( $this->default_settings ) ):
				$this->defaults['limitations']['cookies']['enabled'] = true;
				$this->defaults['limitations']['direct']['enabled']  = true;
				$this->defaults['logs']['enabled']                   = true;
				$this->defaults['results']['format']['votes']        = true;
				$this->defaults['results']['format']['percentages']  = true;
			else:
				$this->defaults = TotalPoll::instance( 'helpers' )->parse_args( $this->default_settings, $this->defaults );
			endif;
		}

		public function header() {
			include TP_PATH . 'includes/admin/editor/header.php';
		}

		public function footer() {
			include TP_PATH . 'includes/admin/editor/footer.php';
		}

		public function options() {
			include TP_PATH . 'includes/admin/partials/options-panel.php';
		}

		public function settings() {
			$context = 'options';
			include TP_PATH . 'includes/admin/editor/settings.php';
		}

		public function save() {
			$this->defaults = TotalPoll::instance( 'helpers' )->parse_args(
				$_POST['totalpoll']['settings'],
				array(
					'limitations'   => array(),
					'results'       => array(),
					'choices'       => array(),
					'design'        => array(),
					'fields'        => array(),
					'screens'       => array(),
					'logs'          => array(),
					'notifications' => array(),
				)
			);

			$this->options = $_POST['totalpoll']['options'];

			// Expressions
			foreach ( $this->options['expressions'] as $expression => $expression_content ):
				if ( empty( $expression_content['translations'][0] ) ):
					unset( $this->options['expressions'][ $expression ] );
				endif;
			endforeach;

			// Fields
			if ( isset( $this->defaults['fields'] ) ):
				foreach ( $this->defaults['fields'] as $index => $field ):
					$this->defaults['fields'][ $index ]['name'] = sanitize_title_with_dashes( $field['name'] );
					if ( empty( $this->defaults['fields'][ $index ]['name'] ) ):
						$this->defaults['fields'][ $index ]['name'] = uniqid( 'untitled_' );
					endif;
				endforeach;
			endif;

			update_option( 'totalpoll_default_settings', $this->defaults );
			update_option( 'totalpoll_options', $this->options );

		}
	}

endif;
