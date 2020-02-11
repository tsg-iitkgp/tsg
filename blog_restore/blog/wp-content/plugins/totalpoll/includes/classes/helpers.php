<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Helpers' ) ) :

	/**
	 * Helpers Class
	 *
	 * @package TotalPoll/Classes/Helpers
	 * @since   3.0.0
	 */
	class TP_Helpers {

		/**
		 * Helpers constructor.
		 */
		public function __construct() {
		}

		/**
		 * Compress CSS.
		 *
		 * @param $css CSS.
		 *
		 * @since 3.0.0
		 * @return mixed Compressed CSS.
		 */
		public function compress_css( $css ) {
			if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG == false ):

				$patterns = array(
					'#/\*.*?\*/#s'       => '', // Remove comments
					'/\s*([{}|:;,])\s+/' => '$1', // Remove whitespace
					'/\s\s+(.*)/'        => '$1', // Remove trailing whitespace at the start
					'/\;\}/'             => '}', // Remove unnecessary ;
				);

				$css = preg_replace( array_keys( $patterns ), array_values( $patterns ), $css );
			endif;

			return $css;
		}

		/**
		 * Compress HTML.
		 *
		 * @param $html HTML.
		 *
		 * @since 3.0.0
		 * @return mixed Compressed HTML.
		 */
		public function compress_html( $html ) {
			if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG == false ):
				$html = preg_replace( "/\n\r|\r\n|\n|\r|\t| {2}/", '', $html );
			endif;

			return $html;
		}

		/**
		 * Recursive version of wp_parse_args.
		 *
		 * @see   http://mekshq.com/recursive-wp-parse-args-wordpress-function/
		 *
		 * @param array $args     Args.
		 * @param array $defaults Defaults.
		 *
		 * @since 3.0.0
		 * @return array Args.
		 */
		public function parse_args( $args, $defaults ) {
			$args   = (array) $args;
			$result = (array) $defaults;
			foreach ( $args as $key => &$value ):
				if ( is_array( $value ) && isset( $result[ $key ] ) ):
					$result[ $key ] = $this->parse_args( $value, $result[ $key ] );
				else:
					$result[ $key ] = $value;
				endif;
			endforeach;

			return $result;
		}

		/**
		 * Deep array walker.
		 *
		 * @param array $haystack Haystack.
		 * @param array $needle   Needle.
		 *
		 * @since 3.0.0
		 * @return mixed
		 */
		public function pathfinder( $haystack, $needle ) {
			if ( is_array( $haystack ) && is_array( $needle ) ):
				foreach ( $needle as $path ):
					if ( isset( $haystack[ $path ] ) ):
						$haystack = $haystack[ $path ];
					else:
						$haystack = false;
						break;
					endif;
				endforeach;
			endif;

			return $haystack;
		}

		/**
		 * Parse User-Agent string.
		 *
		 * @see   http://stackoverflow.com/a/21336163
		 *
		 * @param string $ua User agent string
		 *
		 * @since 3.0.0
		 * @return mixed
		 */
		public function parse_useragent( $ua ) {
			$ua = is_null( $ua ) ? $_SERVER['HTTP_USER_AGENT'] : $ua;
			// Enumerate all common platforms, this is usually placed in braces (order is important! First come first serve..)
			$platforms = 'Windows|iPad|iPhone|Macintosh|Android|BlackBerry';

			// All browsers except MSIE/Trident and..
			// NOT for browsers that use this syntax: Version/0.xx Browsername
			$browsers = 'Firefox|Chrome';

			// Specifically for browsers that use this syntax: Version/0.xx Browername
			$browsers_v = 'Safari|Mobile'; // Mobile is mentioned in Android and BlackBerry UA's

			// Fill in your most common engines..
			$engines = 'Gecko|Trident|Webkit|Presto';

			// Regex the crap out of the user agent, making multiple selections and..
			$regex_pat = "/((Mozilla)\/[\d\.]+|(Opera)\/[\d\.]+)\s\(.*?((MSIE)\s([\d\.]+).*?(Windows)|({$platforms})).*?\s.*?({$engines})[\/\s]+[\d\.]+(\;\srv\:([\d\.]+)|.*?).*?(Version[\/\s]([\d\.]+)(.*?({$browsers_v})|$)|(({$browsers})[\/\s]+([\d\.]+))|$).*/i";

			// .. placing them in this order, delimited by |
			$replace_pat = '$7$8|$2$3|$9|${17}${15}$5$3|${18}${13}$6${11}';

			// Run the preg_replace .. and explode on |
			$ua_array = explode( '|', preg_replace( $regex_pat, $replace_pat, $ua, PREG_PATTERN_ORDER ) );

			$return             = array();
			$return['platform'] = $return['type'] = $return['renderer'] = $return['browser'] = $return['version'] = __( 'N/A', TP_TD );

			if ( count( $ua_array ) > 1 ):
				$return['platform'] = $ua_array[0];  // Windows / iPad / MacOS / BlackBerry
				$return['type']     = $ua_array[1];  // Mozilla / Opera etc.
				$return['renderer'] = $ua_array[2];  // WebKit / Presto / Trident / Gecko etc.
				$return['browser']  = $ua_array[3];  // Chrome / Safari / MSIE / Firefox

				if ( preg_match( "/^[\d]+\.[\d]+(?:\.[\d]{0,2}$)?/", $ua_array[4], $matches ) ) :
					$return['version'] = $matches[0];
				else:
					$return['version'] = $ua_array[4];
				endif;
			else:
				return $return;
			endif;

			// Replace some browsernames e.g. MSIE -> Internet Explorer
			switch ( strtolower( $return['browser'] ) ):
				case 'msie':
				case 'trident':
					$return['browser'] = 'Internet Explorer';
					break;
				case '': // IE 11 is a steamy turd (thanks Microsoft...)
					if ( strtolower( $return['renderer'] ) === 'trident' ):
						$return['browser'] = 'Internet Explorer';
					endif;
					break;
			endswitch;

			switch ( strtolower( $return['platform'] ) ):
				case 'android':    // These browsers claim to be Safari but are BB Mobile
				case 'blackberry': // and Android Mobile

					if ( $return['browser'] === 'Safari' || $return['browser'] === 'Mobile' || empty( $return['browser'] ) ) :
						$return['browser'] = "{$return['platform']} mobile";
					endif;

					break;
			endswitch;

			return $return;

		}

		/**
		 * Purge cache
		 *
		 * @since 3.3.0
		 * @return void
		 */
		public function purge_cache() {
			if ( function_exists( 'w3tc_pgcache_flush' ) ):
				w3tc_pgcache_flush();
			elseif ( function_exists( 'wp_cache_clear_cache' ) ):
				wp_cache_clear_cache();
			elseif ( function_exists( 'rocket_clean_domain' ) ):
				rocket_clean_domain();
			elseif ( function_exists( 'hyper_cache_invalidate' ) ):
				hyper_cache_invalidate();
			elseif ( function_exists( 'wp_fast_cache_bulk_delete_all' ) ):
				wp_fast_cache_bulk_delete_all();
			elseif ( has_action( 'cachify_flush_cache' ) ):
				do_action( 'cachify_flush_cache' );
			endif;
		}
	}


endif;