<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Field' ) ) :

	/**
	 * Field Class
	 *
	 * @package TotalPoll/Classes/Field
	 * @since   3.0.0
	 */
	class TP_Field {

		/**
		 * @var string Field prefix.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $prefix = 'totalpoll-fields';
		/**
		 * @var string Field ID.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $id = '';
		/**
		 * @var string Field name.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $name = '';
		/**
		 * @var string Field input name.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $field_name = '';
		/**
		 * @var string Field input tag.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $tag = '';
		/**
		 * @var string Field input type.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $type = '';
		/**
		 * @var mixed Field label.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $label = '';
		/**
		 * @var mixed Field value.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $value = '';
		/**
		 * @var mixed Field default value.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $default = '';
		/**
		 * @var array Field input attributes.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $attributes = array();
		/**
		 * @var array Field input CSS classes.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $classes = array();
		/**
		 * @var array Field erros.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $errors = array();
		/**
		 * @var object Field extra parameters.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $extra = array();
		/**
		 * @var array Field validations.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $validations = array();
		/**
		 * @var array Field dom (HTML object) container.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $dom = array();
		/**
		 * @var string Field template.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $template = '';
		/**
		 * @var string Field cache.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $cache = '';

		/**
		 * Field constructor.
		 *
		 * @param $args Field options.
		 *
		 * @since  3.0.0
		 */
		public function __construct( $args ) {
			$this->id          = "{$this->prefix}-{$args['name']}";
			$this->name        = $args['name'];
			$this->field_name  = isset( $args['field_name'] ) ? $args['field_name'] : 'totalpoll[fields][%s]';
			$this->tag         = in_array( $args['type'], array( 'select', 'textarea' ) ) ? $args['type'] : 'input';
			$this->type        = $args['type'];
			$this->label       = is_string( $args['label'] ) ? array(
				'content'    => $args['label'],
				'attributes' => array(),
			) : $args['label'];
			$this->classes     = array(
				$this->id,
				"{$this->prefix}-field",
				"{$this->prefix}-{$this->tag}",
				"{$this->prefix}-{$this->type}",
			);
			$this->classes     = isset( $args['class'] ) ? array_merge( (array) $args['class'], $this->classes ) : $this->classes;
			$this->validations = isset( $args['validations'] ) ? (array) $args['validations'] : array();
			$this->attributes  = isset( $args['attributes'] ) ? (array) $args['attributes'] : array();
			$this->extra       = isset( $args['extra'] ) ? (array) $args['extra'] : array();
			$this->template    = isset( $args['template'] ) ? $args['template'] : '%label% %field%';

			if ( $this->type === 'select' ):
				$this->default = isset( $args['default'] ) ? explode( PHP_EOL, $args['default'] ) : array();
			else:
				$this->default = isset( $args['default'] ) ? $args['default'] : '';
			endif;

			if ( isset( $this->extra['options'] ) && is_string( $this->extra['options'] ) ):
				$this->extra['options'] = (array) explode( PHP_EOL, $this->extra['options'] );
				$options_holder         = array();
				foreach ( $this->extra['options'] as $index => $option ):
					unset( $this->extra['options'][ $index ] );

					$option                       = explode( ' : ', $option, 2 );
					$option[1]                    = empty( $option[1] ) ? $option[0] : $option[1];
					$options_holder[ $option[0] ] = $option[1];
				endforeach;

				$this->extra['options'] = $options_holder;
			endif;

			if ( isset( $this->extra['multiple'] ) ):
				$this->field_name .= '[]';
			endif;

			if ( is_array( $this->default ) && isset( $this->default[0] ) ):
				$this->default = array( $this->default[0] );
			endif;

			$this->value = isset( $args['value'] ) ? $args['value'] : $this->default;
		}

		/**
		 * Field label HTML object.
		 *
		 * @since  3.0.0
		 * @return object Label HTML object.
		 */
		public function label() {
			if ( isset( $this->dom['label'] ) ):
				return $this->dom['label'];
			else:
				$label = $this->dom( 'label', 'label' )
				              ->attribute( 'for', $this->id )
				              ->inner( $this->label['content'] );

				foreach ( (array) $this->label['attributes'] as $key => $value ):
					$label->attribute( $key, $value, true );
				endforeach;

				return $label;

			endif;
		}

		/**
		 * Field HTML object
		 *
		 * @since  3.0.0
		 * @return object
		 */
		public function field() {
			if ( isset( $this->dom['field'] ) ):
				return $this->dom['field'];
			else:
				$field = $this->dom( 'field', $this->tag )
				              ->attribute( 'id', $this->id )
				              ->attribute( 'name', empty( $this->extra['ignore_dynamic_name'] ) ? sprintf( $this->field_name, $this->name ) : $this->field_name )
				              ->attribute( 'type', ( $this->tag === 'input' ? $this->type : false ) )
				              ->attribute( 'class', implode( ' ', $this->classes ) );

				foreach ( (array) $this->attributes as $key => $value ):
					$field->attribute( $key, $value, true );
				endforeach;


				return $field;
			endif;
		}

		/**
		 * Render field.
		 *
		 * @since  3.0.0
		 * @return string HTML.
		 */
		public function render() {
			if ( $this->cache === '' ):

				$placeholders = array(
					'%label%' => $this->label(),
					'%field%' => $this->field(),
				);

				if ( $this->tag === 'input' ):

					if ( ! is_array( $this->value ) ):
						$placeholders['%field%']->attribute( 'value', esc_attr( $this->value ) );
					endif;

					if ( $this->type === 'radio' || $this->type === 'checkbox' ):

						$placeholders['%field%'] = $this->dom( 'field_wrapper', 'div' )
						                                ->attribute( 'class', "{$this->id}-options-wrapper" );

						if ( isset( $this->extra['options'] ) ):
							foreach ( (array) $this->extra['options'] as $value => $caption ):
								$value_slug = sanitize_title_with_dashes( $value );

								$option_label = $this->dom( "input-{$value}-label", 'label' )
								                     ->attribute( 'class', "{$this->id}-option-wrapper {$this->prefix}-checkbox-wrapper" );

								$option = $this->dom( "input-{$value}", 'input' )
								               ->attribute( 'name', sprintf( $this->field_name, $this->name ) )
								               ->attribute( 'type', $this->type )
								               ->attribute( 'value', $value )
								               ->attribute( 'checked', in_array( $value, (array) $this->value ) )
								               ->attribute( 'class', $this->classes )
								               ->attribute( 'class', "{$this->id}-{$value_slug}", true )
								               ->inner( esc_html( $caption ) );

								$option_label->inner( $option, true );
								$placeholders['%field%']->inner( $option_label, true );
							endforeach;
						endif;

					endif;

				elseif ( $this->tag === 'textarea' ):

					if ( is_array( $this->value ) ):
						$this->value = '';
					endif;

					$placeholders['%field%']->inner( esc_html( $this->value ) );

				elseif ( $this->tag === 'select' ):

					if ( isset( $this->extra['options'] ) ):
						foreach ( (array) $this->extra['options'] as $value => $caption ):
							$option = $this->dom( "option-{$value}", 'option' )
							               ->attribute( 'value', $value )
							               ->attribute( 'selected', in_array( $value, (array) $this->value ) )
							               ->inner( esc_html( $caption ) );

							$placeholders['%field%']->inner( $option, true );
						endforeach;
					endif;

				endif;

				if ( isset( $this->extra['multiple'] ) && $this->tag !== 'input' ):
					$placeholders['%field%']->attribute( 'multiple', 'multiple' );
				endif;

				if ( isset( $this->extra['rows'] ) ):
					$placeholders['%field%']->attribute( 'rows', absint( $this->extra['rows'] ) );
				endif;

				$this->cache = str_replace( array_keys( $placeholders ), $placeholders, $this->template );
			endif;

			return $this->cache;
		}

		/**
		 * Get/set HTML object.
		 *
		 * @param string     $id  Element name.
		 * @param bool|false $tag Element tag.
		 *
		 * @since  3.0.0
		 * @return bool|object HTML object, false otherwise.
		 */
		public function dom( $id, $tag = false ) {
			if ( ! isset( $this->dom[ $id ] ) && empty( $tag ) ):
				return false;
			elseif ( ! isset( $this->dom[ $id ] ) ):
				$this->dom[ $id ] = TotalPoll::instance( 'html', array( $tag ) );
			endif;

			return $this->dom[ $id ];
		}

		/**
		 * Validate the field.
		 *
		 * @$user_args array $args Args.
		 *
		 * @since  3.0.0
		 * @return array|bool True when valid, array of errors otherwise.
		 */
		public function validate( $user_args = array() ) {
			// Validations
			foreach ( $this->validations as $validation => $validation_args ):
				if ( isset( $validation_args['enabled'] ) ):

					$callback        = $validation;
					$validation_args = array_merge( $validation_args, $user_args );
					$args            = array_merge( array( $this ), array( $validation_args ) );

					if ( is_callable( array( 'TP_Validations', $validation ) ) ):
						$callback = array( 'TP_Validations', $validation );
					endif;

					if ( is_callable( $callback ) ):
						if ( ( $result = call_user_func_array( $callback, $args ) ) !== true ):
							$this->field()->attribute( 'class', "with-errors", true );
							$this->errors[ $validation ] = $result;
							break;
						endif;
					endif;

				endif;
			endforeach;

			return empty( $this->errors ) ? true : $this->errors;
		}

		/**
		 * Getter/Setter.
		 *
		 * @param string $name Name
		 * @param array  $args Args.
		 *
		 * @return bool Value.
		 */
		public function __call( $name, $args ) {
			if ( isset( $args[1] ) ):
				$this->{$name} = $args[1];
			endif;

			return isset( $this->{$name} ) ? $this->{$name} : false;
		}

		/**
		 * To string.
		 *
		 * @since  3.0.0
		 * @return string
		 */
		public function __toString() {
			return $this->render();
		}

	}


endif;