<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Template' ) ) :

	/**
	 * Template Class
	 *
	 * @package TotalPoll/Classes/Template
	 * @since   3.0.0
	 */
	abstract class TP_Template {

		/**
		 * @var TP_Poll Poll object
		 * @access protected
		 * @since  3.0.0
		 */
		protected $poll;

		/**
		 * @var string Template text domain.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $textdomain = '';

		/**
		 * @var string Template settings.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $settings;

		/**
		 * @var string Template preset.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $preset = null;

		/**
		 * @var string Template preset.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $current = null;

		/**
		 * @var string Buffer.
		 * @access protected
		 * @since  3.0.0
		 */
		protected $stream = array();

		/**
		 * @var string Template file path
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__FILE__;

		/**
		 * @var string Template url
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__URL__;

		/**
		 * @var string Template path
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__PATH__;

		/**
		 * @var string Cache directory
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__CACHE_PATH__;

		/**
		 * @var string Cache URL
		 * @access protected
		 * @since  3.0.0
		 */
		protected $__CACHE_URL__;


		/**
		 * Template constructor.
		 *
		 * @since 3.0.0
		 *
		 * @param $poll Poll object
		 */
		public function __construct( $poll ) {
			// Setup useful constant-like

			$this->__PATH__ = str_replace( '\\', '/', dirname( $this->__FILE__ ) . '/' );
			$this->__URL__  = content_url(
				str_replace(
					str_replace( '\\', '/', WP_CONTENT_DIR ),
					'',
					$this->__PATH__
				)
			);

			$this->__CACHE_PATH__ = WP_CONTENT_DIR . '/cache/totalpoll/';
			$this->__CACHE_URL__  = content_url( 'cache/totalpoll/' );

			$this->poll = $poll;
			if ( ! empty( $this->textdomain ) ):
				$this->textdomain();
			endif;

		}

		/**
		 * Load text domain.
		 *
		 * @since 3.0.0
		 * @return bool true on success, false on failure.
		 */
		public function textdomain() {
			if ( ! empty( $this->textdomain ) ):
				$locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );

				return load_textdomain( $this->textdomain, "{$this->__PATH__}/languages/{$this->textdomain}-{$locale}.mo" );
			endif;

			return false;
		}

		/**
		 * Get asset URL.
		 *
		 * @param string $path Relative path to current template path.
		 *
		 * @since 3.0.0
		 * @return string Asset URL.
		 */
		public function asset( $path ) {
			return $this->__URL__ . $path;
		}

		/**
		 * @param string $path Relative path to current template path.
		 *
		 * @since 3.0.0
		 * @return string File path.
		 */
		public function path( $path ) {
			return $this->__PATH__ . $path;
		}

		/**
		 * Template settings (to override).
		 *
		 * @since 3.0.0
		 * @return array Settings array.
		 */
		public function settings() {
			return array();
		}

		/**
		 * Template assets (to override).
		 *
		 * @since 3.0.0
		 * @return array Settings array.
		 */
		public function assets() {
			return false;
		}

		/**
		 * Get option value from template settings.
		 *
		 * @param string $section Section.
		 * @param string $tab     Tab.
		 * @param string $field   Field.
		 *
		 * @since 3.0.0
		 * @return bool|string Option value.
		 */
		public function option( $section, $tab, $field ) {
			$field = $this->preset( $section, $tab, $field );

			if ( isset( $field['value'] ) ):
				$field = $field['value'];
			elseif ( isset( $field['default'] ) ):
				$field = $field['default'];
			else:
				$field = false;
			endif;

			return $field;
		}

		/**
		 * Get option from template settings.
		 *
		 * @param bool|false $section Section.
		 * @param bool|false $tab     Tab.
		 * @param bool|false $field   Field.
		 *
		 * @since 3.0.0
		 * @return mixed Option on success, false otherwise.
		 */
		public function preset( $section = false, $tab = false, $field = false ) {

			$helpers = TotalPoll::instance( 'helpers' );

			if ( $this->preset === null ):
				$this->preset = $this->poll->settings( 'design', 'preset' );

				if ( empty( $this->preset ) ):
					$this->preset = $this->settings();
				else:
					$this->preset = $helpers->parse_args( $this->preset, $this->settings() );
				endif;
			endif;

			return $helpers->pathfinder( $this->preset, array( $section, 'tabs', $tab, 'fields', $field ) );
		}

		/**
		 * Compile and cache template CSS.
		 *
		 * @param string $preset_id Preset ID
		 *
		 * @since 3.0.0
		 * @return string CSS
		 */
		public function compile( $preset_id = '' ) {
			if ( empty( $preset_id ) ):
				$preset_id = md5( json_encode( $this->poll->settings( 'design', 'preset' ) ) );
				update_post_meta( $this->poll->id(), '_preset_id', $preset_id );
			endif;

			$keywords =
				array(
					'keys'   => array( '___PREFIX___' ),
					'values' => array( "#totalpoll-id-{$preset_id}" ),
				);

			$css = $this->stream( 'assets/css/style.css', 'style' );
			$css = TotalPoll::instance( 'helpers' )->compress_css(
				str_replace( $keywords['keys'], $keywords['values'], $css )
			);

			/**
			 * Compile CSS.
			 *
			 * @param string       $css        CSS.
			 * @param false|string $cache_file CSS cached file path.
			 * @param object       $template   Template object.
			 *
			 * @since  3.0.0
			 * @filter totalpoll/filters/template/compile
			 */
			$css = apply_filters( "totalpoll/filters/template/compile", $css, $this );

			update_post_meta( $this->poll->id(), "_compiled_css", $css );

			if ( ! defined( 'TP_CSS_CACHE_ALT' ) ):
				$cached = $this->__CACHE_PATH__ . 'css/';

				if ( file_exists( $cached ) || @mkdir( $cached, ( @fileperms( $cached ) & 0777 | 0755 ), true ) ):

					$old_preset_id = get_post_meta( $this->poll->id(), '_preset_id', true );
					if ( ! empty( $old_preset_id ) && $old_preset_id != $preset_id && file_exists( "{$cached}{$old_preset_id}.css" ) ):
						@unlink( "{$cached}{$old_preset_id}.css" );
					endif;

					file_put_contents( "{$cached}{$preset_id}.css", $css );
				endif;
			endif;

			return $css;
		}

		/**
		 * Get template CSS.
		 *
		 * @since 3.0.0
		 * @return string CSS.
		 */
		public function style() {
			$css       = null;
			$preset_id = get_post_meta( $this->poll->id(), '_preset_id', true );
			$cached    = $this->__CACHE_PATH__ . "css/{$preset_id}.css";

			if ( is_readable( $cached ) ):
				wp_enqueue_style( "totalpoll-{$preset_id}", $this->__CACHE_URL__ . "css/{$preset_id}.css", array(), filemtime( $cached ) );
				ob_start();
				wp_print_styles( "totalpoll-{$preset_id}" );
				$css = ob_get_clean();
			else:
				$cached = false;
				$css    = get_post_meta( $this->poll->id(), "_compiled_css", true );

				if ( empty( $css ) || is_writable( "{$this->__CACHE_PATH__}css/" ) ):
					$css = $this->compile( $preset_id );
				endif;
			endif;

			if ( $css !== null ):
				/**
				 * Stream specific file.
				 *
				 * @param string       $css        CSS.
				 * @param false|string $cache_file CSS cached file path.
				 * @param object       $template   Template object.
				 *
				 * @since  3.0.0
				 * @filter totalpoll/filters/template/css
				 */
				$css = apply_filters( "totalpoll/filters/template/css", $css, $cached, $this );
				if ( ! $cached ):
					$css = "<style type=\"text/css\">$css</style>";
				endif;
			endif;

			return $css;
		}

		/**
		 * Stream (include) a file.
		 *
		 * @param string|false $file   File to include in the stream,
		 *                             empty string can be passed to reset the stream.
		 * @param string       $stream Stream name.
		 *
		 * @since 3.0.0
		 * @return mixed Stream content.
		 */
		public function stream( $file = false, $stream = 'main' ) {
			if ( $file !== false ):
				ob_start();
				$path = $this->path( $file );
				if ( file_exists( $path ) === true ):
					include $path;
				endif;

				if ( ! isset( $this->stream[ $stream ] ) ):
					$this->stream[ $stream ] = '';
				endif;

				/**
				 * Stream specific file.
				 *
				 * @param string $content  File content.
				 * @param string $stream   Current stream.
				 * @param object $template Template object.
				 *
				 * @since  3.0.0
				 * @filter totalpoll/filters/template/stream/{file}
				 */
				$content = apply_filters( "totalpoll/filters/template/stream/{$file}", ob_get_clean(), $stream, $this );

				/**
				 * Stream file.
				 *
				 * @param string $content  File content.
				 * @param string $file     File name.
				 * @param string $stream   Current stream.
				 * @param object $template Template object.
				 *
				 * @since  3.0.0
				 * @filter totalpoll/filters/template/stream/{file}
				 */
				$this->stream[ $stream ] .= apply_filters( 'totalpoll/filters/template/stream/*', $content, $file, $stream, $this );

			elseif ( $file === '' ):
				$this->stream[ $stream ] = '';
			endif;

			return $this->stream[ $stream ];
		}

		/**
		 * Render.
		 *
		 * @param string    $fragment         Fragment to render.
		 * @param bool|true $css              Include CSS with HTML.
		 * @param string    $extra_attributes Extra attributes.
		 *
		 * @param string    $before           Before poll container
		 * @param string    $after            After poll container
		 *
		 * @return string HTML.
		 * @since 3.0.0
		 */
		public function render( $fragment = 'vote', $css = true, $extra_attributes = '', $before = '', $after = '' ) {
			if ( ! empty( $fragment ) ):
				$this->current = $fragment;
			endif;

			$preset_id = get_post_meta( $this->poll->id(), '_preset_id', true );

			// Re-compile style for debugging purpose.
			if ( empty( $preset_id ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG == true ) ):
				$this->compile( $preset_id );
				$preset_id = get_post_meta( $this->poll->id(), '_preset_id', true );
			endif;

			$this->stream( 'header.php' );
			$this->stream( "{$this->current}.php" );
			$this->stream( 'footer.php' );

			return sprintf(
				'%1$s<div id="%2$s" class="%3$s"%4$s>%5$s%6$s%7$s</div>',
				$css === true ? $this->style() : '',
				"totalpoll-id-{$preset_id}",
				'totalpoll-poll-container' . ( is_rtl() ? ' totalpoll-rtl' : '' ),
				$extra_attributes,
				do_shortcode( $before ),
				TotalPoll::instance( 'helpers' )->compress_html( $this->stream() ),
				do_shortcode( $after )
			);
		}

		/**
		 * Buttons.
		 *
		 * @since 3.0.0
		 * @return array Buttons objects.
		 */
		public function buttons() {
			$buttons = array();

			/**
			 * @var TP_HTML $html
			 */
			$html = TotalPoll::instance( 'html', false );
			/**
			 * @var TP_HTML $button
			 */
			$button = new $html( 'button' );
			$errors = isset( $this->poll->limitations()->bag->errors ) ? $this->poll->limitations()->bag->errors : array();

			if ( ( $this->current === 'vote' || $this->current === 'results' ) && ( $this->poll->has_previous_page() || $this->poll->has_next_page() ) ):
				$buttons['pagination'] = clone $button;
				$buttons['pagination']->tag( 'div' );
				$buttons['pagination']->attribute( 'class', 'totalpoll-buttons-pagination' );

				if ( $this->poll->has_previous_page() ):

					$previous_button = clone $button;
					$buttons['pagination']->inner(
						$previous_button->attributes(
							array(
								array( 'name', 'totalpoll[action]' ),
								array( 'value', 'previous' ),
								array( 'class', 'totalpoll-button totalpoll-button-previous' ),
							)
						)->inner( __( 'Previous page', TP_TD ) ),
						true
					);
				endif;

				if ( $this->poll->has_next_page() ):
					$next_button = clone $button;
					$buttons['pagination']->inner(
						$next_button->attributes(
							array(
								array( 'name', 'totalpoll[action]' ),
								array( 'value', 'next' ),
								array( 'class', 'totalpoll-button totalpoll-button-next' ),
							)
						)->inner( __( 'Next page', TP_TD ) ),
						true
					);
				endif;

			endif;

			if ( $this->current === 'vote' ):

				if ( $this->poll->settings( 'limitations', 'captcha', 'enabled' ) ):
					$buttons['captcha'] = clone $button;
					$buttons['captcha']->tag( 'div' );
					$buttons['captcha']->attributes(
						array(
							array( 'class', 'totalpoll-captcha' ),
							array( 'data-tp-captcha', '' ),
						)
					);
				endif;

				if ( $this->poll->settings( 'results', 'hide', 'enabled' ) === false && $this->poll->settings( 'limitations', 'results', 'require_vote', 'enabled' ) === false && ! isset( $errors['start_date'] ) && ! isset( $errors['end_date'] ) ):
					$buttons['results'] = clone $button;
					$buttons['results']->attributes(
						array(
							array( 'name', 'totalpoll[action]' ),
							array( 'value', 'results' ),
							array( 'class', 'totalpoll-button totalpoll-button-link totalpoll-button-results' ),
						)
					)->inner( __( 'Results', TP_TD ) );
				endif;

				if ( ! isset( $errors['start_date'] ) && ! isset( $errors['membership_type'] ) && ! isset( $errors['membership_type'] ) ):
					$buttons['vote'] = clone $button;
					$buttons['vote']->attributes(
						array(
							array( 'name', 'totalpoll[action]' ),
							array( 'value', 'vote' ),
							array( 'class', 'totalpoll-button totalpoll-button-primary totalpoll-button-vote' ),
						)
					)->inner( __( 'Vote', TP_TD ) );
				endif;

			elseif ( $this->current === 'results' && count( $this->poll->limitations()->errors() ) === 0 ):
				$request = $this->poll->requested_by();
				if ( $request != false && $request->type() != 'vote' ):
					$buttons['back'] = clone $button;
					$buttons['back']->attributes(
						array(
							array( 'name', 'totalpoll[action]' ),
							array( 'value', 'back' ),
							array( 'class', 'totalpoll-button totalpoll-button-secondary totalpoll-button-back' ),
						)
					)->inner( __( 'Back', TP_TD ) );
				endif;

			elseif ( $this->current === 'before_vote' || $this->current === 'after_vote' ):
				$buttons['continue'] = clone $button;
				$buttons['continue']->attributes(
					array(
						array( 'name', 'totalpoll[action]' ),
						array( 'value', $this->current === 'after_vote' ? 'results' : 'proceed' ),
						array( 'class', 'totalpoll-button totalpoll-button-primary totalpoll-button-proceed' ),
					)
				)->inner( __( 'Proceed', TP_TD ) );
			endif;

			if ( isset( $buttons['vote'] ) && $this->poll->settings( 'design', 'one_click', 'enabled' ) ) {
				$buttons['vote']->attribute( 'style', 'display: none!important;' );
			}

			/**
			 * Buttons.
			 *
			 * @param array  $buttons  Buttons objects.
			 * @param object $template Template object.
			 *
			 * @since  3.0.0
			 * @filter totalpoll/filters/template/hidden_fields
			 */
			$buttons = apply_filters( 'totalpoll/filters/template/buttons', $buttons, $this );


			return $buttons;
		}

		/**
		 * Get embed code.
		 *
		 * @param        $url  Embed URL.
		 * @param string $type Embed type.
		 *
		 * @since 3.0.0
		 * @return string Embed code
		 */
		public function embed( $url, $type = 'video' ) {
			$embed = wp_oembed_get( $url );

			return empty( $embed ) ? sprintf( '<%1$s src="%2$s" controls="true"></%1$s>', $type, $url ) : $embed;
		}

		/**
		 * Get choice input.
		 *
		 * @param array $choice Choice
		 *
		 * @since 3.0.0
		 * @return string checkbox or radio
		 */
		public function choice_input( $choice ) {
			$type = ( $this->poll->settings( 'limitations', 'selection', 'maximum' ) === 1 ) ? 'radio' : 'checkbox';
			$html = TotalPoll::instance( 'html', false );

			$input = new $html( 'input' );
			$input->attributes(
				array(
					array( 'type', $type ),
					array( 'name', 'totalpoll[choices][]' ),
					array( 'value', $choice['index'] ),
					array( 'checked', $choice['checked'] ),
				)
			);

			return $input;
		}

		public function captcha() {
			if ( $this->current === 'vote' && $this->poll->settings( 'limitations', 'captcha', 'enabled' ) ):
				return TotalPoll::instance( 'html', array( 'div' ) )
				                ->attribute( 'class', 'totalpoll-captcha' )
				                ->attribute( 'data-tp-captcha', '' );
			endif;

			return '';
		}

		/**
		 * Hidden fields.
		 *
		 * @since 3.0.0
		 * @return array Hidden fields objects.
		 */
		public function hidden_fields() {
			$fields = array();

			$html  = TotalPoll::instance( 'html', false );
			$field = new $html( 'input' );
			$field->attribute( 'type', 'hidden' );

			$fields['id'] = clone $field;
			$fields['id']->attributes(
				array(
					array( 'type', 'hidden' ),
					array( 'name', 'totalpoll[id]' ),
					array( 'value', $this->poll->id() ),
				)
			);

			$fields['page'] = clone $field;
			$fields['page']->attributes(
				array(
					array( 'type', 'hidden' ),
					array( 'name', 'totalpoll[page]' ),
					array( 'value', $this->poll->page() ),
				)
			);

			$fields['action'] = clone $field;
			$fields['action']->attributes(
				array(
					array( 'type', 'hidden' ),
					array( 'name', 'totalpoll[view]' ),
					array( 'value', $this->current ),
				)
			);

			/**
			 * Hidden fields.
			 *
			 * @param array  $fields   Fields objects.
			 * @param object $template Template object.
			 *
			 * @since  3.0.0
			 * @filter totalpoll/filters/template/hidden_fields
			 */
			$fields = apply_filters( 'totalpoll/filters/template/hidden_fields', $fields, $this );

			return $fields;
		}

		public function votes( $choice, $percentage = true ) {
			$fragments   = $this->poll->settings( 'results', 'format' );
			$votes       = isset( $fragments['votes'] ) ? sprintf( _n( '%s Vote', '%s Votes', $choice['votes'], TP_TD ), number_format( $choice['votes'] ) ) : '&nbsp;';
			$percentages = $percentage === true && isset( $fragments['percentages'] ) ? number_format( $choice['votes%'], 2 ) . '%' : '';

			if ( ! empty( $percentages ) ):
				$percentages = "({$percentages})";
			endif;

			return "{$votes} {$percentages}";
		}

		/**
		 * On poll save callback.
		 *
		 * @param array $choices  Choices.
		 * @param array $settings Settings.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function used( $choices, $settings, $preset_id = false ) {
			$this->compile( $preset_id );
		}

		/**
		 * On template activation.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public static function on_activate() {

		}

		/**
		 * On template installation.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public static function on_install() {

		}

		/**
		 * On template uninstall.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public static function on_uninstall() {

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