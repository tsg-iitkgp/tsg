<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Fields' ) ) :

	/**
	 * Fields Class
	 *
	 * @package TotalPoll/Classes/Fields
	 * @since   3.0.0
	 */
	class TP_Fields {

		/**
		 * @var object Poll object.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $poll = null;

		/**
		 * @var object Request object.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $request = null;

		/**
		 * @var array Array of fields (original and raw).
		 * @access protected
		 * @since  3.0.0
		 */
		protected $raw_fields = array();

		/**
		 * @var array Array of fields objects.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $fields = array();

		/**
		 * @var array Array posted fields.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $posted = array();

		/**
		 * @var array Errors items.
		 * @access protected
		 * @since  3.0.0
		 */
		public $bag = false;

		/**
		 * @var array Unique fields items.
		 * @access public
		 * @since  3.0.0
		 */
		public $unique_fields = array();

		/**
		 * Fields constructor.
		 *
		 * @param object $poll    Poll object.
		 * @param object $request Request object.
		 *
		 * @since 3.0.0
		 */
		public function __construct( $poll, $request ) {
			if ( $request instanceof TotalPoll::$classes['request']['class'] ):
				$this->request = $request;
				$this->posted  = isset( $_REQUEST['totalpoll']['fields'] ) ? $_REQUEST['totalpoll']['fields'] : array();
			endif;

			if ( $poll instanceof TotalPoll::$classes['poll']['class'] ):
				$this->poll       = $poll;
				$this->raw_fields = $this->poll->settings( 'fields' );
				$this->raw_fields = empty( $this->raw_fields ) ? array() : $this->raw_fields;

				foreach ( $this->raw_fields as $field ):
					if ( ! isset( $field['name'] ) ):
						continue;
					endif;
					if ( isset( $this->posted[ $field['name'] ] ) ):
						$field['value'] = empty( $field['value'] ) ? $this->posted[ $field['name'] ] : $field['value'];
					endif;
					$field['field_name'] = 'totalpoll[fields][%s]';
					$field_obj           = TotalPoll::instance( 'field', array( $field ) );
					$this->fields[]      = $field_obj;

					if ( ! empty( $field['validations']['unique']['enabled'] ) ):
						$this->unique_fields[] = $field_obj;
					endif;
				endforeach;
			endif;
		}

		/**
		 * Run fields validations.
		 *
		 * @param bool $purge Purge cached items.
		 *
		 * @since 3.0.0
		 * @return array Errors.
		 */
		public function run( $purge = false ) {
			if ( $this->bag === false || $purge !== false ):
				$this->bag = new WP_Error();
				$this->validate();
			endif;

			return (array) $this->bag->get_error_messages();
		}

		/**
		 * Get errors.
		 *
		 * @since 3.0.0
		 * @return array Errors.
		 */
		public function errors() {
			if ( $this->bag instanceof WP_Error ) {
				return (array) $this->bag->get_error_messages();
			}

			return array();
		}

		/**
		 * Get fields objects.
		 *
		 * @since 3.0.0
		 * @return array Fields.
		 */
		public function all() {
			return $this->fields;
		}

		/**
		 * Validate fields.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		private function validate() {
			if ( $this->request && $this->request->type() === 'vote' ):

				TotalPoll::instance( 'validations', false );

				foreach ( $this->fields as $field ):
					if ( ( $messages = $field->validate( array( 'poll' => $this->poll ) ) ) !== true ):

						foreach ( $messages as $message ):
							$this->bag->add( $field->name(), $message );
						endforeach;

					else:

					endif;
				endforeach;

			endif;
		}

		/**
		 * Get flat array of fields object ( name => value ).
		 *
		 * @since 3.0.0
		 * @return array Fields.
		 */
		public function to_array() {
			$fields = array();

			foreach ( $this->fields as $field ):
				$fields[ $field->name() ] = $field->value();
			endforeach;

			return $fields;
		}

		/**
		 * Getter.
		 *
		 * @param string $name Name.
		 * @param array  $args Args.
		 *
		 * @return mixed Value.
		 */
		public function __call( $name, $args ) {
			return isset( $this->{$name} ) ? $this->{$name} : false;
		}

	}


endif;