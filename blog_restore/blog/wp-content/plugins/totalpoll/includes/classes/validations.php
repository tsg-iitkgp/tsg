<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Validations' ) ) :

	/**
	 * Validations Class
	 *
	 * @package TotalPoll/Classes/Validations
	 * @since   3.0.0
	 */
	class TP_Validations {

		/**
		 * Validations constructor.
		 */
		public function __construct() {
		}

		/**
		 * Email validation.
		 *
		 * @param object $field Field object.
		 * @param array  $args  Args.
		 *
		 * @since 3.0.0
		 * @return bool|mixed True or error message.
		 */
		public static function email( $field, $args ) {

			if ( is_email( $field->value() ) === false ):

				return str_replace( '%label%', $field->label()->text(), empty( $args['message'] ) ? __( '%label% field does not contain a valid email.', TP_TD ) : $args['message'] );

			endif;

			return true;
		}

		/**
		 * Filled (required) validation.
		 *
		 * @param object $field Field object.
		 * @param array  $args  Args.
		 *
		 * @since 3.0.0
		 * @return bool|mixed True or error message.
		 */
		public static function filled( $field, $args ) {
			$is_empty = false;

			if ( str_replace( ' ', '', $field->value() ) === '' ):
				$is_empty = true;
			elseif ( is_array( $field->value() ) ):
				foreach ( $field->value() as $value ):
					if ( is_array( $value ) || str_replace( ' ', '', $value ) === '' ):
						$is_empty = true;
						break;
					endif;
				endforeach;
			endif;

			if ( $is_empty ):
				return str_replace( '%label%', $field->label()->text(), empty( $args['message'] ) ? __( '%label% field is required.', TP_TD ) : $args['message'] );
			endif;

			return true;
		}

		/**
		 * Options validation.
		 *
		 * @param object $field Field object
		 * @param array  $args  Args
		 *
		 * @since 3.0.0
		 * @return bool|mixed True or error message.
		 */
		public static function options( $field, $args ) {
			$extra                  = $field->extra();
			$multiple               = ! empty( $extra['multiple'] );
			$options                = $extra['options'];
			$field_value            = $field->value();
			$contains_invalid_value = false;

			if ( empty( $field_value ) ) {
				return true;
			}

			foreach ( (array) $field_value as $value ):
				if ( ! isset( $options[ $value ] ) ):
					$contains_invalid_value = true;
					break;
				endif;
			endforeach;

			if ( $contains_invalid_value === true ):
				return str_replace( '%label%', $field->label()->text(), empty( $args['message'] ) ? __( '%label% field does not contain a valid value.', TP_TD ) : $args['message'] );
			endif;

			if ( $multiple === false && count( $field->value() ) > 1 ):
				return str_replace( '%label%', $field->label()->text(), empty( $args['message'] ) ? __( '%label% field does not support multiple values.', TP_TD ) : $args['message'] );
			endif;

			return true;
		}

		/**
		 * Uniqueness validation.
		 *
		 * @param object $field Field object.
		 * @param array  $args  Args.
		 *
		 * @since 3.0.0
		 * @return bool|mixed True or error message.
		 */
		public static function unique( $field, $args ) {
			$unique_key = sanitize_key( $field->name() . '_' . $field->value() );

			if ( $field->value() && $unique_key && get_post_meta( $args['poll']->id(), "_tp_unique_{$unique_key}", true ) ):
				return str_replace( '%label%', $field->label()->text(), empty( $args['message'] ) ? __( '%label% has been used before.', TP_TD ) : $args['message'] );
			endif;

			return true;

		}

		/**
		 * Regular expressions validation.
		 *
		 * @param object $field Field object.
		 * @param array  $args  Args.
		 *
		 * @since 3.0.0
		 * @return bool|mixed True or error message.
		 */
		public static function regex( $field, $args ) {
		    if( empty( $args['against'] ) || empty( $args['type'] ) ):
                return true;
            endif;

			$match = preg_match( $args['against'], $field->value() );
			$valid = $args['type'] == 'match' ? $match : !$match;

			if ( $valid ):
				return str_replace( '%label%', $field->label()->text(), empty( $args['message'] ) ? __( '%label% field is not valid.', TP_TD ) : $args['message'] );
			endif;

			return true;
		}

		/**
		 *  Filter-based validation.
		 *
		 * @param object $field Field object.
		 * @param array  $args  Args.
		 *
		 * @since 3.0.0
		 * @return bool|mixed True or error message.
		 */
		public static function filter( $field, $args ) {
			$list = isset( $args['list'] ) ? explode( "\r\n", trim( $args['list'] ) ) : false;

			if ( $field->value() && ! empty( $list ) ):

				foreach ( $list as $rule ):
					// Strip whitespaces
					$rule        = str_replace( ' ', '', $rule );
					$blacklisted = isset( $rule[0] ) && $rule[0] === '-';
					$rule        = $blacklisted ? substr( $rule, 1 ) : $rule;
					// Generate a new regular expression
					$regexp = str_replace(
						'\*',
						'.+',
						preg_quote( $rule, '/' )
					);

					$regexp = "/{$regexp}/i";

					if ( preg_match( $regexp, $field->value() ) ):

						// When the email is black-listed (prefixed with "-")
						if ( $blacklisted ):
							return sprintf( __( 'You cannot vote because %s field is not allowed.', TP_TD ), $field->label()->text() );
						endif;

						break;
					endif;

				endforeach;

			endif;

			return true;
		}

	}


endif;