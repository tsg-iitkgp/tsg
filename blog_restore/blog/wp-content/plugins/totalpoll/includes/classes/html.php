<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_HTML' ) ) :

	/**
	 * HTML Class
	 *
	 * @package TotalPoll/Classes/HTML
	 * @since   3.0.0
	 */
	class TP_HTML {
		/**
		 * @var string Tag.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $tag;
		/**
		 * @var array Attributes.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $attributes = array();
		/**
		 * @var array Inner content.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $inner = array();
		/**
		 * @var string Cached HTML.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $cache = '';

		/**
		 * @var string Void tags.
		 * @access protected
		 * @since  3.0.0
		 */
		protected static $void = array(
			'area'   => true,
			'base'   => true,
			'br'     => true,
			'col'    => true,
			'embed'  => true,
			'hr'     => true,
			'img'    => true,
			'input'  => true,
			'keygen' => true,
			'link'   => true,
			'meta'   => true,
			'param'  => true,
			'source' => true,
			'track'  => true,
			'wbr'    => true,
		);

		/**
		 * HTML constructor.
		 *
		 * @param $tag Tag.
		 *
		 * @since 3.0.0
		 */
		public function __construct( $tag ) {
			$this->tag = strtolower( $tag );
		}

		/**
		 * Get/Set attribut.
		 *
		 * @param bool|string $key       Attribute key.
		 * @param bool|mixed  $value     Attribute value.
		 * @param bool        $append    Append value to existing values.
		 * @param string      $separator Values separator.
		 *
		 * @since 3.0.0
		 * @return object $this Object instance
		 */
		public function attribute( $key = false, $value = false, $append = false, $separator = ' ' ) {

			if ( $key !== false && $value !== false ):
				$this->cache = '';

				if ( ! isset( $this->attributes[ $key ] ) || $append === false ):
					$this->attributes[ $key ] = array( 'separator' => $separator, 'values' => array() );
				endif;

				foreach ( (array) $value as $sub_value ):
					$this->attributes[ $key ]['values'][] = $sub_value;
				endforeach;

			endif;

			return $this;

		}

		/**
		 * Get/Set attributes.
		 *
		 * @param array $attributes Array of arrays ( args of attribute method ).
		 *
		 * @since 3.0.0
		 * @return $this|array Attributes array when used as getter, object instance otherwise.
		 */
		public function attributes( $attributes = array() ) {

			if ( ! empty( $attributes ) ):

				foreach ( $attributes as $attribute ):
					$this->attribute(
						isset( $attribute[0] ) ? $attribute[0] : false,
						isset( $attribute[1] ) ? $attribute[1] : false,
						isset( $attribute[2] ) ? $attribute[2] : false,
						isset( $attribute[3] ) ? $attribute[3] : false,
						isset( $attribute[4] ) ? $attribute[4] : ' '
					);
				endforeach;

				return $this;
			endif;

			return $this->attributes;
		}

		/**
		 * Append content to element (inner content).
		 *
		 * @param object|string $inner  Content
		 * @param bool|false    $append Append or replace.
		 *
		 * @since 3.0.0
		 * @return $this Object instance.
		 */
		public function inner( $inner, $append = false ) {
			$this->cache = '';
			if ( $append ):
				$this->inner[] = $inner;
			else:
				$this->inner = array( $inner );
			endif;

			return $this;
		}

		/**
		 * Get inner content.
		 *
		 * @since 3.0.0
		 * @return string Element inner content.
		 */
		public function text() {
			return isset( $this->inner ) ? implode( '', $this->inner ) : '';
		}

		/**
		 * Render element.
		 *
		 * @since 3.0.0
		 * @return string Element rendered.
		 */
		public function render() {
			if ( empty( $this->cache ) ):

				$this->cache = "<{$this->tag}";

				foreach ( $this->attributes as $key => $attribute ):
					$this->cache .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( implode( $attribute['separator'], (array) $attribute['values'] ) ) );
				endforeach;

				$this->cache .= '>';
				if ( ! isset( self::$void[ $this->tag ] ) ):
					$this->cache .= implode( '', $this->inner );
					$this->cache .= "</{$this->tag}>";
				else:
					$this->cache .= implode( '', $this->inner );
				endif;

			endif;

			return $this->cache;
		}

		/**
		 * Getter / Setter.
		 *
		 * @param string $name Name.
		 * @param array  $args Args.
		 *
		 * @since 3.0.0
		 * @return mixed Value.
		 */
		public function __call( $name, $args ) {
			if ( isset( $args[0] ) ):
				$this->{$name} = $args[0];
			endif;

			return isset( $this->{$name} ) ? $this->{$name} : false;
		}

		/**
		 * To string.
		 *
		 * @since 3.0.0
		 * @return string
		 */
		public function __toString() {
			return $this->render();
		}

	}


endif;