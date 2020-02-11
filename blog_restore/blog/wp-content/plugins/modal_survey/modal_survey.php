<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: Modal Survey
 * Plugin URI: http://modalsurvey.pantherius.com
 * Description: Manage Surveys, Polls and Quizzies
 * Author: Pantherius
 * Version: 1.9.8.8
 * Author URI: http://pantherius.com
 */

define( 'MODAL_SURVEY_TEXT_DOMAIN' , 'modal_survey' );
define( 'GRID_ITEMS' , '' );
define( 'MODAL_SURVEY_VERSION' , '1.9.8.8' );
define( 'MSDIRS' , '/' );

if( ! class_exists( 'modal_survey' ) ) {
	class modal_survey {
		protected static $instance = null;
		var $auto_embed = 'false';
		var $mscontentinit = 'false';
		var $mspreinit = 'false';
		var $modalscript = '';
		var $scripts = array( 'msdev' => 'modal_survey.js', 'msmin' => 'modal_survey.min.js', 'msadev' => 'modal_survey_answer.js', 'msamin' => 'modal_survey_answer.min.js' );
		var $mainscript = '';
		var $answerscript = '';
		var $esurvey = array();
		var $script = '';
		var $postid = '';
		var $postcharts = array();
		var $msplugininit_array = array();
		public $msplugininit_answer_array = array();
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			global $wpdb;
			// installation and uninstallation hooks
			register_activation_hook(__FILE__, array('modal_survey', 'activate'));
			register_deactivation_hook(__FILE__, array('modal_survey', 'deactivate'));
			register_uninstall_hook(__FILE__, array('modal_survey', 'uninstall'));
			add_action( 'plugins_loaded', array(&$this, 'modalsurvey_localization'));
			if ( is_admin() ) {
				if ( get_option( 'setting_remember_users' ) == "" ) {
					update_option( 'setting_remember_users', 'on' );
				}
				require_once( sprintf( "%s/settings.php", dirname( __FILE__ ) ) );
				$modal_survey_settings = new modal_survey_settings();
				$plugin = plugin_basename( __FILE__ );
				add_filter( "plugin_action_links_$plugin", array( &$this, 'plugin_settings_link' ) );
				add_action( 'admin_notices', array( &$this, 'deactivation_notice' ) );
			}
			else {
				$modal_survey_url = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
				$modal_survey_load = true;
				if ( ( strpos( $modal_survey_url, 'wp-login' ) ) !== false ) {
						$modal_survey_load = false;
				}
				if ( ( strpos( $modal_survey_url, 'wp-admin' ) ) !== false ) {
					$modal_survey_load = false;
				}
				if ( $modal_survey_load || isset( $_REQUEST[ 'sspcmd' ] ) ) {
					//integrate the public functions
					add_action('init', array(&$this, 'enqueue_custom_scripts_and_styles' ) );
					add_shortcode( 'survey', array( &$this, 'survey_shortcodes' ) );
					add_shortcode( 'modalsurvey', array( &$this, 'survey_shortcodes' ) );
					add_shortcode( 'modal_survey', array( &$this, 'survey_shortcodes' ) );
					add_shortcode( 'survey_answers', array( &$this, 'survey_answers_shortcodes' ) );
					add_shortcode( 'survey_records', array( &$this, 'survey_records_shortcodes' ) );
					add_shortcode( 'survey_conditions', array( &$this, 'survey_conditions_shortcodes' ) );
					add_filter( 'widget_text', 'do_shortcode' );
					add_filter( 'bbp_get_reply_content', array( &$this, 'enable_modalsurvey_shortcode' ), 1 );
					add_filter( 'bbp_get_topic_title', array( &$this, 'add_modalsurvey_shortcode_to_topics' ), 1 );
					add_filter( 'the_content', array( &$this, 'extend_the_content' ) );
					if ( get_option( 'setting_plugininit' ) == 'getfooter' ) {
						add_action( 'get_footer' , array( &$this, 'initialize_plugin' ), 175 );
					}
					elseif ( get_option( 'setting_plugininit' ) == 'wpfooter' ) {
						add_action( 'wp_footer' , array( &$this, 'initialize_plugin' ), 175 );
					}
					else {
						add_action( 'get_footer' , array( &$this, 'initialize_plugin' ), 175 );						
					}
					if ( get_option( 'setting_social_metas' ) == "on" ) {
						add_action( 'wp_head', array( &$this, 'add_social_metas' ) );
					}
				}
				if ( get_option( 'setting_minify' ) == 'on' ) {
					$this->mainscript = $this->scripts[ 'msmin' ];
					$this->answerscript = $this->scripts[ 'msamin' ];
				}
				else {
					$this->mainscript = $this->scripts[ 'msdev' ];				
					$this->answerscript = $this->scripts[ 'msadev' ];
				}
			}
		}

		function add_modalsurvey_shortcode_to_topics( $title ) {
			global $post;
			if ( $this->auto_embed == 'false' && ! empty( $title ) && ! empty( $this->esurvey ) ) {
				$this->auto_embed = 'true';
				if ( $post->post_type == "topic" && $this->esurvey[ 'style' ] == 'embed_topics' ) {
					$title .= modal_survey::survey_shortcodes( 
					array ( 'id' => $this->esurvey[ 'survey_id' ], 'style' => 'flat', 'customclass' => 'autoembed-msurvey' )
					);
				}
			}
				return $title;
		}

		function enable_modalsurvey_shortcode( $content ) {
			$reply_author_id = get_post_field( 'post_author', bbp_get_reply_id() );
			$user_data = get_userdata( $reply_author_id );
			if ( user_can( $user_data, 'edit_others_forums' ) ) {
				preg_match_all( '/\[modalsurvey (.*)]/', $content, $matches );
				foreach( $matches[ 0 ] as $match ) {
					$content = str_replace( $match, do_shortcode( $match ), $content );
				}
			}
			return $content;
		}	
	
		function call_modalsurvey_shortcode( $content ) {
				add_shortcode( 'survey_answers', array( &$this, 'survey_answers_shortcodes' ) );
				preg_match_all( '/\[survey_answers (.*)]/', $content, $matches );
				foreach( $matches[ 0 ] as $match ) {
					$content = str_replace( $match, do_shortcode( $match ), $content );
				}
			return $content;
		}	

		function call_modalsurvey_conditions_shortcode( $content ) {
				add_shortcode( 'survey_conditions', array( &$this, 'survey_conditions_shortcodes' ) );
				$content = trim( preg_replace( '/\s+/', ' ', nl2br( $content ) ) );
				preg_match_all( '/\[survey_conditions (.*?)](.*?)\[\/survey_conditions]/', $content, $matches );
				foreach( $matches[ 0 ] as $key=>$match ) {
					$content = str_replace( $match, do_shortcode( $match ), $content );
				}
				$breaks = array( "<br />", "<br>", "<br/>" );  
				$content = str_ireplace( $breaks, "\r\n", $content );
			return $content;
		}	

		function call_modalsurvey_records_shortcode( $content ) {
				add_shortcode( 'survey_records', array( &$this, 'survey_records_shortcodes' ) );
				$content = trim( preg_replace( '/\s+/', ' ', nl2br( $content ) ) );
				preg_match_all( '/\[survey_records (.*)]/', $content, $matches );
				foreach( $matches[ 0 ] as $key=>$match ) {
					$content = str_replace( $match, do_shortcode( $match ), $content );
				}
			return $content;
		}	

		function get_featured_image() {
			if ( has_post_thumbnail( get_the_ID() ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
				return $image[0];
			}
			else {
				return false;
			}
		}
		
		function get_short_desc() {
			global $post;
			if ( ! empty( $post ) ) {
				return $post->post_excerpt;
			}
			else {
				if ( $post ) {
					$content = $post->post_content;
					$content = strip_shortcodes( strip_tags( $content ) );
					$excerpt = wp_trim_words( $content, 100 );
					return $excerpt;
				}
				else {
					return '';
				}
			}
		}
		
		function add_social_metas() {
			global $wp;
			$socialmeta = '<meta property="og:title" content="' . get_the_title() . '" />
';
			if ( get_option( 'setting_fbappid' ) != "" ) {
			$socialmeta .= '<meta property="fb:app_id" content="' . get_option( 'setting_fbappid' ) . '" />
';
			}
			$socialmeta .= '<meta property="og:description" content="' . $this->get_short_desc() . '" />
';
			if ( isset( $_REQUEST[ 'msid' ] ) ) {
			$socialmeta .= '<meta property="og:url" data-react-helmet="true" content="' . ( home_url(add_query_arg(array(),$wp->request)) ) . '?msid=' . $_REQUEST[ 'msid' ] . '" />
';
			$socialmeta .= '<link rel="canonical" href="' . ( home_url(add_query_arg(array(),$wp->request)) ) . '?msid=' . $_REQUEST[ 'msid' ] . '" />
';
			}
			else {
			$socialmeta .= '<meta property="og:url" content="' . ( home_url(add_query_arg(array(),$wp->request)) ). '/" />
';				
			}
			if ( isset( $_REQUEST[ 'msid' ] ) ) {
			$socialmeta .= '<meta property="og:type" content="article" />
';
			}
			else {
			$socialmeta .= '<meta property="og:type" content="website" />
';				
			}
			$socialmeta .= '<meta property="og:site_name" content="' . get_the_title() . '" />
';
			if ( isset( $_REQUEST[ 'msid' ] ) ) {
				$socialmeta .= '<meta property="og:image" data-react-helmet="true" content="' . base64_decode( $_REQUEST[ 'msid' ] ) . '" />
';
			}
			else {
				$feat_image = $this->get_featured_image();
				if ( ! empty( $feat_image ) ) {
					$socialmeta .= '<meta property="og:image" content="' . $feat_image . '" />
';
				}
			}
			return print($socialmeta);
		}
		
		public static function getInstance()
		{
			if ( ! isset( $instance ) ) 
			{
				$instance = new modal_survey;
			}
		return $instance;
		}
		function deactivation_notice() {
			global $wpdb;
			$e_sql = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."modal_survey_answers LIMIT 1");
			if ( ! empty( $e_sql ) ) {
				if (!isset($e_sql[0]->uniqueid))
				{
				print('<div class="error">
					<p>' . __( 'Modal Survey needs to be reactivated to initialize the new updates and keep your existing settings. Please ',MODAL_SURVEY_TEXT_DOMAIN ) . '<a href="' . admin_url( 'plugins.php#modal-survey' ) . '">' . __( 'click here to go to the Plugins page ',MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' . __( ', deactivate the plugin, then click on the Activate.',MODAL_SURVEY_TEXT_DOMAIN ) . '</strong></p>
				</div>');
				}
			}
		}
		
		/**
		* Activate the plugin
		**/
		public static function activate() {
			global $wpdb;
			$db_info = array();
			//define custom data tables
			$charset_collate = '';
			if ( ! empty( $wpdb->charset ) ) {
			  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
			}

			if ( ! empty( $wpdb->collate ) ) {
			  $charset_collate .= " COLLATE {$wpdb->collate}";
			}
			$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . 'modal_survey_surveys' . " (
			  id varchar(255) NOT NULL,
			  name varchar(255) NOT NULL,
			  options text NOT NULL,
			  start_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  expiry_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  global tinyint(1) NOT NULL,
			  autoid mediumint(9) NOT NULL AUTO_INCREMENT,
			  created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  owner bigint NOT NULL,
			  UNIQUE KEY autoid (autoid)
			) $charset_collate";
			$wpdb->query( $sql );
			$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->base_prefix . 'modal_survey_questions' . " (
			  id mediumint(9) NOT NULL,
			  survey_id varchar(255) NOT NULL,
			  question text NOT NULL,
			  qoptions text NOT NULL
			) $charset_collate";
			$wpdb->query( $sql );
			$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . 'modal_survey_answers' . " (
			  survey_id varchar(255) NOT NULL,
			  question_id mediumint(9) NOT NULL,
			  answer text NOT NULL,
			  aoptions text NOT NULL,
			  count mediumint(9) DEFAULT '0' NOT NULL,
			  autoid mediumint(9) NOT NULL,
			  uniqueid varchar(255) NOT NULL
			) $charset_collate";
			$wpdb->query( $sql );
			$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . 'modal_survey_answers_text' . " (
			id varchar(255) NOT NULL,
			survey_id varchar(255) NOT NULL,
			answertext text NOT NULL,
			count mediumint(9) DEFAULT '0' NOT NULL
			) $charset_collate";
			$wpdb->query( $sql );
			$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . 'modal_survey_participants' . " (
			  autoid mediumint(9) NOT NULL AUTO_INCREMENT,
			  id varchar(255) NOT NULL,
			  username varchar(255) NOT NULL,
			  email varchar(255) NOT NULL,
			  name varchar(255) NOT NULL,
			  custom text NOT NULL,
			  UNIQUE KEY autoid (autoid)
			) $charset_collate";
			$wpdb->query( $sql );
			$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . 'modal_survey_participants_details' . " (
			  uid varchar(255) NOT NULL,
			  sid varchar(255) NOT NULL,
			  qid varchar(255) NOT NULL,
			  aid text NOT NULL,
			  postid bigint NOT NULL,
			  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  ip varchar(255) NOT NULL,
			  samesession varchar(255) NOT NULL,
			  timer int NULL
			) $charset_collate";
			$wpdb->query( $sql );

			if ( ! get_option( 'setting_keep_settings' ) ) {
				update_option( 'setting_keep_settings', 'off' );
			}
			if ( ! get_option( 'setting_globalsurvey' ) ) {
				update_option( 'setting_globalsurvey', 'on' );
			}
			if ( ! get_option( 'setting_minify' ) ) {
				update_option('setting_minify', 'on');
			}
			if ( ! get_option( 'setting_save_votes' ) ) {
				update_option('setting_save_votes', 'on');
			}	
			if ( ! get_option( 'setting_remember_users' ) ) {
				update_option('setting_remember_users', 'on');
			}
			if ( ! get_option( 'setting_display_once' ) ) {
				update_option( 'setting_display_once' , 'off' );
			}
			if ( ! get_option( 'setting_display_once_per_filled' ) ) {
				update_option( 'setting_display_once_per_filled' , 'off' );
			}
			if ( ! get_option( 'setting_plugininit' ) ) {
				update_option( 'setting_plugininit' , 'aftercontent' );
			}
			if ( ! get_option( 'setting_pdf_font' ) ) {
				update_option( 'setting_pdf_font' , 'dejavusans' );
			}
			if ( ! get_option( 'setting_pdf_header' ) || get_option( 'setting_pdf_header' ) == "" ) {
				update_option( 'setting_pdf_header' , 'generated by Modal Survey
http://pantherius.com/modal-survey' );
			}
			if ( ! get_option( 'setting_customcss' ) ) {
				add_option( 'setting_customcss' , '' );
			}
			if ( ! get_option( 'setting_db_modal_survey' ) ) {
				update_option( 'setting_db_modal_survey', MODAL_SURVEY_VERSION );
			}
			modal_survey::update_modal_survey_db();
		}
		/**
		* Deactivate the plugin
		**/
		public static function deactivate()
		{
			wp_unregister_sidebar_widget('modal_survey');
			unregister_setting('modal_survey-group', 'setting_display_once');
			unregister_setting('modal_survey-group', 'setting_display_once_per_filled');
			unregister_setting('modal_survey-group', 'setting_keep_settings');
			unregister_setting('modal_survey-group', 'setting_globalsurvey');
			unregister_setting('modal_survey-group', 'setting_minify');
			unregister_setting('modal_survey-group', 'setting_save_votes');
			unregister_setting('modal_survey-group', 'setting_remember_users');
			unregister_setting('modal_survey-group', 'setting_pdf_header');
			unregister_setting('modal_survey-group', 'setting_plugininit');
			unregister_setting('modal_survey-group', 'setting_pdf_font');
			unregister_setting('modal_survey-group', 'setting_custom_individual_export');
			unregister_setting('modal_survey_social-group', 'setting_social');
			unregister_setting('modal_survey_social-group', 'setting_social_sites');
			unregister_setting('modal_survey_social-group', 'setting_social_metas');
			unregister_setting('modal_survey_social-group', 'setting_social_style');
			unregister_setting('modal_survey_social-group', 'setting_social_pos');
			unregister_setting('modal_survey_social-group', 'setting_fbappid');
			unregister_setting('modal_survey_customcss-group', 'setting_customcss');
		}
		
		/**
		* Uninstall the plugin
		**/
		public static function uninstall()
		{
			if (get_option("setting_keep_settings")!="on")
			{
				global $wpdb;
				$db_info = array();
				//define custom data tables
				$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->base_prefix.'modal_survey_surveys');
				$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->base_prefix.'modal_survey_questions');
				$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->base_prefix.'modal_survey_answers');
				$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->base_prefix.'modal_survey_answers_text');
				$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->base_prefix.'modal_survey_participants');
				$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->base_prefix.'modal_survey_participants_details');
				delete_option('setting_display_once');
				delete_option('setting_display_once_per_filled');
				delete_option('setting_keep_settings');
				delete_option('setting_globalsurvey');
				delete_option('setting_minify');
				delete_option('setting_save_votes');
				delete_option('setting_remember_users');
				delete_option('setting_pdf_header');
				delete_option('setting_plugininit');
				delete_option('setting_pdf_font');
				delete_option('setting_custom_individual_export');
				delete_option('setting_social');
				delete_option('setting_social_sites');
				delete_option('setting_social_metas');
				delete_option('setting_social_style');
				delete_option('setting_social_pos');
				delete_option('setting_fbappid');
				delete_option('setting_customcss');
				delete_option('setting_db_modal_survey');
			}
		}
			
		/**
		* Enable Localization
		**/
		public function modalsurvey_localization() {
		// Localization
		load_plugin_textdomain( 'modal_survey', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		function analyze_advanced_conditions( $args, $fullrecords ) {
			$cpoints = 0;
			if ( $args[ 'condition' ] == "finalscore" ) {
				foreach( $fullrecords as $fr ) {
					foreach( $fr[ 'datas' ] as $frd ) {
						if ( $frd[ 'selected' ] == "true" ) {
							if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
								$cpoints += $frd[ 'score' ];
							}
						}
					}
				}
			}
			elseif ( $args[ 'condition' ] == "correctanswers" ) {
				foreach( $fullrecords as $fr ) {
					foreach( $fr[ 'datas' ] as $frd ) {
						if ( $frd[ 'selected' ] == "true" && $frd[ 'correct' ] == "true" ) {
							if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
								$cpoints++;
							}
						}
					}
				}					
			}
			elseif ( strpos( $args[ 'condition' ], 'questionscore' ) !== false ) {
				$index = explode( "_", $args[ 'condition' ] );
				foreach( $fullrecords as $key => $fr ) {
					if ( $key == ( $index[ 1 ] - 1 ) ) {
						foreach( $fr[ 'datas' ] as $frd ) {
							if ( $frd[ 'selected' ] == "true" ) {
								if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
									$cpoints = $frd[ 'score' ];
								}
							}
						}
					}
				}
			}
			elseif ( strpos( $args[ 'condition' ], 'questionanswer' ) !== false ) {
				$index = explode( "_", $args[ 'condition' ] );
				foreach( $fullrecords as $key => $fr ) {
					if ( $key == ( $index[ 1 ] - 1 ) ) {
						$aid = 0;
						foreach( $fr[ 'datas' ] as $frd ) {
							$aid++;
							if ( $frd[ 'selected' ] == "true" ) {
								if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
									$cpoints = $aid;
								}
							}
						}
					}
				}
			}
/*			elseif ( strpos( $args[ 'condition' ], 'categoryscore' ) !== false ) {
				$index = explode( "_", $args[ 'condition' ] );
				foreach( $fullrecords as $fr ) {
					$category = "";
					preg_match_all( "/\[([^\]]*)\]/", $fr[ 'title' ], $cat );
					if ( $cat[ 1 ][ 0 ] ) {
						$category = strtolower( $cat[ 1 ][ 0 ] );
					}
					foreach( $fr[ 'datas' ] as $frd ) {
						if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
							preg_match_all( "/\[([^\]]*)\]/", $frd[ 'answer' ], $acat );
							if ( isset( $acat[ 1 ][ 0 ] ) ) {
								$category = strtolower( $acat[ 1 ][ 0 ] );
							}
							if ( $frd[ 'selected' ] == "true" && ! empty( $category ) ) {
								if ( ! isset( $cats[ $category ] ) ) {
									$cats[ $category ] = 0;
								}
								$cats[ $category ] += $frd[ 'score' ];
							}
						}
					}
				}
				if ( isset( $cats[ strtolower( $index[ 1 ] ) ] ) ) {
					$cpoints = $cats[ strtolower( $index[ 1 ] ) ];
				}
			}*/
			elseif ( strpos( $args[ 'condition' ], 'categoryscore' ) !== false ) {
				$c_math = explode( "+", $args[ 'condition' ] ); $cpoints = 0;
				foreach ( $c_math as $math_elements ) {
					$cats = array();
					$index = explode( "_", $math_elements );
					foreach( $fullrecords as $fr ) {
						$category = "";
						preg_match_all( "/\[([^\]]*)\]/", $fr[ 'title' ], $cat );
						if ( $cat[ 1 ][ 0 ] ) {
							$category = strtolower( $cat[ 1 ][ 0 ] );
						}
						foreach( $fr[ 'datas' ] as $frd ) {
							if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
								preg_match_all( "/\[([^\]]*)\]/", $frd[ 'answer' ], $acat );
								if ( isset( $acat[ 1 ][ 0 ] ) ) {
									$category = strtolower( $acat[ 1 ][ 0 ] );
								}
								if ( $frd[ 'selected' ] == "true" && ! empty( $category ) ) {
									if ( ! isset( $cats[ $category ] ) ) {
										$cats[ $category ] = 0;
									}
									$cats[ $category ] += $frd[ 'score' ];
								}
							}
						}
					}				
					if ( isset( $cats[ strtolower( $index[ 1 ] ) ] ) ) {
						$cpoints += $cats[ strtolower( $index[ 1 ] ) ];
					}
				}
			}
			if ( $args[ 'relation' ] == "highest" ) {
				$max = array_keys( $cats, max( $cats ));
				if ( $max[ 0 ] ==  strtolower( $index[ 1 ] ) ) {
					return true;
				}
			}
			if ( $args[ 'relation' ] == "lowest" ) {
				$min = array_keys( $cats, min( $cats ) );
				if ( $min[ 0 ] ==  strtolower( $index[ 1 ] ) ) {
					return true;
				}
			}
			if ( $args[ 'relation' ] == "higher" ) {
				if ( $cpoints > $args[ 'value' ] ) {
					return true;
				}
			}
			if ( $args[ 'relation' ] == "equal" ) {
				$between = explode( "-", $args[ 'value' ] );
				if ( is_array( $between ) && isset( $between[ 1 ] ) ) {
					if ( $cpoints >= $between[ 0 ] && $cpoints <= $between[ 1 ] ) {
						return true;
					}							
				}
				else {
					if ( $cpoints == $args[ 'value' ] ) {
						return true;
					}
				}
			}
			if ( $args[ 'relation' ] == "notequal" ) {
				if ( $cpoints != $args[ 'value' ] ) {
					return true;
				}
			}
			if ( $args[ 'relation' ] == "lower" ) {
				if ( $cpoints < $args[ 'value' ] ) {
					return true;
				}
			}			
		}
		
		public function survey_conditions_shortcodes( $atts, $content = null ) {
			global $wpdb;
			$args =  shortcode_atts( array(
					'id' => '',
					'condition' => '',
					'relation' => '',
					'value' => '',
					'advanced' => ''
				), $atts );
			if ( empty( $args[ 'id' ] ) ) {
				return( __( 'Conditional Shortcode must contain the survey ID!', MODAL_SURVEY_TEXT_DOMAIN ) );
			}
			if ( ( empty( $args[ 'condition' ] ) && empty( $args[ 'relation' ] ) && empty( $args[ 'value' ] ) ) && empty( $args[ 'advanced' ] ) ) {
				return( __( 'Conditional Shortcode must contain the simple or the advanced conditions!', MODAL_SURVEY_TEXT_DOMAIN ) );
			}
			$cpoints = 0;
			$mscuid = "";
			if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {			
				$mscuid = $_COOKIE[ 'ms-uid' ];
			}
			$fullrecords = modal_survey::survey_answers_shortcodes( 
				array ( 'id' => $args[ 'id' ], 'data' => 'full-records', 'style' => 'plain', 'uid' => $mscuid, 'pure' => 'true', 'session' => 'last'  )
			);
			if ( $args[ 'advanced' ] != '' ) {
				if ( strpos( strtolower( $args[ 'advanced' ] ), ' and ' ) !== false ) {
					$adv_conds_and = explode( " and ", strtolower( $args[ 'advanced' ] ) );
				}
				if ( strpos( strtolower( $args[ 'advanced' ] ), ' or ' ) !== false ) {
					$adv_conds_or = explode( " or ", strtolower( $args[ 'advanced' ] ) );
				}
				if ( ! empty( $adv_conds_and ) && ! empty( $adv_conds_or ) ) {
					return( __( 'Using AND OR in the same condition is currently not supported, you can use one of them.', MODAL_SURVEY_TEXT_DOMAIN ) );
				}
				if ( ! empty( $adv_conds_and ) && empty( $adv_conds_or ) ) {
					$adv_conds = $adv_conds_and;
					$adv_conds_type = "AND";
				}
				if ( empty( $adv_conds_and ) && ! empty( $adv_conds_or ) ) {
					$adv_conds = $adv_conds_or;
					$adv_conds_type = "OR";
				}
				$adv_cond_result = false;
				if ( isset( $adv_conds ) ) {
					foreach( $adv_conds as $ac ) {
						if ( strpos( $ac, "&lt;" ) !== false ) {
							$adv_relation = '&lt;';
							$adv_relation_text = "lower";
						}
						elseif ( strpos( $ac, "<" ) !== false ) {
							$adv_relation = '&lt;';
							$adv_relation_text = "lower";
						}
						elseif ( strpos( $ac, "!=" ) !== false ) {
							$adv_relation = '=';
							$adv_relation_text = "notequal";
						}
						elseif ( strpos( $ac, "=" ) !== false ) {
							$adv_relation = '=';
							$adv_relation_text = "equal";
						}
						elseif ( strpos( $ac, "&gt;" ) !== false ) {
							$adv_relation = '&gt;';
							$adv_relation_text = "higher";
						}
						elseif ( strpos( $ac, ">" ) !== false ) {
							$adv_relation = '&gt;';
							$adv_relation_text = "higher";
						}
						elseif ( ! isset( $adv_relation ) ) {
							return( __( 'Conditional relation sign is required to create a condition.', MODAL_SURVEY_TEXT_DOMAIN ) );
						}
						$adv_elements = explode( $adv_relation, $ac );
						$args[ 'condition' ] = trim( $adv_elements[ 0 ] );
						$args[ 'value' ] = trim( $adv_elements[ 1 ] );
						$args[ 'relation' ] = $adv_relation_text;
						$adv_res = $this->analyze_advanced_conditions( $args, $fullrecords);
						if ( $adv_conds_type == "OR" && $adv_res == true ) {
							return( do_shortcode( $content ) );
						}
						if ( $adv_conds_type == "AND" && $adv_res == false ) {
							return false; //replaces break; PHP7+
						}
					}
				}
				if ( ! isset( $adv_conds_type ) ) {
					return;
				}
				if ( $adv_conds_type == "AND" && $adv_res == true ) {
					return( do_shortcode( $content ) );
				}
			}
			else {			
				if ( $args[ 'condition' ] == "finalscore" ) {
					foreach( $fullrecords as $fr ) {
						foreach( $fr[ 'datas' ] as $frd ) {
							if ( $frd[ 'selected' ] == "true" ) {
								if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
									$cpoints += $frd[ 'score' ];
								}
							}
						}
					}
				}
				elseif ( $args[ 'condition' ] == "correctanswers" ) {
					foreach( $fullrecords as $fr ) {
						foreach( $fr[ 'datas' ] as $frd ) {
							if ( $frd[ 'selected' ] == "true" && $frd[ 'correct' ] == "true" ) {
								if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
									$cpoints++;
								}
							}
						}
					}					
				}
				elseif ( strpos( $args[ 'condition' ], 'questionscore' ) !== false ) {
					$index = explode( "_", $args[ 'condition' ] );
					foreach( $fullrecords as $key => $fr ) {
						if ( $key == ( $index[ 1 ] - 1 ) ) {
							foreach( $fr[ 'datas' ] as $frd ) {
								if ( $frd[ 'selected' ] == "true" ) {
									if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
										$cpoints = $frd[ 'score' ];
									}
								}
							}
						}
					}
				}
				elseif ( strpos( $args[ 'condition' ], 'questionanswer' ) !== false ) {
					$index = explode( "_", $args[ 'condition' ] );
					foreach( $fullrecords as $key => $fr ) {
						if ( $key == ( $index[ 1 ] - 1 ) ) {
							$aid = 0;
							foreach( $fr[ 'datas' ] as $frd ) {
								$aid++;
								if ( $frd[ 'selected' ] == "true" ) {
									if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
										$cpoints = $aid;
									}
								}
							}
						}
					}
				}
				elseif ( strpos( $args[ 'condition' ], 'categoryscore' ) !== false ) {
					$c_math = explode( "+", $args[ 'condition' ] );
					foreach ( $c_math as $math_elements ) {
						$index = explode( "_", $math_elements );
						foreach( $fullrecords as $fr ) {
							$category = "";
							preg_match_all( "/\[([^\]]*)\]/", $fr[ 'title' ], $cat );
							if ( isset( $cat[ 1 ][ 0 ] ) ) {
								$category = strtolower( $cat[ 1 ][ 0 ] );
							}
							foreach( $fr[ 'datas' ] as $frd ) {
								if ( ! isset( $frd[ 'status' ] ) || ( $frd[ 'status' ] != "inactive" )  ) {
									preg_match_all( "/\[([^\]]*)\]/", $frd[ 'answer' ], $acat );
									if ( isset( $acat[ 1 ][ 0 ] ) ) {
										$category = strtolower( $acat[ 1 ][ 0 ] );
									}
									if ( $frd[ 'selected' ] == "true" && ! empty( $category ) ) {
										if ( ! isset( $cats[ $category ] ) ) {
											$cats[ $category ] = 0;
										}
										$cats[ $category ] += $frd[ 'score' ];
									}
								}
							}
						}				
						if ( isset( $cats[ strtolower( $index[ 1 ] ) ] ) ) {
							$cpoints += $cats[ strtolower( $index[ 1 ] ) ];
						}
					}
				}
				if ( $args[ 'relation' ] == "highest" ) {
					if ( ! isset( $cats ) ) {
						return;
					}
					$tempcats = $cats;
					if ( ( $key = array_search( "-", $tempcats ) ) !== false ) {
						unset( $tempcats[ $key ] );
					}
					$max = array_keys( $tempcats, max( $tempcats ));
					if ( $max[ 0 ] == strtolower( $index[ 1 ] ) ) {
						return( do_shortcode( $content ) );
					}
				}
				if ( $args[ 'relation' ] == "lowest" ) {
					if ( ! isset( $cats ) ) {
						return;
					}
					$tempcats = $cats;
					if ( ( $key = array_search( "-", $tempcats ) ) !== false ) {
						unset( $tempcats[ $key ] );
					}
					$min = array_keys( $tempcats, min( $tempcats ) );
					if ( $min[ 0 ] ==  strtolower( $index[ 1 ] ) ) {
						return( do_shortcode( $content ) );
					}
				}
				if ( $args[ 'relation' ] == "higher" ) {
					if ( $cpoints > $args[ 'value' ] ) {
						return( do_shortcode( $content ) );
					}
				}
				if ( $args[ 'relation' ] == "equal" ) {
					$between = explode( "-", $args[ 'value' ] );
					if ( is_array( $between ) && isset( $between[ 1 ] ) ) {
						if ( $cpoints >= $between[ 0 ] && $cpoints <= $between[ 1 ] ) {
							return( do_shortcode( $content ) );
						}							
					}
					else {
						if ( $cpoints == $args[ 'value' ] ) {
							return( do_shortcode( $content ) );
						}
					}
				}
				if ( $args[ 'relation' ] == "notequal" ) {
					if ( $cpoints != $args[ 'value' ] ) {
						return( do_shortcode( $content ) );
					}
				}
				if ( $args[ 'relation' ] == "lower" ) {
					if ( $cpoints < $args[ 'value' ] ) {
							return( do_shortcode( $content ) );
					}
				}
			}
		}
		
		public function survey_records_shortcodes( $atts ) {
			global $wpdb, $msplugininit_answer_array;
			extract( shortcode_atts( array(
					'id' => '-1',
					'data' => 'name',
					'qid' => '',
					'aid' => '',
					'uid' => 'true',
					'session' => 'last'
				), $atts, 'survey_records' ) );	
				if ( ! isset( $atts[ 'data' ] ) ) {
					$atts[ 'data' ] = 'name';
				}
				if ( ! isset( $atts[ 'uid' ] ) ) {
					$atts[ 'uid' ] = 'true';
				}
				if ( ! isset( $atts[ 'session' ] ) ) {
					$atts[ 'session' ] = 'last';
				}
				if ( ! isset( $atts[ 'qid' ] ) ) {
					$atts[ 'qid' ] = '';
				}
				if ( ! isset( $atts[ 'aid' ] ) ) {
					$atts[ 'aid' ] = '';
				}
				$ssuid = "";
			$records = modal_survey::survey_answers_shortcodes( 
					array ( 'id' => $atts[ 'id' ], 'data' => 'full-records', 'style' => 'plain', 'limited' => 'no', 'uid' => $atts[ 'uid' ], 'title' => '<span>', 'score' => 'true', 'session' => $atts[ 'session' ] )
					);
			if ( $atts[ 'session' ] == "last" ) {
				if ( $atts[ 'uid' ] == "true" ) {
					if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {
						$ssuid = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE id = %s ", $_COOKIE[ 'ms-uid' ] ) );
					}
				}
				elseif ( $atts[ 'uid' ] != "" ) {
						$ssuid = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE id = %s ", $atts[ 'uid' ] ) );				
				}
				$last_session = "SELECT samesession FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = '" . $atts[ 'id' ] . "' AND uid = '" . $ssuid . "' ORDER BY time DESC";
				$atts[ 'session' ] = $wpdb->get_var( $last_session );
				$lastvotes = " AND mspd.samesession = '" . $atts[ 'session' ] . "' ";
			}
			$sql_u_t = "SELECT DATE_FORMAT( time,'%Y-%m-%d') as date, DATE_FORMAT( time,'%Y-%m-%d %H:%i') as datetime FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = '" . $atts[ 'id' ] . "' AND uid = '" . $ssuid . "' ORDER BY time DESC";
			$a_sql_u_t = $wpdb->get_row( $sql_u_t );
			$sql_u = "SELECT username, email, name, custom FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE autoid = '" . $ssuid . "'";
			$a_sql_u = $wpdb->get_row( $sql_u );
			if ( isset( $a_sql_u_t->date ) ) {
				$a_sql_u->date = $a_sql_u_t->date;
			}
			if ( isset( $a_sql_u_t->datetime ) ) {
				$a_sql_u->datetime = $a_sql_u_t->datetime;
			}
			if ( ! empty( $a_sql_u->{$atts[ 'data' ]} ) ) {
				return $a_sql_u->{$atts[ 'data' ]};
			}
			$u_custom = array();
			if ( ! empty( $a_sql_u->custom ) ) {
				$u_custom = unserialize( $a_sql_u->custom );
			}
			if ( ! empty( $u_custom ) ) {
				foreach( $u_custom as $cskey => $ucs) {
					if ( strtolower( $cskey ) == strtolower( $atts[ 'data' ] ) ) {
						return $ucs;
					}
				}
			}
			
			if ( $atts[ 'qid' ] != "" ) {
				if ( ! empty( $records[ $atts[ 'qid' ] - 1 ][ $atts[ 'data' ] ] ) ) {
					return $records[ $atts[ 'qid' ] - 1 ][ $atts[ 'data' ] ];
				}
			}
			$sr_selans = "";
			if ( $atts[ 'qid' ] != "" && $atts[ 'aid' ] != "" ) {
				if ( $atts[ 'aid' ] == "selected" ) {
					foreach( $records[ $atts[ 'qid' ] - 1 ][ 'datas' ] as $qs ) {
						if ( $qs[ 'selected' ] == "true" ) {
							$sr_selans .= $qs[ $atts[ 'data' ] ] . ", ";
						}
					}
					return substr( $sr_selans, 0, ( strlen( $sr_selans ) - 2 ) );
				}
				else {
					if ( ! empty( $records[ $atts[ 'qid' ] - 1 ][ 'datas' ][ $atts[ 'aid' ] ][ $atts[ 'data' ] ] ) ) {
						return $records[ $atts[ 'qid' ] - 1 ][ 'datas' ][ $atts[ 'aid' ] ][ $atts[ 'data' ] ];
					}
				}
			}
			return __( 'Data doesn\'t exists', MODAL_SURVEY_TEXT_DOMAIN );
		}
		
		public function survey_answers_shortcodes( $atts ) {
			global $wpdb, $msplugininit_answer_array, $current_user;
			$unique_key = mt_rand();
			$result = "";$cat_count = array();
			if ( isset( $_REQUEST[ 'sspcmd' ] ) && $_REQUEST[ 'sspcmd' ] == "displaychart" ) {
				$unique_key = "endcontent";
			}
			extract( shortcode_atts( array(
					'id' => '-1',
					'style' => 'progressbar',
					'data' => 'full',
					'qid' => '1',
					'aid' => '',
					'titles' => '',
					'compare' => '',
					'bgcolor' => '',
					'cbgcolor' => '',
					'color' => '',
					'hidecounter' => 'no',
					'uid' => 'false',
					'limited' => 'no',
					'max' => '',
					'sort' => '',
					'title' => '<h3>',
					'init' => '',
					'hidequestion' => 'no',
					'pure' => 'false',
					'alternativedatas' => 'true',
					'score' => 'false',
					'top' => '',
					'session' => '',
					'legend' => 'false',
					'tooltip' => 'false',
					'percentage' => 'false',
					'showhidden' => 'false',
					'progress' => 'false',
					'catmax' => 'false'
				), $atts, 'survey_answers' ) );
				if ( ! isset( $atts[ 'style' ] ) ) {
					$atts[ 'style' ] = 'progressbar';
				}
				if ( ! isset( $atts[ 'sort' ] ) ) {
					$atts[ 'sort' ] = '';
				}
				if ( ! isset( $atts[ 'title' ] ) ) {
					$atts[ 'title' ] = '<h3 class="survey_header">';
				}
				if ( ! isset( $atts[ 'qid' ] ) && ( $atts[ 'style' ] != "plain" ) ) {
					$atts[ 'qid' ] = '1';
				}
				else {
					if ( ! isset( $atts[ 'qid' ] ) ) {
						$atts[ 'qid' ] = "";
					}
				}
				if ( ! isset( $atts[ 'aid' ] ) ) {
					$atts[ 'aid' ] = '';
				}
				if ( ! isset( $atts[ 'titles' ] ) ) {
					$atts[ 'titles' ] = '';
				}
				if ( ! isset( $atts[ 'compare' ] ) ) {
					$atts[ 'compare' ] = 'false';
				}
				if ( ! isset( $atts[ 'data' ] ) ) {
					$atts[ 'data' ] = 'full';
				}
				if ( ! isset( $atts[ 'hidecounter' ] ) ) {
					$atts[ 'hidecounter' ] = 'no';
				}
				if ( ! isset( $atts[ 'uid' ] ) ) {
					$atts[ 'uid' ] = 'false';
				}
				if ( ! isset( $atts[ 'limited' ] ) ) {
					$atts[ 'limited' ] = 'no';
				}
				if ( ! isset( $atts[ 'max' ] ) ) {
					$atts[ 'max' ] = '0';
				}
				if ( ! isset( $atts[ 'postid' ] ) ) {
					$atts[ 'postid' ] = '';
				}
				if ( ! isset( $atts[ 'hidequestion' ] ) ) {
					$atts[ 'hidequestion' ] = 'no';
				}
				if ( ! isset( $atts[ 'bgcolor' ] ) ) {
					$atts[ 'bgcolor' ] = '';
				}
				if ( ! isset( $atts[ 'cbgcolor' ] ) ) {
					$atts[ 'cbgcolor' ] = '';
				}
				if ( ! isset( $atts[ 'color' ] ) ) {
					$atts[ 'color' ] = '';
				}
				if ( ! isset( $atts[ 'init' ] ) ) {
					$atts[ 'init' ] = '';
				}
				if ( ! isset( $atts[ 'pure' ] ) ) {
					$atts[ 'pure' ] = 'false';
				}
				if ( ! isset( $atts[ 'alternativedatas' ] ) ) {
					$atts[ 'alternativedatas' ] = 'true';
				}
				if ( ! isset( $atts[ 'percentage' ] ) ) {
					$atts[ 'percentage' ] = 'false';
				}
				if ( ! isset( $atts[ 'after' ] ) ) {
					$atts[ 'after' ] = '';
				}
				if ( ! isset( $atts[ 'score' ] ) ) {
					$atts[ 'score' ] = 'false';
				}
				if ( ! isset( $atts[ 'top' ] ) ) {
					$atts[ 'top' ] = '';
				}
				if ( ! isset( $atts[ 'session' ] ) ) {
					$atts[ 'session' ] = '';
				}
				if ( ! isset( $atts[ 'legend' ] ) ) {
					$atts[ 'legend' ] = 'false';
				}
				if ( ! isset( $atts[ 'tooltip' ] ) ) {
					$atts[ 'tooltip' ] = 'false';
				}
				if ( ! isset( $atts[ 'showhidden' ] ) ) {
					$atts[ 'showhidden' ] = 'false';
				}
				if ( ! isset( $atts[ 'progress' ] ) ) {
					$atts[ 'progress' ] = 'false';
				}
				if ( ! isset( $atts[ 'catmax' ] ) ) {
					$atts[ 'catmax' ] = 'false';
				}
				if ( ! is_single() && !is_page() && $atts[ 'limited' ] == "yes" ) {
					return('');
				}
				$args = array(
					'id' => $atts[ 'id' ],
					'style' => $atts[ 'style' ],
					'sort' => $atts[ 'sort' ],
					'title' => $atts[ 'title' ],
					'data' => $atts[ 'data' ],
					'qid' => $atts[ 'qid' ],
					'aid' => $atts[ 'aid' ],
					'hidecounter' => $atts[ 'hidecounter' ],
					'max' => $atts[ 'max' ],
					'postid' => $atts[ 'postid' ],
					'hidequestion' => $atts[ 'hidequestion' ],
					'uid' => $atts[ 'uid' ],
					'limited' => $atts[ 'limited' ],
					'bgcolor' => $atts[ 'bgcolor' ],
					'cbgcolor' => $atts[ 'cbgcolor' ],
					'color' => $atts[ 'color' ],
					'titles' => $atts[ 'titles' ],
					'init' => $atts[ 'init' ],
					'compare' => $atts[ 'compare' ],
					'percentage' => $atts[ 'percentage' ],
					'after' => $atts[ 'after' ],
					'pure' => $atts[ 'pure' ],
					'alternativedatas' => $atts[ 'alternativedatas' ],
					'score' => $atts[ 'score' ],
					'top' => $atts[ 'top' ],
					'session' => $atts[ 'session' ],
					'legend' => $atts[ 'legend' ],
					'tooltip' => $atts[ 'tooltip' ],
					'showhidden' => $atts[ 'showhidden' ],
					'progress' => $atts[ 'progress' ],
					'catmax' => $atts[ 'catmax' ]
					);
			if ( ( $args[ 'data' ] == 'score' || $args[ 'data' ] == 'average-score' || $args[ 'data' ] == 'rating' ) && ( $args[ 'style' ] != "plain" ) ) {
				$atts[ 'qid' ] = '';
				$args[ 'qid' ] = '';
			}
			//retrieve last survey completion for the current user
			if ( ! empty( $current_user->user_login ) && $args[ 'uid' ] != "false" && ! isset( $_COOKIE[ 'ms-uid' ] ) && $args[ 'session' ] == "last" && $args[ 'uid' ] == "true" ) {
				$args[ 'uid' ] = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE username = %s", $current_user->user_login ) );
			}
			$answercats = array();
			$ssuid = "";
			$lastvotes = "";
			$timer = array();
			$args[ 'title' ] = html_entity_decode( $args[ 'title' ] );
			$title_c = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $args['title']);
			$args[ 'title_c' ] = str_replace( "<", "</", $title_c );
			$sdatas = array();
			if( strtoupper( $args[ 'sort' ] ) == "DESC" ) {
				$sort = "count DESC";
			}
			elseif( strtoupper( $args[ 'sort' ] ) == "ASC" ) {
				$sort = "count ASC";
			}
			else {
				$sort = "autoid ASC";
			}
			if ( $args[ 'session' ] == "last" ) {
				if ( $args[ 'uid' ] == "true" ) {
					if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {
						$ssuid = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE id = %s ", $_COOKIE[ 'ms-uid' ] ) );
					}
				}
				elseif ( $args[ 'uid' ] != "" ) {
						$ssuid = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE id = %s ", $args[ 'uid' ] ) );				
				}
				$last_session = "SELECT samesession FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = '" . $args[ 'id' ] . "' AND uid = '" . $ssuid . "' ORDER BY time DESC";
				$args[ 'session' ] = $wpdb->get_var( $last_session );
				$lastvotes = " AND mspd.samesession = '" . $args[ 'session' ] . "' ";
			}
			if ( $args[ 'style' ] == 'plain' ) {
				if ( $args[ 'data' ] == 'full' || $args[ 'data' ] == 'full-records' ) {
				if ( $args[ 'data' ] == 'full-records' ) {
					$result = array();
				}
				$sql = "SELECT *, msq.id as question_id FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE mss.id='" . $args[ 'id' ] . "' ORDER BY msq.id ASC";
				$q_sql = $wpdb->get_results( $sql );
				if ( $args[ 'data' ] != 'full-records' ) {
					$result = "<div class='ms-plain-results'>";
				}
						$finaltimer = 0;
						$finalscore = 0;
						foreach( $q_sql as $key1=>$ars ) {
						//display individual records start
						if ( $args[ 'uid' ] != "false" ) {
							if ( $args[ 'uid' ] == "true" ) {
								if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {
									$args[ 'uid' ] = $_COOKIE[ 'ms-uid' ];
								}
							}
							$samesession = "";
							if ( ! empty( $args[ 'session' ] ) ) {
								$samesession = " AND mspd.samesession = " . $args[ 'session' ];
							}
							$sql_u = "SELECT mspd.qid, mspd.aid, mspd.timer FROM " . $wpdb->base_prefix . "modal_survey_participants_details mspd LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants msp on mspd.uid = msp.autoid WHERE mspd.sid = '" . $args[ 'id' ] . "' AND mspd.qid = '" . $ars->question_id . "' AND msp.id = '" . $args[ 'uid' ] . "' " . $samesession . " ORDER BY autoid ASC";
							$a_sql_u = $wpdb->get_results( $sql_u );
							if ( ! empty( $a_sql_u ) ) {
								foreach( $a_sql_u as $key2u=>$asu ) {
									$user_votes[ $asu->qid ][] = $asu->aid;
									$timer[ $asu->qid ] = $asu->timer;
								}
							}
							else {
								if ( $args[ 'alternativedatas' ] == "false" ) {
									return ( "" );
								}
							}
						}
						if ( $args[ 'data' ] == 'full-records' ) {
						//display individual records end
								if ( $args[ 'pure' ] == "false" ) {
									$result[ $key1 ][ 'title' ] = preg_replace( '/\[.*\]/', '', $ars->question );
								}
								else {
									$result[ $key1 ][ 'title' ] = $ars->question;
								}
							$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $ars->question_id . "' ORDER BY " . $sort;
							$a_sql = $wpdb->get_results( $sql );
								foreach($a_sql as $key2=>$as) {
									$allcount = 0;
									$aoptions = unserialize( $as->aoptions );
									foreach($a_sql as $aas){
										$allcount = $allcount + $aas->count;
									}
									$uv_ans = "";
									$selected = "false";
									if ( isset( $user_votes ) ) {
										$thisuv = $user_votes;
										if ( $aoptions[ 0 ] == "open" ) {
											if ( isset( $thisuv[ $ars->question_id ] ) ) {
												foreach( $thisuv[ $ars->question_id ] as $key=>$uvarray ) {
													$uv_ans = explode( "|", $uvarray );
													if ( ! in_array( $uv_ans[ 0 ], $thisuv[ $ars->question_id ] ) ) {
														$thisuv[ $ars->question_id ][ $key ] = $uv_ans[ 0 ];
														$as->answer = __( 'Other', MODAL_SURVEY_TEXT_DOMAIN );
													}
												}
											}
										}
										else {
												if ( isset( $thisuv[ $ars->question_id ] ) ) {
													foreach( $thisuv[ $ars->question_id ] as $key=>$uvarray ) {
														$uv_ans_rec = explode( "|", $uvarray );
														if ( ! in_array( $uv_ans_rec[ 0 ], $thisuv[ $ars->question_id ] ) && isset( $uv_ans_rec[ 1 ] ) ) {
															$thisuv[ $ars->question_id ][ ] = $uv_ans_rec[ 0 ];
														}
													}
												}
										}
										if ( isset( $thisuv[ $ars->question_id ] ) && is_array( $thisuv[ $ars->question_id ] ) && ( in_array( $as->autoid, $thisuv[ $ars->question_id ] ) ) ) {
											$selected = "true";
											if ( isset( $uv_ans[ 1 ] ) ) {
												$as->answer .= ': ' . $uv_ans[ 1 ];
											}
										}
									}
									if ( $args[ 'pure' ] == "false" ) {
										$result[ $key1 ][ 'datas' ][ $key2 ][ 'answer' ] = ( preg_replace( '/\[.*\]/', '', $as->answer ) ? ( preg_replace( '/\[.*\]/', '', $as->answer ) ) : __( 'Not Specified', MODAL_SURVEY_TEXT_DOMAIN ) );
									}
									else {
										$result[ $key1 ][ 'datas' ][ $key2 ][ 'answer' ] = $as->answer;
									}
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'survey' ] = $q_sql[ 0 ]->name;
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'selected' ] = $selected;
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'votes' ] = $as->count;
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'score' ] = $aoptions[ 4 ];
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'correct' ] = ( ! empty( $aoptions[ 5 ] ) ? 'true' : 'false' );
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'status' ] = ( ( ! isset( $aoptions[ 8 ] ) || ( $aoptions[ 8 ] == "1" ) ) ? 'inactive' : 'active' );
									$result[ $key1 ][ 'datas' ][ $key2 ][ 'percentage' ] = ( $allcount > 0 ? ( round( ( $as->count / $allcount ) * 100, 2 ) ) : '0' ) . "%";
								}
						}
						else {
							//display individual records end
							if ( isset( $timer[ $key1 + 1 ] ) && $timer[ $key1 + 1 ] >= 0 ) {
								$finaltimer += $timer[ $key1 + 1 ];
							}
							$result .= "<div class='question-onerow'><div class='ms-question-row'><div class='ms-question-text'>" . $args[ 'title' ] . preg_replace( '/\[.*\]/', '', $ars->question ) . $args[ 'title_c' ] . "</div><div class='ms-question-block1'></div><div class='ms-question-block2'>" . ( isset( $timer[ $key1 + 1 ] ) && $finaltimer > 0  ? ( __( 'Time Required', MODAL_SURVEY_TEXT_DOMAIN ) . ": ". $timer[ $key1 + 1 ] . __( 'sec', MODAL_SURVEY_TEXT_DOMAIN ) ) : '' ) . "</div></div>";
								$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $ars->question_id . "' ORDER BY " . $sort;
								$a_sql = $wpdb->get_results( $sql );
								//shortcode extension to get votes by post ID
								if ( $args[ 'postid' ] != '' ) {
									foreach( $a_sql as $aaskey=>$bas ) {
										$a_sql[ $aaskey ]->count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( aid ) FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = %d AND qid = %d AND aid = %d AND postid = %d", $args[ 'id' ], $ars->question_id, $bas->autoid, $args[ 'postid' ] ) );
									}
								}
								//start - remove inactive answers
								foreach($a_sql as $aaskey=>$bas){
									$baoptions = unserialize( $bas->aoptions );								
									if ( isset( $baoptions[ 8 ] ) && $baoptions[ 8 ] == "1" ) {
										unset( $a_sql[ $aaskey ] );
									}
								}
								//end - remove inactive answers					

								foreach( $a_sql as $key2=>$as ) {
									$allcount = 0;
									$aoptions = unserialize( $as->aoptions );
									foreach( $a_sql as $aas ) {
										$allcount = $allcount + $aas->count;
									}
									$uv_ans = "";$uv_ans_rec = "";
									$selected = "";
									if ( isset( $user_votes ) ) {
										$thisuv = $user_votes;
										if ( $aoptions[ 0 ] == "open" ) {
											if ( isset( $thisuv[ $ars->question_id ] ) ) {
												foreach( $thisuv[ $ars->question_id ] as $key=>$uvarray ) {
													$uv_ans = explode( "|", $uvarray );
													if ( ! in_array( $uv_ans[ 0 ], $thisuv[ $ars->question_id ] ) ) {
														$thisuv[ $ars->question_id ][ $key ] = $uv_ans[ 0 ];
														$as->answer = __( 'Other', MODAL_SURVEY_TEXT_DOMAIN );
													}
												}
											}
										}
										else {
											if ( isset( $thisuv[ $ars->question_id ] ) ) {
												foreach( $thisuv[ $ars->question_id ] as $key=>$uvarray ) {
													$uv_ans_rec = explode( "|", $uvarray );
													if ( ! in_array( $uv_ans_rec[ 0 ], $thisuv[ $ars->question_id ] ) && isset( $uv_ans_rec[ 1 ] ) ) {
														$thisuv[ $ars->question_id ][ ] = $uv_ans_rec[ 0 ];
													}
												}
											}
										}
										if ( isset( $thisuv[ $ars->question_id ] ) && is_array( $thisuv[ $ars->question_id ] ) && ( in_array( $as->autoid, $thisuv[ $ars->question_id ] ) ) ) {
											$selected = " ms-answer-row-selected";
											if ( isset( $uv_ans[ 1 ] ) ) {
												$as->answer .= ': ' . $uv_ans[ 1 ];
											}
											$finalscore += $aoptions[ 4 ];
										}
									}
									$score_output = "";
									if ( $args[ 'score' ] == 'true' ) {
										$score_output = "<div class='ms-answer-score modal_survey_tooltip' title='" . __( 'Answer Score', MODAL_SURVEY_TEXT_DOMAIN ) . "'>" . $aoptions[ 4 ] . "</div>";
									}
									$result .= "<div class='ms-answer-row" . $selected . "'><div class='ms-answer-text'>" . ( preg_replace( '/\[.*\]/', '', $as->answer ) ? ( preg_replace( '/\[.*\]/', '', $as->answer ) ) : __( 'Not Specified', MODAL_SURVEY_TEXT_DOMAIN ) ) . "</div><div class='ms-answer-count modal_survey_tooltip' title='" . __( 'Global Votes', MODAL_SURVEY_TEXT_DOMAIN ) . "'>" . $as->count . "</div><div class='ms-answer-percentage modal_survey_tooltip' title='" . __( 'Global Percentage', MODAL_SURVEY_TEXT_DOMAIN ) . "'>" . ( $allcount > 0 ? ( round( ( $as->count / $allcount ) * 100, 2 ) ) : '0' ) . "%" . "</div>" . $score_output . "</div>";
								}
								$result .= "</div>";
								if ( $key1 == count( $q_sql ) - 1 ) {
									$ftimerhtml = "<span class='final-time-title'>" . __( 'Final Time', MODAL_SURVEY_TEXT_DOMAIN ) . ":</span> <span class='final-time'>" . $finaltimer . "" . __( 'sec', MODAL_SURVEY_TEXT_DOMAIN ) . "</span>";
									$result .= "<div class='final-result'>";
									if ( $finalscore != "" ) {
										$result .= "<span class='final-score-title'>" . __( 'Total Score', MODAL_SURVEY_TEXT_DOMAIN ) . ":</span> <span class='final-score'>" . $finalscore . "</span> ";
									}
									$result .= ( $finaltimer > 0 ? $ftimerhtml : "" );
									$result .= "</div>";
								}
							}
						}
				}
				if ( $args[ 'data' ] == 'question' ) {
				$sql = "SELECT *, msq.id as question_id FROM ".$wpdb->base_prefix."modal_survey_surveys mss LEFT JOIN ".$wpdb->base_prefix."modal_survey_questions msq on mss.id = msq.survey_id WHERE mss.id='".$args['id']."' ORDER BY msq.id ASC";
				$q_sql = $wpdb->get_results($sql);
					foreach( $q_sql as $key1=>$ars ) {
							if ( ( $key1 + 1 ) == $args[ 'qid' ] ) {
								if ( $atts[ 'init' ] == "true" ) {
									$this->initialize_plugin();
								}
								return(preg_replace('/\[.*\]/', '', $ars->question));
							}
					}
				}
				if ( $args[ 'data' ] == 'answer' || $args[ 'data' ] == 'answer_count' || $args[ 'data' ] == 'answer_percentage' ) {
						if ( $args[ 'aid' ] == '' && $args[ 'uid' ] == "true" ) {
							if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {
								$cmsuid = $_COOKIE[ 'ms-uid' ];
							}
							else {
								$cmsuid = "";
							}
								$fullrecords = modal_survey::survey_answers_shortcodes( 
									array ( 'id' => $args[ 'id' ], 'data' => 'full-records', 'style' => 'plain', 'uid' => $cmsuid, 'pure' => 'true'  )
								);
								$uans = array();$uans_output = "";
								foreach( $fullrecords[ $args[ 'qid' ] - 1 ][ 'datas' ] as $qss ) {
									if ( $qss[ 'selected' ] == "true" ) {
										$uans[] = $qss[ 'answer' ];
									}
								}
								foreach( $uans as $key=>$userans ) {
									$uans_output .= $userans;
									if ( $key + 1 < count( $uans ) ) {
										$uans_output .= ", ";
									}
								}
								return $uans_output;
						}					
						$sql = "SELECT * FROM ".$wpdb->base_prefix."modal_survey_answers WHERE survey_id = '".$args['id']."' AND question_id = '".$args['qid']."' ORDER BY " . $sort;
						$a_sql = $wpdb->get_results($sql);
						//shortcode extension to get votes by post ID
						if ( $args[ 'postid' ] != '' ) {
							foreach($a_sql as $aaskey=>$bas){
								$a_sql[ $aaskey ]->count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( aid ) FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = %d AND qid = %d AND aid = %d AND postid = %d", $args[ 'id' ], $ars->question_id, $bas->autoid, $args[ 'postid' ] ) );
							}
						}
						$allcount = 0;
						foreach( $a_sql as $aas ) {
							$allcount = $allcount + $aas->count;
						}
						foreach( $a_sql as $key2 => $as ) {
							if ( ( ( $key2 + 1 ) == $args[ 'aid' ] ) && ( ! empty( $args[ 'aid' ] ) ) ) {
								if ( $args[ 'data' ] == 'answer' ) {
									if ( $atts[ 'init' ] == "true" ) {
										$this->initialize_plugin();
									}
									return( preg_replace( '/\[.*\]/', '', $as->answer ) );
								}
								if ( $args[ 'data' ] == 'answer_count' ) {
									if ( $atts[ 'init' ] == "true" ) {
										$this->initialize_plugin();
									}
									return( $as->count );
								}
								if ( $args[ 'data' ] == 'answer_percentage' ) {
									if ( $allcount > 0 ) {
										if ( $atts[ 'init' ] == "true" ) {
											$this->initialize_plugin();
										}
										return( round( ( $as->count / $allcount ) * 100, 2 ) . '%' );
									}
									else {
										if ( $atts[ 'init' ] == "true" ) {
											$this->initialize_plugin();
										}
										return( '0%' );
									}
								}
							}
						}
						if ( $args[ 'data' ] == 'answer_count' ) {
							if ( $atts[ 'init' ] == "true" ) {
								$this->initialize_plugin();
							}
							return( $allcount );
						}
				}
				if ( $args[ 'data' ] == 'score' || $args[ 'data' ] == 'average-score' || $args[ 'data' ] == 'rating' ) {
					$totalsumscore = 0;
					$sql = "SELECT *,msq.id as question_id, msq.qoptions FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE mss.id='" . $args[ 'id' ] . "' ORDER BY msq.id ASC";
					$q_sql = $wpdb->get_results( $sql );
					foreach( $q_sql as $key1 => $ars ) {
						$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $ars->question_id . "' ORDER BY " . $sort;
						$a_sql = $wpdb->get_results( $sql );
						//shortcode extension to get votes by post ID
						if ( $args[ 'postid' ] != '' ) {
							foreach($a_sql as $aaskey=>$bas){
								$a_sql[ $aaskey ]->count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( aid ) FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = %d AND qid = %d AND aid = %d AND postid = %d", $args[ 'id' ], $ars->question_id, $bas->autoid, $args[ 'postid' ] ) );
							}
						}
						//display individual records start
						if ( $args[ 'uid' ] != "false" ) {
							if ( $args[ 'uid' ] == "true" ) {
								if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {
									$args[ 'uid' ] = $_COOKIE[ 'ms-uid' ];
								}
							}
							if ( is_numeric( $args[ 'uid' ] ) ) {
								$args[ 'uid' ] = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE autoid = %d ", $args[ 'uid' ] ) );
							}
							$sql_u = "SELECT mspd.qid, mspd.aid FROM " . $wpdb->base_prefix . "modal_survey_participants_details mspd LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants msp on mspd.uid = msp.autoid WHERE mspd.sid = '" . $args[ 'id' ] . "' AND mspd.qid = '" . $ars->question_id . "' AND msp.id = '" . $args[ 'uid' ] . "' " . $lastvotes . " ORDER BY autoid ASC";
							$a_sql_u = $wpdb->get_results( $sql_u );
							if ( ! empty( $a_sql_u ) ) {
								foreach( $a_sql_u as $key2u=>$asu ) {
									$uv_ans = explode( "|", $asu->aid );
									$user_votes[ $asu->qid ][] = $uv_ans[ 0 ];
								}
								foreach( $a_sql as $key2o=>$aso ) {
									if ( isset( $user_votes[ $aso->question_id ] ) && ( in_array( $aso->autoid, $user_votes[ $aso->question_id ] ) ) ) {
										$a_sql[ $key2o ]->count = 1;
									}
									else {
										$a_sql[ $key2o ]->count = 0;										
									}
								}
							}
							else {
								if ( $args[ 'alternativedatas' ] == "false" ) {
									return ( "" );
								}
							}
						}
						//display individual records end
						//start - remove inactive answers
						foreach($a_sql as $aaskey=>$bas){
							$baoptions = unserialize( $bas->aoptions );								
							if ( isset( $baoptions[ 8 ] ) && $baoptions[ 8 ] == "1" ) {
								unset( $a_sql[ $aaskey ] );
							}
						}
						//end - remove inactive answers					
						$qscore = 0;
						$summary = 0; $allratings = 0;
						foreach( $a_sql as $key2=>$as ) {
							if ( isset( $as->aoptions ) ) {
								$aoptions = unserialize( $as->aoptions );
								if ( is_numeric( $aoptions[ 4 ] ) ) {
									preg_match_all( "/\[([^\]]*)\]/", $as->answer, $acats );
									if ( ! empty( $acats[ 1 ] ) ) {
										$acats_list = explode( ",", $acats[ 1 ][ 0 ] );
										foreach ( $acats_list as $aca ) {
											if ( isset( $aca ) ) {
												if ( ! empty( $aca ) && ! is_numeric( $aca )  ) {
													if ( ! isset( $answercats[ $aca ] ) ) {
														$answercats[ $aca ] = 0;
													}
													if ( isset( $answercats[ $aca ] ) ) {
														$answercats[ $aca ] += $aoptions[ 4 ] * $as->count;
													}
												}
											}
										}
									}
								}
								if ( $aoptions[ 0 ] == "open" ) {
									$as->answer = __( 'Other', MODAL_SURVEY_TEXT_DOMAIN );
								}
							if ( ! empty( $aoptions[ 3 ] ) ) {
								//$as->answer = '<img src="' . $aoptions[ 3 ] . '">' . $as->answer;
								}
							}
							else {
								$aoptions[ 4 ] = 0;
							}
							if ( isset( $args[ 'titles' ] ) && $args[ 'titles' ] != "" ) {
								$titles = explode( ",", $args[ 'titles' ] );
							}
							else {
								$titles = "";
							}
							if ( ! isset( $titles[ $key1 ] ) || empty( $titles[ $key1 ] ) || $titles[ $key1 ] == "" ) {
								$titles[ $key1 ] = nl2br( $ars->question );
							}
							if ( $args[ 'data' ] == 'score' || $args[ 'data' ] == 'average-score' ) {
							if ( isset( $args[ 'titles' ] ) && $args[ 'titles' ] != "" ) {
								$titles = explode( ",", $args[ 'titles' ] );
							}
							else {
								$titles = "";
							}
							if ( ! isset( $titles[ $key1 ] ) || empty( $titles[ $key1 ] ) || $titles[ $key1 ] == "" ) {
								$titles[ $key1 ] = nl2br( $ars->question );
							}
							if ( isset( $aoptions[ 4 ] ) && is_numeric( $aoptions[ 4 ] ) ) {
									$qscore += $as->count * $aoptions[ 4 ];
								}
								else {
									$qscore += 0;								
								}
							}
							if ( $args[ 'data' ] == 'rating' ) {
							$summary += ( $key2 + 1 ) * $as->count;
							$allratings += $as->count;
							}
						}
						if ( $args[ 'data' ] == 'rating' ) {
							if ( $allratings == 0 ) {
								$exactvalue =  0;
								$decvalue = 0;
								$intvalue = 0;
							}
							else {
								$exactvalue =  ( $summary / $allratings );
								$decvalue = ceil( ( $summary / $allratings ) * 2 ) / 2;
								$intvalue = ( int ) $decvalue;
							}
							$allans_count = count( $a_sql ) - $intvalue;
							$qscore = number_format( $exactvalue, 2, '.', '' );
						}
						preg_match_all( "/\[([^\]]*)\]/", $titles[ $key1 ], $ques );
						if ( isset( $ques[ 1 ] ) ) {
							if ( ! empty( $ques[ 1 ] ) ) {
								foreach( $ques[ 1 ] as $perscat ) {
									$titles[ $key1 ] = str_replace( $perscat, "", $titles[ $key1 ] );
									if ( ! empty( $perscat ) ) {
										$titles[ $key1 ] = str_replace( array( "[", "]" ), "", trim( $perscat ) );
									}
								}
							}
						}
						$valexist = 0;
						if ( ! empty( $sdatas[ 0 ] ) ) {
								foreach ( $sdatas[ 0 ] as $qstkey=>$qst ) {
									if ( $qst[ 'answer' ] == $titles[ $key1 ] ) {
										 if ( $args[ 'data' ] == 'average-score' ) {
											$allcount = 0;
											foreach($a_sql as $aas){
												$allcount = $allcount + $aas->count;
											}
											if ( $allcount > 0 ) {
												$qscore = number_format( $qscore / $allcount, 2, '.', '' );
											}
											else {
												$qscore = 0;
											}
										 }
										$sdatas[ 0 ][ $qstkey ][ 'count' ] = $sdatas[ 0 ][ $qstkey ][ 'count' ] + $qscore;
										$valexist = 1;
									}
								}
						}
						if ( $valexist == 0 ) {
							if ( strlen( $titles[ $key1 ] ) > 50 ) {
								$titles[ $key1 ] = substr( $titles[ $key1 ], 0, 50 ) . "...";
							}
							if ( $titles[ $key1 ] != "-" ) {
								 if ( $args[ 'data' ] == 'average-score' ) {
									$allcount = 0;
									foreach($a_sql as $aas){
										$allcount = $allcount + $aas->count;
									}
									if ( $allcount > 0 ) {
										$qscore = number_format( $qscore / $allcount, 2, '.', '' );
									}
									else {
										$qscore = 0;
									}
								 }
								$sdatas[ 0 ][ $key1 ] = array( 'answer' => $titles[ $key1 ], 'count'=> $qscore );
							}
						}
					}
					if ( $args[ 'qid' ] == "" && $args[ 'aid' ] == "" && ! empty( $sdatas ) ) {
						foreach( $sdatas[ 0 ] as $sd ) {
							$totalsumscore += $sd[ 'count' ];
						}
						if ( $atts[ 'init' ] == "true" ) {
							$this->initialize_plugin();
						}
						if ( ! empty( $args[ 'max' ] ) ) {
							if ( $args[ 'progress' ] == "true" ) {
								$additional_params = "";
								if ( $args[ 'bgcolor' ] ) {
									$bgcls = explode( ",", $args[ 'bgcolor' ] );
									if ( isset( $bgcls[ 0 ] ) ) {
										$additional_params .= ' data-foregroundColor="' . $bgcls[ 0 ] . '"';
									}
									if ( isset( $bgcls[ 1 ] ) ) {
										$additional_params .= ' data-backgroundColor="' . $bgcls[ 1 ] . '"';
									}
									if ( isset( $bgcls[ 2 ] ) ) {
										$additional_params .= ' data-targetColor="' . $bgcls[ 2 ] . '"';
									}
									if ( isset( $bgcls[ 3 ] ) ) {
										$additional_params .= ' data-fontColor="' . $bgcls[ 3 ] . '"';
									}
								}
								return ( '<div id="ms-progress-circle' . $args[ 'id' ] . '" class="modalsurvey-progress-circle" data-animation="1" ' . $additional_params . ' data-animationStep="5" data-percent="' . ( intval( ( $totalsumscore / $args[ 'max' ] ) * 100 ) ) . '"></div>' );
							}
							else {
								return ( intval( ( $totalsumscore / $args[ 'max' ] ) * 100 ) );
							}
						}
						if ( empty( $totalsumscore ) ) {
							$totalsumscore = 0;
						}
						return ( $totalsumscore );
					}
					else {
						if ( empty( $args[ 'qid' ] ) ) {
							return 0;
						}
					}
					if ( $args[ 'qid' ] != "" && $args[ 'aid' ] == "" ) {
						if ( ! is_numeric( $args[ 'qid' ] ) ) {
							if ( $args[ 'uid' ] != "false" ) {
								$fullrecords = modal_survey::survey_answers_shortcodes( 
									array ( 'id' => $args[ 'id' ], 'data' => 'full-records', 'style' => 'plain', 'uid' => ( isset( $_COOKIE[ 'ms-uid' ] ) ? $_COOKIE[ 'ms-uid' ] : '') , 'pure' => 'true', 'session' => $atts[ 'session' ] )
								);
								foreach( $fullrecords as $fr ) {
									$category = "";
									preg_match_all( "/\[([^\]]*)\]/", $fr[ 'title' ], $cat );
									if ( isset( $cat[ 1 ][ 0 ] ) ) {
										$category = strtolower( $cat[ 1 ][ 0 ] );
									}
									foreach( $fr[ 'datas' ] as $frd ) {
										preg_match_all( "/\[([^\]]*)\]/", $frd[ 'answer' ], $acat );
										if ( isset( $acat[ 1 ][ 0 ] ) ) {
											$category = strtolower( $acat[ 1 ][ 0 ] );
										}
										if ( $frd[ 'selected' ] == "true" && ! empty( $category ) && $frd[ 'score' ] > 0 ) {
											if ( ! isset( $cats[ $category ] ) ) {
												$cats[ $category ] = 0;
											}
											$cats[ $category ] += $frd[ 'score' ];
										}
									}
								}
								if ( isset( $cats[ strtolower( $args[ 'qid' ] ) ] ) ) {
									$totalsumscore = $cats[ strtolower( $args[ 'qid' ] ) ];
								}
							}
							else {
								if ( $args[ 'alternativedatas' ] == "false" ) {
									return ( "" );
								}
							}
						}
						else {
							$sdatas[ 0 ] = array_values( $sdatas[ 0 ] );
							foreach( $sdatas[ 0 ] as $sdkey => $sd ) {
								if ( strtolower( $sd[ 'answer' ] ) == strtolower( $qid ) || ( ( $sdkey + 1 ) == $qid && is_numeric( $qid ) ) ) {
									$totalsumscore += $sd[ 'count' ];
								}
							}
							if ( $atts[ 'init' ] == "true" ) {
								$this->initialize_plugin();
							}
						}
						if ( ! empty( $args[ 'max' ] ) ) {
							if ( $args[ 'progress' ] == "true" ) {
								$additional_params = "";
								if ( $args[ 'bgcolor' ] ) {
									$bgcls = explode( ",", $args[ 'bgcolor' ] );
									if ( isset( $bgcls[ 0 ] ) ) {
										$additional_params .= ' data-foregroundColor="' . $bgcls[ 0 ] . '"';
									}
									if ( isset( $bgcls[ 1 ] ) ) {
										$additional_params .= ' data-backgroundColor="' . $bgcls[ 1 ] . '"';
									}
									if ( isset( $bgcls[ 2 ] ) ) {
										$additional_params .= ' data-targetColor="' . $bgcls[ 2 ] . '"';
									}
									if ( isset( $bgcls[ 3 ] ) ) {
										$additional_params .= ' data-fontColor="' . $bgcls[ 3 ] . '"';
									}
								}
								return ( '<div id="ms-progress-circle' . $args[ 'id' ] . '" class="modalsurvey-progress-circle" data-animation="1" ' . $additional_params . ' data-animationStep="5" data-percent="' . ( intval( ( $totalsumscore / $args[ 'max' ] ) * 100 ) ) . '"></div>' );
							}
							else {
								return ( intval( ( $totalsumscore / $args[ 'max' ] ) * 100 ) );
							}
						}
						return ( $totalsumscore );
					}
				}
			}
			if ( $args[ 'style' ] == 'progressbar' || $args[ 'style' ] == 'linebar' ) {
				$msplugininit_answer_array[ $args[ 'id' ] . '-' . $unique_key ] = array(
					"style" => array(
						"style" => $args[ 'style' ],
						"max" => $args[ 'max' ],
						"bgcolor" => $args[ 'bgcolor' ]
						)
					);
				$result = '<div id="survey-results-' . $args[ 'id' ] . '-' . $unique_key . '" class="survey-results">';
				$sql = "SELECT *,msq.id as question_id, msq.qoptions FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE mss.id='" . $args[ 'id' ] . "' ORDER BY msq.id ASC";
				$q_sql = $wpdb->get_results( $sql );		
				foreach( $q_sql as $key1 => $ars ) {
					$qoptions = unserialize( $ars->qoptions );
					if ( ( $args[ 'data' ] == 'full' || ( ( $key1 + 1 ) == $args[ 'qid' ] ) ) ) {
						preg_match( '/\[.*\]/', $ars->question, $ques );
						if ( ! empty( $ques ) ) {
							$ars->question = str_replace( $ques[ 0 ], "", $ars->question );
						}
					if ( $args[ 'hidequestion' ] == 'no' ) {
						$result .= $args[ 'title' ] . nl2br( $ars->question ) . $args[ 'title_c' ];
					}
					if ( $args[ 'data' ] == 'question' ) {
						$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $args[ 'qid' ] . "' ORDER BY " . $sort;
					}
					else {
						$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $ars->question_id . "' ORDER BY " . $sort;
					}
					$a_sql = $wpdb->get_results( $sql );
					//shortcode extension to get votes by post ID
					if ( $args[ 'postid' ] != '' ) {
						foreach($a_sql as $aaskey=>$bas){
							$a_sql[ $aaskey ]->count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( aid ) FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = %d AND qid = %d AND aid = %d AND postid = %d", $args[ 'id' ], $ars->question_id, $bas->autoid, $args[ 'postid' ] ) );
						}
					}
					//start - remove inactive answers
					foreach($a_sql as $aaskey=>$bas){
						$baoptions = unserialize( $bas->aoptions );								
						if ( isset( $baoptions[ 8 ] ) && $baoptions[ 8 ] == "1" ) {
							unset( $a_sql[ $aaskey ] );
						}
					}
					//end - remove inactive answers					
						if ( isset( $qoptions[ 3 ] ) && ( $qoptions[ 3 ] == 1 ) ) {
							$summary = 0; $allratings = 0;
							foreach( $a_sql as $key2=>$as ) {
								$summary += ( $key2 + 1 ) * $as->count;
								$allratings += $as->count;
									if ( preg_replace( '/\[.*\]/', '', $as->answer ) == "" ) {
										$tooltip[ $key2 + 1 ] = '';
									}
									else {
										$tooltip[ $key2 + 1 ] = ' data-tooltip="' . preg_replace('/\[.*\]/', '', $as->answer) . '"';
									}
							}
							if ( $allratings == 0 ) {
								$exactvalue =  0;
								$decvalue = 0;
								$intvalue = 0;
							}
							else {
								$exactvalue =  ( $summary / $allratings );
								$decvalue = ceil( ( $summary / $allratings ) * 2 ) / 2;
								$intvalue = ( int ) $decvalue;
							}
							$allans_count = count( $a_sql ) - $intvalue;
							$output = "<div class='question_rating_output'>";
							for ( $x = 1; $x <= $intvalue; $x++ ) {
								$output .= '<span ' . $tooltip[ $x ] . '><img class="rating_output" src="'.plugins_url( "/templates/assets/img/star-icon.png" , __FILE__ ).'"></span>'; 
							}
							if ( $decvalue > $intvalue ) {
								$output .= '<span ' . $tooltip[ $x  ] . '><img class="rating_output" src="'.plugins_url( "/templates/assets/img/star-icon-half.png" , __FILE__ ).'"></span>';
								$allans_count--;
								$x++;
							}
							for ( $y = 1; $y <= $allans_count; $y++ ) {
								$output .= '<span ' . $tooltip[ $y + $x - 1 ] . '><img class="rating_output" src="'.plugins_url( "/templates/assets/img/star-icon-empty.png" , __FILE__ ).'"></span>'; 
							}
							if ( $args[ 'hidecounter' ] == 'no' ) {
								$output .= "<span class='ms_ratingvalue'>" . number_format( $exactvalue, 2, '.', '' ) . " / " . $allratings . " " . __( 'votes', MODAL_SURVEY_TEXT_DOMAIN ) . "</span>";
							}
							$output .= "</div>";
							$result .= $output;
						}
						else {
							foreach( $a_sql as $key2 => $as ) {
								$aoptions = unserialize( $as->aoptions );
								if ( $aoptions[ 0 ] == "open" ) {
									$as->answer = __( 'Other', MODAL_SURVEY_TEXT_DOMAIN );
								}
								if ( ! empty( $aoptions[ 3 ] ) ) {
									$as->answer = '<img src="' . $aoptions[ 3 ] . '">' . $as->answer;
								}
								$allcount = 0;
								foreach( $a_sql as $aas ) {
									$allcount = $allcount + $aas->count;
								}
								if ( $allcount == 0 ) {
									$acr = '0';
								}
								else {
									$acr = round( ( $as->count / $allcount ) * 100, 2 );
								}
								if ( $args[ 'data' ] == 'full' || ( ( $key1 + 1 ) == $args[ 'qid' ] || $args[ 'data' ] == 'question' ) ) {
									if ( ( is_numeric( $args[ 'aid' ] ) && ( ( $key2 + 1 ) == $args[ 'aid' ] ) ) || ( ! is_numeric( $args[ 'aid' ] ) || $args[ 'aid' ] == '' ) ) {
										if ( $args[ 'hidecounter' ] == 'no' ) {
											$counter = '<span class="process_text"></span> <span class="badge badge-info right">' . $as->count . ' / ' . $allcount . '</span></p>';
										}
										else {
											$counter = '<span class="process_text"></span> <span class="badge badge-info right"></span></p>';
										}
										if ( $args[ 'bgcolor' ] == "random" ) {
											$bgcolor = $this->random_color();
										}
										else {
											$bgcolor = $args[ 'bgcolor' ];
										}
										if ( $args[ 'color' ] == "random" ) {
											$color = $this->random_color();
										}
										else {
											$color = $args[ 'color' ];
										}
										if ( $aoptions[ 10 ] != "" && $args[ 'tooltip' ] == "true" ) {
											$atooltip = 'data-tooltip="' . $aoptions[ 10 ] . '"';
										}
										else {
											$atooltip = "";
										}
										if ( $args[ 'style' ] == 'progressbar' ) {
											$result .= '<div class="process"><p><strong ' .$atooltip. '>' . preg_replace( '/\[.*\]/', '', $as->answer ) . '</strong> ' . $counter . ' <input type="hidden" class="hiddenperc" value="' . $acr . '" /><div class="progress progress-info progress-striped"><div class="bar survey_global_percent" style="width:0%;background-color:' . $bgcolor . ';color:' . $color . ';">' . $acr . '%</div>';
										}
										if ( $args[ 'style' ] == 'linebar' ) {
											$result .= '<div class="lineprocess"><p><strong ' .$atooltip. '>' . preg_replace( '/\[.*\]/', '', $as->answer ) . '</strong> ' . $counter . ' <input type="hidden" value="' . $acr . '" class="hiddenperc" /><div class="lineprogress progress-info progress-striped"><div class="bar survey_global_percent" style="width:0%;background-color:' . $bgcolor . ';color:' . $color . ';"></div><div class="perc" id="survey_perc">0%</div>';
										}
										$result .= '</div></div>';
									}
									if ( ( $key2 + 1 ) == $args[ 'aid' ] ) {
										return false; //replaces break; PHP7+
									}
								}
							}
							if ( ( $key1 + 1 ) == $args[ 'qid' ] && $args[ 'data' ] != 'full' ) {
								$msplugininit_answer_array[ $args[ 'id' ] . '-' . $unique_key ] = array( 
									"style" => array( 
										"style" => $args[ 'style' ],
										"max" => $args[ 'max' ],
										"bgcolor" => $args[ 'bgcolor' ],
										"cbgcolor" => $args[ 'cbgcolor' ],
										"legend" => $args[ 'legend' ]
										),
									"datas" => $sdatas
								);
								$result .= '</div>';
								if ( $atts[ 'init' ] == "true" ) {
									$this->initialize_plugin();
								}
								return( $result );
							}
						}
					}
				}
			$msplugininit_answer_array[ $args['id'] . '-' . $unique_key ] = array(
					"style" => array(
						"style" => $args[ 'style' ],
						"max" => $args[ 'max' ],
						"bgcolor" => $args[ 'bgcolor' ],
						"cbgcolor" => $args[ 'cbgcolor' ]
						),
					"datas" => $sdatas
				);
				$result .= '</div>';
				if ( isset( $_REQUEST[ 'sspcmd' ] ) && $_REQUEST[ 'sspcmd' ] == "displaychart" ) {
					$result .= "|endcontent-params|" . json_encode( $msplugininit_answer_array[ $args['id'] . '-' . $unique_key ] );
				}
				if ( $atts[ 'init' ] == "true" ) {
					$this->initialize_plugin();
				}
				return($result);
			}
			if ( $args[ 'style' ] == 'piechart' || $args[ 'style' ] == 'barchart' || $args[ 'style' ] == 'doughnutchart' || $args[ 'style' ] == 'linechart' || $args[ 'style' ] == 'polarchart' || $args[ 'style' ] == 'radarchart' ) {
				$result = '<div id="survey-results-' . $args[ 'id' ] . '-' . $unique_key . '" class="survey-results">';
				$sql = "SELECT *,msq.id as question_id, msq.qoptions FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE mss.id='" . $args[ 'id' ] . "' ORDER BY msq.id ASC";
				$q_sql = $wpdb->get_results( $sql );
				foreach( $q_sql as $key1 => $ars ) {
					if ( $args[ 'data' ] == 'full' || ( ( $key1 + 1 ) == $args[ 'qid' ] ) ) {
						$result .= '<div class="modal-survey-chart' . $key1 . ' ms-chart">';
						preg_match( '/\[.*\]/', $ars->question, $ques );
						if ( ! empty( $ques ) ) {
							$ars->question = str_replace( $ques[ 0 ], "", $ars->question );
						}
						if ( $args[ 'hidequestion' ] == 'no' ) {
							$result .= $args[ 'title' ] . nl2br( $ars->question ) . $args[ 'title_c' ];			
						}
						$result .= '<div class="legendDiv"></div><canvas style="width: 100%; height: 100%;"></canvas></div>';
						if ($args['data']=='question') {
							$ars->question_id = $args[ 'qid' ];
						}			
						$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $ars->question_id . "' ORDER BY " . $sort;								
						$a_sql = $wpdb->get_results( $sql );
						//shortcode extension to get votes by post ID
						if ( $args[ 'postid' ] != '' ) {
							foreach($a_sql as $aaskey=>$bas){
								$a_sql[ $aaskey ]->count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( aid ) FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = %d AND qid = %d AND aid = %d AND postid = %d", $args[ 'id' ], $ars->question_id, $bas->autoid, $args[ 'postid' ] ) );
							}
						}
						//start - remove inactive answers
						foreach($a_sql as $aaskey=>$bas){
							$baoptions = unserialize( $bas->aoptions );								
							if ( isset( $baoptions[ 8 ] ) && $baoptions[ 8 ] == "1" ) {
								unset( $a_sql[ $aaskey ] );
							}
						}
						//end - remove inactive answers					
						foreach( $a_sql as $key2=>$as ) {
							if ( isset( $as->aoptions ) ) {
								$aoptions = unserialize( $as->aoptions );								
								if ( is_numeric( $aoptions[ 4 ] ) ) {
									preg_match_all( "/\[([^\]]*)\]/", $as->answer, $acats );
									if ( isset( $acats[ 1 ][ 0 ] ) ) {
										$acats_list = explode( ",", $acats[ 1 ][ 0 ] );
										foreach ( $acats_list as $aca ) {
											if ( isset( $aca ) ) {
												if ( ! empty( $aca ) && ! is_numeric( $aca )  ) {
													if ( ! isset( $answercats[ $aca ] ) ) {
														$answercats[ $aca ] = 0;
													}
													if ( isset( $answercats[ $aca ] ) ) {
														$answercats[ $aca ] += $aoptions[ 4 ] * $as->count;
													}
												}
											}
										}
									}
								}
								if ( $aoptions[ 0 ] == "open" ) {
									$as->answer = __( 'Other', MODAL_SURVEY_TEXT_DOMAIN );
								}
							if ( ! empty( $aoptions[ 3 ] ) ) {
								//$as->answer = '<img src="' . $aoptions[ 3 ] . '">' . $as->answer;
								}
							}
							$sdatas[ $key1 ][ $key2 ]=array('answer' => preg_replace('/\[.*\]/', '', $as->answer), 'count'=>$as->count);
						}
						if ( ( $key1 + 1 ) == $args[ 'qid' ] && $args[ 'data' ] != 'full' && $args[ 'data' ] != 'question' ) {
							return false; //replaces break; PHP7+
						}
					}
					if ( $args[ 'data' ] == 'score' || $args[ 'data' ] == 'average-score' || $args[ 'data' ] == 'rating' ) {
						if ( $key1 == count( $ars ) - 1 ) {
							$result .= '<div class="modal-survey-chart' . $key1 . ' ms-chart">';
							$result .= '<div class="legendDiv"></div><canvas style="width: 100%; height: 100%;"></canvas></div>';
						}
						$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $args[ 'id' ] . "' AND question_id = '" . $ars->question_id . "' ORDER BY " . $sort;
						$a_sql = $wpdb->get_results( $sql );
						//shortcode extension to get votes by post ID
						if ( $args[ 'postid' ] != '' ) {
							foreach($a_sql as $aaskey=>$bas){
								$a_sql[ $aaskey ]->count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( aid ) FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE sid = %d AND qid = %d AND aid = %d AND postid = %d", $args[ 'id' ], $ars->question_id, $bas->autoid, $args[ 'postid' ] ) );
							}
						}
						//remove inactive answers start
						if ( $args[ 'showhidden' ] == "false" ) {
							foreach($a_sql as $aaskey=>$bas){
								$baoptions = unserialize( $bas->aoptions );								
								if ( isset( $baoptions[ 8 ] ) && $baoptions[ 8 ] == "1" ) {
									unset( $a_sql[ $aaskey ] );
								}
							}
						}
						//remove inactive answers end
						//keep the cumulative results to display multiple results on the same chart
						if ( $args[ 'compare' ] == "true" ) {
							$cum_a_sql = array();
							foreach ($a_sql as $k => $v) {
								$cum_a_sql[ $k ] = clone $v;
							}
						}
						else {
							$cum_a_sql[ 0 ] = ( object ) array( "count" => 0 );
						}
						//display individual records start
						if ( $args[ 'uid' ] != "false" ) {
							if ( $args[ 'uid' ] == "true" ) {
								if ( isset( $_COOKIE[ 'ms-uid' ] ) ) {
									$args[ 'uid' ] = $_COOKIE[ 'ms-uid' ];
								}
							}
							if ( is_numeric( $args[ 'uid' ] ) ) {
								$args[ 'uid' ] = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE autoid = %d ", $args[ 'uid' ] ) );
							}
							$sql_u = "SELECT mspd.qid, mspd.aid FROM " . $wpdb->base_prefix . "modal_survey_participants_details mspd LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants msp on mspd.uid = msp.autoid WHERE mspd.sid = '" . $args[ 'id' ] . "' AND mspd.qid = '" . $ars->question_id . "' AND msp.id = '" . $args[ 'uid' ] . "' " . $lastvotes . " ORDER BY autoid ASC";
							$a_sql_u = $wpdb->get_results( $sql_u );
							if ( ! empty( $a_sql_u ) ) {
								foreach( $a_sql_u as $key2u=>$asu ) {
									$uv_ans = explode( "|", $asu->aid );
									$user_votes[ $asu->qid ][] = $uv_ans[ 0 ];
								}
								foreach( $a_sql as $key2o=>$aso ) {
									if ( isset( $user_votes[ $aso->question_id ] ) && ( in_array( $aso->autoid, $user_votes[ $aso->question_id ] ) ) ) {
										$a_sql[ $key2o ]->count = 1;
									}
									else {
										$a_sql[ $key2o ]->count = 0;										
									}
								}
							}
							else {
								if ( $args[ 'alternativedatas' ] == "false" ) {
									return ( "" );
								}
							}
						}
						//remove inactive answers start
						if ( $args[ 'showhidden' ] == "false" ) {
							foreach($a_sql as $aaskey=>$bas){
								$baoptions = unserialize( $bas->aoptions );								
								if ( isset( $baoptions[ 8 ] ) && $baoptions[ 8 ] == "1" ) {
									unset( $a_sql[ $aaskey ] );
								}
							}
						}
						//remove inactive answers end
						//display individual records end
						$qscore = 0; $gqscore = 0;
						$summary = 0; $gsummary = 0;
						$allratings = 0;$gallratings = 0;
						foreach( $a_sql as $key2=>$as ) {
							if ( isset( $as->aoptions ) ) {
								$aoptions = unserialize( $as->aoptions );
								if ( is_numeric( $aoptions[ 4 ] ) ) {
									preg_match_all( "/\[([^\]]*)\]/", $as->answer, $acats );
									if ( isset( $acats[ 1 ][ 0 ] ) ) {
										$acats_list = explode( ",", $acats[ 1 ][ 0 ] );
										foreach ( $acats_list as $aca ) {
											if ( isset( $aca ) ) {
												if ( ! empty( $aca ) && ! is_numeric( $aca )  ) {
													if ( ! isset( $answercats[ $aca ] ) ) {
														$answercats[ $aca ] = 0;
													}
													if ( isset( $answercats[ $aca ] ) ) {
														$answercats[ $aca ] += $aoptions[ 4 ] * $as->count;
													}
												}
											}
										}
									}
								}
							}
							if ( ! isset( $cum_a_sql[ $key2 ] ) ) {
								$cum_a_sql[ $key2 ] = new stdClass();
								$cum_a_sql[ $key2 ]->count = 0;
							}
							if ( isset( $as->aoptions ) ) {
								$aoptions = unserialize( $as->aoptions );
								if ( $aoptions[ 0 ] == "open" ) {
									$as->answer = __( 'Other', MODAL_SURVEY_TEXT_DOMAIN );
								}
							if ( ! empty( $aoptions[ 3 ] ) ) {
								//$as->answer = '<img src="' . $aoptions[ 3 ] . '">' . $as->answer;
								}
							}
							else {
								$aoptions[ 4 ] = 0;
							}
							if ( isset( $args[ 'titles' ] ) && $args[ 'titles' ] != "" ) {
								$titles = explode( ",", $args[ 'titles' ] );
							}
							else {
								$titles = "";
							}
							if ( ! isset( $titles[ $key1 ] ) || empty( $titles[ $key1 ] ) || $titles[ $key1 ] == "" ) {
								if ( strpos( nl2br( $ars->question ), '[-]' ) === false ) {
									$titles[ $key1 ] = nl2br( $ars->question );
								}
							}
							if ( $args[ 'data' ] == 'score' || $args[ 'data' ] == 'average-score' ) {
								if ( isset( $aoptions[ 4 ] ) && is_numeric( $aoptions[ 4 ] ) ) {
									$qscore += $as->count * $aoptions[ 4 ];
									$gqscore += $cum_a_sql[ $key2 ]->count * $aoptions[ 4 ];
								}
								else {
									$qscore += 0;								
									$gqscore += 0;								
								}
							}
							if ( $args[ 'data' ] == 'rating' ) {
							$summary += ( $key2 + 1 ) * $as->count;
							$allratings += $as->count;
							$gsummary += ( $key2 + 1 ) * $cum_a_sql[ $key2 ]->count;
							$gallratings += $cum_a_sql[ $key2 ]->count;
							}
						}
						if ( $args[ 'data' ] == 'rating' ) {
							if ( $allratings == 0 ) {
								$exactvalue = 0;
								$decvalue = 0;
								$intvalue = 0;
							}
							else {
								$exactvalue =  ( $summary / $allratings );
								$decvalue = ceil( ( $summary / $allratings ) * 2 ) / 2;
								$intvalue = ( int ) $decvalue;
							}
							$allans_count = count( $a_sql ) - $intvalue;
							$qscore = number_format( $exactvalue, 2, '.', '' );
							if ( $gallratings == 0 ) {
								$gexactvalue = 0;
								$gdecvalue = 0;
								$gintvalue = 0;
							}
							else {
								$gexactvalue =  ( $gsummary / $gallratings );
								$gdecvalue = ceil( ( $gsummary / $gallratings ) * 2 ) / 2;
								$gintvalue = ( int ) $gdecvalue;
							}
							$gallans_count = count( $cum_a_sql ) - $gintvalue;
							$gqscore = number_format( $gexactvalue, 2, '.', '' );
						}
						if ( isset( $titles[ $key1 ] ) ) {
							preg_match_all( "/\[([^\]]*)\]/", $titles[ $key1 ], $ques );
						}
						if ( isset( $ques[ 1 ] ) ) {
							if ( ! empty( $ques[ 1 ] ) ) {
								foreach( $ques[ 1 ] as $perscat ) {
									$titles[ $key1 ] = str_replace( $perscat, "", $titles[ $key1 ] );
									if ( ! empty( $perscat ) ) {
										$titles[ $key1 ] = str_replace( array( "[", "]" ), "", trim( $perscat ) );
										if ( ! isset( $cat_count[ $titles[ $key1 ] ] ) ) {
											$cat_count[ $titles[ $key1 ] ] = 1;
										}
										else {
											$cat_count[ $titles[ $key1 ] ]++;
										}
									}
								}
							}
						}
						$valexist = 0;
						if ( ! empty( $sdatas[ 0 ] ) ) {
								foreach ( $sdatas[ 0 ] as $qstkey=>$qst ) {
									if ( isset( $titles[ $key1 ] ) ) {
										if ( $qst[ 'answer' ] == $titles[ $key1 ] ) {
											 if ( $args[ 'data' ] == 'average-score' ) {
												$allcount = 0;
												foreach($a_sql as $aas){
													$allcount = $allcount + $aas->count;
												}
												if ( $allcount > 0 ) {
													$qscore = number_format( $qscore / $allcount, 2, '.', '' );
												}
												else {
													$qscore = 0;
												}
											 }
											 if ( $args[ 'compare' ] == 'true' && $args[ 'data' ] == "score"  ) {
												$gallcount = 0;
												foreach($cum_a_sql as $caas){
													$gallcount = $gallcount + $caas->count;
												}
												if ( $gallcount > 0 ) {
													$gqscore = number_format( $gqscore / $gallcount, 2, '.', '' );
												}
												else {
													$gqscore = 0;
												}
											 }
											$sdatas[ 0 ][ $qstkey ][ 'count' ] = $sdatas[ 0 ][ $qstkey ][ 'count' ] + $qscore;
											if ( $args[ 'compare' ] == "true" ) {
												$sdatas[ 0 ][ $qstkey ][ 'gcount' ] = $sdatas[ 0 ][ $qstkey ][ 'gcount' ] + $gqscore;
											}
											$valexist = 1;
										}
									}
								}
						}
						if ( $valexist == 0 ) {
							if ( isset( $titles[ $key1 ] ) ) {
								if ( strlen( $titles[ $key1 ] ) > 50 ) {
									$titles[ $key1 ] = substr( $titles[ $key1 ], 0, 50 ) . "...";
								}
								if ( $titles[ $key1 ] != "-" ) {
									 if ( $args[ 'data' ] == 'average-score' ) {
										$allcount = 0;
										foreach($a_sql as $aas){
											$allcount = $allcount + $aas->count;
										}
										if ( $allcount > 0 ) {
											$qscore = number_format( $qscore / $allcount, 2, '.', '' );
										}
										else {
											$qscore = 0;
										}
									 }
									 if ( $args[ 'compare' ] == 'true' && $args[ 'data' ] == "score" ) {
										$gallcount = 0;
										foreach($cum_a_sql as $caas){
											$gallcount = $gallcount + $caas->count;
										}
										if ( $gallcount > 0 ) {
											$gqscore = number_format( $gqscore / $gallcount, 2, '.', '' );
										}
										else {
											$gqscore = 0;
										}
									 }
									 if ( ! empty( $key1 ) && ! empty( $qscore ) && ! empty( $titles[ $key1 ] ) ) {
										$sdatas[ 0 ][ $key1 ] = array( 'answer' => $titles[ $key1 ], 'count'=> $qscore );
									 }
									if ( $args[ 'compare' ] == "true" ) {
										$sdatas[ 0 ][ $key1 ][ 'gcount' ] = $gqscore;
									}
								}
							}
						}
					}
				}
			}
			if ( ! empty( $answercats ) && ( $args[ 'data' ] == 'score' || $args[ 'data' ] == 'rating' ) ) {
				foreach( $answercats as $ackey=>$ac ) {
					if ( ! empty( $ackey ) ) {
						$sdatas[ 0 ][] = array( 'answer' => $ackey, 'count'=> $ac );
					}
				}
			}
			if ( $args[ 'data' ] == "score" || $args[ 'data' ] == "average-score" ) {
				if ( $args[ 'sort' ] == "asc" ) {
					usort( $sdatas[ 0 ], function ( $item1, $item2 ) {
						if ( $item1[ 'count' ] == $item2[ 'count' ] ) return 0;
						return $item1[ 'count' ] < $item2[ 'count' ] ? -1 : 1;
					});
				}
				if ( $args[ 'sort' ] == "desc" ) {
					usort( $sdatas[ 0 ], function ( $item1, $item2 ) {
						if ( $item1[ 'count' ] == $item2[ 'count' ] ) return 0;
						return $item1[ 'count' ] < $item2[ 'count' ] ? -1 : 1;
					});
					$sdatas[ 0 ] = array_reverse( $sdatas[ 0 ] );
				}
			}

			// start - extension to display top results only
			if ( is_numeric( $args[ 'top' ] ) && ( $args[ 'data' ] == "score" || $args[ 'data' ] == "average-score" ) ) {
				usort( $sdatas[ 0 ], function ( $item1, $item2 ) {
					if ( $item1[ 'count' ] == $item2[ 'count' ] ) return 0;
					return $item1[ 'count' ] < $item2[ 'count' ] ? -1 : 1;
				});
				$sdatas[ 0 ] = array_slice( array_reverse( $sdatas[ 0 ] ), 0, $args[ 'top' ] );
			}
			// end - extension to display top results only
			// start - extension to display percentages instead of scores or votes
			if ( $args[ 'percentage' ] == "true" && $args[ 'data' ] == "score" ) {
				$tsumscore = 0;
				foreach( $sdatas[ 0 ] as $sd ) {
					$tsumscore += $sd[ 'count' ];
				}
				if ( strpos( $args[ 'catmax' ], ',' ) !== false) {
					$args[ 'catmax' ] = explode( ",", $args[ 'catmax' ] );
				}
				foreach( $sdatas[ 0 ] as $sdkey=>$sd ) {
					$thispercentage = 0;
					if ( $sd[ 'count' ] > 0 ) {
						if ( $args[ 'catmax' ] == 'false' ) {
							$thispercentage = round( ( ( $sd[ 'count' ] / $tsumscore ) * 100 ), 2 );
						}
						else {
							if ( is_numeric( $args[ 'catmax' ] ) ) {
								$thispercentage = round( ( ( $sd[ 'count' ] / $args[ 'catmax' ]  ) * 100 ), 2 );
							}
							if ( is_array( $args[ 'catmax' ] ) ) {
								if ( is_numeric( $args[ 'catmax' ][ $sdkey ] ) ) {
									$thispercentage = round( ( ( $sd[ 'count' ] / $args[ 'catmax' ][ $sdkey ] ) * 100 ), 2 );
								}
							}
						}
					}
					$sdatas[ 0 ][ $sdkey ][ 'count' ] = $thispercentage;
				}
			}
			if ( $args[ 'uid' ] != "false" && $args[ 'data' ] == "average-score" ) {
				foreach( $sdatas[ 0 ] as $sdkey=>$sd ) {
					if ( isset( $cat_count[ $sd[ 'answer' ] ] ) && $cat_count[ $sd[ 'answer' ] ] > 0 ) {
						$thisavg = round( ( $sd[ 'count' ] / $cat_count[ $sd[ 'answer' ] ] ), 2 );
						$sdatas[ 0 ][ $sdkey ][ 'count' ] = $thisavg;
					}
				}
			}
			if ( $atts[ 'pure' ] == "true" && $style != "plain" ) {
				return $sdatas;
			}
			// end - extension to display percentages instead of scores or votes
			$msplugininit_answer_array[ $args['id'] . '-' . $unique_key ] = array( "style" => array( "style" => $args[ 'style' ], "max" => $args[ 'max' ], "bgcolor" => $args[ 'bgcolor' ], "cbgcolor" => $args[ 'cbgcolor' ], "percentage" => $args[ 'percentage' ], "after" => $args[ 'after' ], "legend" => $args[ 'legend' ] ), "datas" => $sdatas );
			if ( $args[ 'compare' ] == "true" ) {
				$msplugininit_answer_array[ $args['id'] . '-' . $unique_key ][ "style" ][ "lng" ] = array( "label1" => __( 'Personal: ', MODAL_SURVEY_TEXT_DOMAIN ), "label2" => __( 'Average: ', MODAL_SURVEY_TEXT_DOMAIN ) );
			}
			else {
				$msplugininit_answer_array[ $args['id'] . '-' . $unique_key ][ "style" ][ "lng" ] = array( "label1" => "", "label2" => "" );
			}
			if ( $args[ 'data' ] != 'full-records' ) {
				$result .= '</div>';
			}
			if ( isset( $_REQUEST[ 'sspcmd' ] ) && $_REQUEST[ 'sspcmd' ] == "displaychart" ) {
				$result .= "|endcontent-params|" . json_encode( $msplugininit_answer_array[ $args['id'] . '-' . $unique_key ] );
			}
			if ( $atts[ 'init' ] == "true" ) {
				$this->initialize_plugin();
			}
			return($result);
		}
	
		function random_color() {
			return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
		}

		public function survey_shortcodes( $atts ) {
			global $wpdb, $wp, $script, $msplugininit_array, $postid, $current_user;
			$unique_key = mt_rand();
			$postid = get_the_id();
			extract( shortcode_atts( array(
					'id' => '-1',
					'style' => 'modal',
					'width' => '',
					'align' => 'left',
					'textalign' => 'center',
					'message' => 'You already filled out this survey!',
					'filtered' => 'false',
					'social' => '',
					'init' => '',
					'visible' => 'true',
					'sociallink' => '',
					'socialimage' => '',
					'socialtitle' => '',
					'socialdesc' => '',
					'socialstyle' => '',
					'socialpos' => '',
					'form' => '',
					'enddelay' => '',
					'display' => '',
					'unique' => 'false'
				), $atts, 'survey' ) );
			if ( ! isset( $atts[ 'style' ] ) ) {
				$atts[ 'style' ] = 'modal';
			}
			if ( ! isset( $atts[ 'align' ] ) ) {
				$atts[ 'align' ] = '';
			}
			if ( ! isset( $atts[ 'textalign' ] ) ) {
				$atts[ 'textalign' ] = '';
			}
			if ( ! isset( $atts[ 'width' ] ) ) {
				$atts[ 'width' ] = '100%';
			}
			if ( ! isset( $atts[ 'filtered' ] ) ) {
				$atts[ 'filtered' ] = 'false';
			}
			if ( ! isset( $atts[ 'init' ] ) ) {
				$atts[ 'init' ] = '';
			}
			if ( ! isset( $atts[ 'social' ] ) ) {
				$atts[ 'social' ] = '';
			}
			if ( ! isset( $atts[ 'visible' ] ) ) {
				$atts[ 'visible' ] = 'true';
			}
			if ( ! isset( $atts[ 'sociallink' ] ) ) {
				$atts[ 'sociallink' ] = '';
			}
			if ( ! isset( $atts[ 'socialimage' ] ) ) {
				$atts[ 'socialimage' ] = '';
			}
			if ( ! isset( $atts[ 'socialtitle' ] ) ) {
				$atts[ 'socialtitle' ] = '';
			}
			if ( ! isset( $atts[ 'socialdesc' ] ) ) {
				$atts[ 'socialdesc' ] = '';
			}
			if ( ! isset( $atts[ 'socialstyle' ] ) ) {
				$atts[ 'socialstyle' ] = '';
			}
			if ( ! isset( $atts[ 'fbappid' ] ) ) {
				$atts[ 'fbappid' ] = '';
			}
			if ( ! isset( $atts[ 'socialpos' ] ) ) {
				$atts[ 'socialpos' ] = '';
			}
			if ( ! isset( $atts[ 'form' ] ) ) {
				$atts[ 'form' ] = '';
			}
			if ( ! isset( $atts[ 'enddelay' ] ) ) {
				$atts[ 'enddelay' ] = '';
			}
			if ( ! isset( $atts[ 'display' ] ) ) {
				$atts[ 'display' ] = '';
			}
			if ( ! isset( $atts[ 'message' ] ) ) {
				$atts[ 'message' ] = '';
			}
			if ( ! isset( $atts[ 'customclass' ] ) ) {
				$atts[ 'customclass' ] = '';
			}
			if ( ! isset( $atts[ 'unique' ] ) ) {
				$atts[ 'unique' ] = 'false';
			}
			if ( ( ! isset( $atts[ 'message' ] ) || $atts[ 'message' ] == '' ) && ( $atts[ 'style' ] == 'click' || $atts[ 'style' ] == 'flat' ) ) {
				$atts[ 'message' ] = 'You already filled out this survey!';
			}
			$args = array(
				'id' => $atts[ 'id' ],
				'style' => $atts[ 'style' ],
				'init' => $atts[ 'init' ],
				'align' => $atts[ 'align' ],
				'textalign' => $atts[ 'textalign' ],
				'width' => $atts[ 'width' ],
				'filtered' => $atts[ 'filtered' ],
				'social' => $atts[ 'social' ],
				'visible' => $atts[ 'visible' ],
				'sociallink' => $atts[ 'sociallink' ],
				'socialimage' => $atts[ 'socialimage' ],
				'socialtitle' => strip_tags( $atts[ 'socialtitle' ] ),
				'socialdesc' => strip_tags( $atts[ 'socialdesc' ] ),
				'socialstyle' => $atts[ 'socialstyle' ],
				'fbappid' => $atts[ 'fbappid' ],
				'socialpos' => $atts[ 'socialpos' ],
				'form' => $atts[ 'form' ],
				'enddelay' => $atts[ 'enddelay' ],
				'display' => $atts[ 'display' ],
				'message' => $atts[ 'message' ],
				'customclass' => $atts[ 'customclass' ],
				'unique' => $atts[ 'unique' ]
			);
			if ( $atts[ 'filtered' ] == "true" ) {
				if ( ! is_single() && ! is_page() ) {
				//	return('');
				}
			}
			$survey_viewed = array();$sv_condition = '';
			if ( $atts[ 'style' ] = 'click' ) {
				if ( isset( $_COOKIE[ 'modal_survey' ] ) ) {
					if ( $_COOKIE[ 'modal_survey' ] != "undefined" ) {
						$survey_viewed = unserialize( stripslashes( $_COOKIE[ 'modal_survey' ] ) );
					}
				}
				$sql = "SELECT *,msq.id as question_id FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE (`expiry_time`>'" . current_time( 'mysql' ) . "' OR `expiry_time`='0000-00-00 00:00:00') AND (`start_time`<'" . current_time( 'mysql' ) . "' OR `start_time`='0000-00-00 00:00:00') AND mss.id='" . $args['id'] . "' ORDER BY msq.id ASC";
			}
			else {
				if ( isset( $_COOKIE[ 'modal_survey' ] ) ) {
					if ( $_COOKIE[ 'modal_survey' ] != "undefined" ) {
						$survey_viewed = unserialize( stripslashes( $_COOKIE[ 'modal_survey' ] ) );
					}
				}
				if ( ! empty( $survey_viewed ) ) {
					$sv_condition = "AND (mss.autoid NOT IN ( '" . implode( $survey_viewed, "', '" ) . "' ))";
				}
				$sql = "SELECT *,msq.id as question_id FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE (`expiry_time`>'" . current_time( 'mysql' ) . "' OR `expiry_time`='0000-00-00 00:00:00') AND (`start_time`<'" . current_time( 'mysql' ) . "' OR `start_time`='0000-00-00 00:00:00') AND mss.id='" . $args[ 'id' ] . "' '" . $sv_condition . "' ORDER BY msq.id ASC";
			}
			$questions_sql = $wpdb->get_results( $sql );
			if ( ! empty( $questions_sql ) ) {
			$survey = array();
			if ( $atts[ 'social' ] == "" ) {
				$social = get_option( 'setting_social' );
			}
			else {
				$social = $atts[ 'social' ];
			}
			if ( $atts[ 'socialstyle' ] == "" ) {
				$socialstyle = get_option( 'setting_social_style' );
			}
			else {
				$socialstyle = $atts[ 'socialstyle' ];
			}
			if ( $atts[ 'socialpos' ] == "" ) {
				$socialpos = get_option( 'setting_social_pos' );
			}
			else {
				$socialpos = $atts[ 'socialpos' ];
			}
			if ( $atts[ 'fbappid' ] == "" ) {
				$fbappid = get_option( 'setting_fbappid' );
			}
			else {
				$fbappid = $atts[ 'fbappid' ];
			}
			if ( $atts[ 'socialtitle' ] == "" ) {
				$soctit_temp = get_the_title();
			}
			else {
				$soctit_temp = $atts[ 'socialtitle' ];
			}
			if ( $atts[ 'socialdesc' ] == "" ) {
				$socdes_temp = $this->get_short_desc();
			}
			else {
				$socdes_temp = $atts[ 'socialdesc' ];
			}
			if ( $atts[ 'sociallink' ] == "" ) {
				$soclink_temp = home_url( add_query_arg( array(), $wp->request ) ) . '/';
			}
			else {
				$soclink_temp = $atts[ 'sociallink' ];
			}
			if ( $atts[ 'socialimage' ] == "" ) {
				$socimg_temp = $this->get_featured_image();
			}
			else {
				$socimg_temp = $atts[ 'socialimage' ];
			}
			if ( $fbappid != "" ) {
				$socfbappid_temp = $fbappid;
			}
			else {
				$socfbappid_temp = "";
			}
			$survey[ 'social' ] = array( $social, get_option( 'setting_social_sites' ), $socialstyle, $socialpos, $soclink_temp, $socimg_temp, strip_tags( $soctit_temp ) , strip_tags( $socdes_temp ), $socfbappid_temp );
			$survey[ 'visible' ] = $args[ 'visible' ];
			$survey[ 'form' ] = $args[ 'form' ];
			$survey[ 'display' ] = $args[ 'display' ];
			$survey[ 'postid' ] = $postid;
			foreach( $questions_sql as $key=>$qs ) {
				if ( $key == 0 ) {
					if ( $args[ 'unique' ] == "true" ) {
						$qs->autoid = $qs->autoid . $postid;
					}
					if ( ! empty( $survey_viewed ) ) {
						if ( in_array( $qs->autoid, $survey_viewed ) ) {
							$sv_condition = "expired";
						}
					}
					$survey[ 'options' ] = stripslashes( str_replace( '\\\'', '|', $qs->options ) );
					if ( $atts[ 'enddelay' ] != "" ) {
						$ssoa = json_decode( $survey[ 'options' ] );
						$ssoa[ 23 ] = $atts[ 'enddelay' ];
						$survey[ 'options' ] = json_encode( $ssoa );
					}
					$survey[ 'plugin_url' ] = plugins_url( '' , __FILE__ );
					$survey[ 'admin_url' ] = admin_url( 'admin-ajax.php' );
					$survey[ 'survey_id' ] = $qs->survey_id;
					$survey[ 'auto_id' ] = $qs->autoid;
					$survey[ 'align' ] = $args[ 'align' ];
					$survey[ 'textalign' ] = $args[ 'textalign' ];
					$survey[ 'width' ] = $args[ 'width' ];
					$survey[ 'style' ] = $args[ 'style' ];
					$survey[ 'grid_items' ] = GRID_ITEMS;
					if ( $survey[ 'grid_items' ] == "" && $survey[ 'options' ][ 142 ] != "" ) {
						$ssoa = json_decode( $survey[ 'options' ] );
						$survey[ 'grid_items' ] = $ssoa[ 142 ];
						$survey[ 'options' ] = json_encode( $ssoa );
					}
					if ( $sv_condition != "expired" ) {
						$survey[ 'expired' ] = 'false';
					}
					else {
						$survey[ 'expired' ] = 'true';
					}
					if ( $args[ 'style' ] == 'click' ) {
						$survey[ 'message' ] = $atts[ 'message' ];
					}
				}
				$survey[ 'questions' ][ $key ][] = nl2br( $qs->question );
							$sql = "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = '" . $qs->survey_id . "' AND question_id = '" . $qs->question_id . "' ORDER BY autoid ASC";
							$answers_sql = $wpdb->get_results( $sql );
							foreach( $answers_sql as $key2 => $as ) {
								$survey[ 'questions' ][ $key ][] = $as->answer;
								$survey[ 'ao' ][ ( $key + 1 ) . "_" . ( $key2 + 1 ) ] = unserialize( $as->aoptions );
							}
				$survey[ 'qo' ][ $key ] = unserialize( $qs->qoptions );
			}
			$soa = json_decode( $survey[ 'options' ] );
			if ( ! isset( $soa[ 18 ] ) ) {
				$soa[ 18 ] = "";
			}
			if ( $soa[18] == 1 ) {
				if ( ! is_user_logged_in() ) return;
				if ( get_option( 'setting_display_once_per_filled' ) == "on" ) {
					//$check_user = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants msp LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants_details mspd on msp.autoid = mspd.uid WHERE mspd.sid = %s AND msp.username = %s", $qs->survey_id, $current_user->user_login ) );
					$max_question = $wpdb->get_var( $wpdb->prepare( "SELECT question_id FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE survey_id = %s ORDER BY question_id DESC", $qs->survey_id ) );
					$user_max_question = $wpdb->get_var( $wpdb->prepare( "SELECT mspd.qid FROM " . $wpdb->base_prefix . "modal_survey_participants msp LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants_details mspd on msp.autoid = mspd.uid WHERE mspd.sid = %s AND msp.username = %s ORDER BY mspd.qid DESC", $qs->survey_id, $current_user->user_login ) );
					if ( $max_question == $user_max_question ) {
						return( htmlspecialchars_decode( $args[ 'message' ] ) );
					}
				}
			}
				if ( ( $args[ 'style' ] == 'flat' && in_array( $survey[ 'auto_id' ], $survey_viewed ) ) && ( $soa[ 18 ] != 1 ) ) {
						return( htmlspecialchars_decode( $args[ 'message' ] ) );
				}
				else
				{
					if (get_option('setting_display_once')=="on")
					{
						$survey_viewed = array();
						if ( isset( $_COOKIE[ 'modal_survey' ] ) ) {
							if ( $_COOKIE[ 'modal_survey' ] != "undefined" ) {
								$survey_viewed = unserialize( stripslashes( $_COOKIE[ 'modal_survey' ] ) );
							}
							if ( ! in_array( $survey[ 'auto_id' ], $survey_viewed ) ) {
								$survey_viewed[] = $survey[ 'auto_id' ];
								$survey[ 'display_once' ] = serialize( $survey_viewed );
							}
						}
						else
						{
							$survey_viewed[] = $survey[ 'auto_id' ];
							$survey[ 'display_once' ] = serialize( $survey_viewed );
						}
					}
					else $survey['display_once'] = '';
					$answers_text_sql = $wpdb->get_results( $wpdb->prepare( "SELECT msat.survey_id, msat.id, msat.answertext, msa.aoptions FROM ".$wpdb->base_prefix."modal_survey_answers_text msat INNER JOIN ".$wpdb->base_prefix."modal_survey_answers msa on msat.id = msa.uniqueid WHERE msat.survey_id = %d ORDER BY answertext ASC", $survey[ 'survey_id' ] ) );
					$datalist = array();
					$dlist = "";
					if ( ! empty( $answers_text_sql ) ) {
						foreach( $answers_text_sql as $atkey => $ats ) {
							if ( isset( $ats->aoptions ) ) {
								$aoptions = unserialize( $ats->aoptions );
								if ( isset( $aoptions[ 2 ] ) ) {
									if ( $aoptions[ 2 ] == "1" ) {
										$arraykey = $ats->survey_id . "_" . $ats->id;
										$datalist[ $arraykey ][] = $ats->answertext;
									}
								}
							}
						}
						foreach( $datalist as $dlkey => $dl ) {
							$dlist .= '<datalist id="ms_answers_' . $dlkey . '">';
							foreach( $dl as $answer ) {
								$dlist .= '<option value="' . $answer . '">';
							}
							$dlist .= '</datalist>';							
						}
					}
					$survey[ 'lastsessionqid' ] = $this->get_last_qid( $survey[ 'survey_id' ] );
					$msplugininit_array[ $survey[ 'survey_id' ] . '-' . $unique_key ] = array( "survey_id" => $survey[ 'survey_id' ], "unique_key" => $unique_key, "survey_options" => json_encode( $survey ) );
					if ( !empty( $args[ 'customclass' ] ) ) {
						$custom_class = ' ' . str_replace( ",", " ", $args[ 'customclass' ] );
					}
					else {
						$custom_class = '';
					}
					if ( $atts[ 'init' ] == "true" ) {
						$this->initialize_plugin();
					}
					return( $dlist . '<div id="survey-' . $survey[ 'survey_id' ] . '-' . $unique_key . '" class="modal-survey-container modal-survey-embed' . $custom_class . '" style="width:100%;"></div>' );
				}
			}
		}
		
		function extend_the_content( $content ) {
			if ( $this->auto_embed == 'false' && ! empty( $content ) ) {
				$this->auto_embed = 'true';
				if ( empty( $this->esurvey ) ) {
					return $content;
				}
				$thisoptions = json_decode( $this->esurvey[ 'options' ] );
				for ( $x = 1; $x <= 100; $x++ ) {
					if ( ! isset( $thisoptions[ $x ] ) ) {
						$thisoptions[ $x ] = '';
					}
				}
				if ( is_page() && ( $thisoptions[ 17 ] != "embed_start_pages" && $thisoptions[ 17 ] != "embed_end_pages" ) ) {
					return $content;
				}
				if ( is_single() && ! is_page() && ( $thisoptions[ 17 ] != "embed_start" && $thisoptions[ 17 ] != "embed_end" ) ) {
					return $content;
				}		
					if ( strpos( $thisoptions[ 17 ], "end"  ) !== false ) {
						$content .= modal_survey::survey_shortcodes( 
									array ( 'id' => $this->esurvey[ 'survey_id' ], 'style' => 'flat', 'customclass' => 'autoembed-msurvey', 'unique' => 'true' )
									);					
					}
					if ( strpos( $thisoptions[ 17 ], "start"  ) !== false ) {
						$content = modal_survey::survey_shortcodes( 
									array ( 'id' => $this->esurvey[ 'survey_id' ], 'style' => 'flat', 'customclass' => 'autoembed-msurvey', 'unique' => 'true' )
									) . $content;				
					}
			}
			return $content;
		}

		function enqueue_custom_scripts_and_styles() {
			global $wpdb, $wp, $script, $msplugininit_array, $postid, $current_user;
			$postid = url_to_postid( site_url( $_SERVER['REQUEST_URI'] ) );
			//retrieve last survey completions for the current user
			if ( ! empty( $current_user->user_login ) ) {
				$ms_reg_user_details = $wpdb->get_row( $wpdb->prepare( "SELECT mspd.samesession, msp.autoid FROM " . $wpdb->base_prefix . "modal_survey_participants_details mspd LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants msp on mspd.uid = msp.autoid WHERE msp.username = %s ORDER BY mspd.time DESC", $current_user->user_login ) );
				if ( ! empty( $ms_reg_user_details->samesession ) ) {
					setcookie( 'ms-session', $ms_reg_user_details->samesession, time() + 31536000, COOKIEPATH, COOKIE_DOMAIN, false);
				}
			}

			if ( get_option( 'setting_remember_users' ) != "off" ) {
				if ( ! isset( $_COOKIE[ 'ms-uid' ] ) ) {
					if( session_id() == '' ) {
						session_start();
					}
					$id = session_id();
					setcookie( 'ms-uid', session_id(), time() + 31536000, COOKIEPATH, COOKIE_DOMAIN, false);
				}
			}
			$unique_key = mt_rand();
			wp_enqueue_style( 'modal_survey_style', plugins_url( '/templates/assets/css/modal_survey.css', __FILE__ ), array(), MODAL_SURVEY_VERSION );
			wp_enqueue_style( 'circliful_style', plugins_url( '/templates/assets/css/jquery.circliful.css', __FILE__ ), array(), MODAL_SURVEY_VERSION );
			wp_enqueue_script( 'jquery' );
			if ( get_option( 'setting_social' ) == "on" ) {
				wp_enqueue_style('social_sharing_buttons_style', plugins_url( '/templates/assets/css/social-buttons.css', __FILE__ ), array(), MODAL_SURVEY_VERSION );
				wp_enqueue_script( 'social_sharing_buttons_script',plugins_url('/templates/assets/js/social-buttons.js', __FILE__ ), array( 'jquery' ), MODAL_SURVEY_VERSION );				
			}
			wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ) );
			wp_enqueue_script( 'jquery-effects-core', array( 'jquery' ) );
			wp_enqueue_script( 'jquery-effects-drop', array( 'jquery-effects-core' ) );
			wp_enqueue_script( 'jquery-effects-fade', array( 'jquery-effects-core' ) );
			wp_enqueue_script( 'jquery-effects-slide', array( 'jquery-effects-core' ) );
			wp_enqueue_script( 'jquery-visible', plugins_url( '/templates/assets/js/jquery.visible.min.js', __FILE__ ), array( 'jquery' ), '1.10.2' );
			wp_enqueue_script( 'jquery-chartjs', plugins_url( '/templates/assets/js/Chart.min.js', __FILE__ ), array( 'jquery' ), '1.10.2' );
			wp_enqueue_script('modal_survey_answer_script',plugins_url('/templates/assets/js/' . $this->answerscript, __FILE__ ), array( 'jquery', 'jquery-chartjs' ), MODAL_SURVEY_VERSION, true);
			wp_enqueue_script('modal_survey_script', plugins_url('/templates/assets/js/' . $this->mainscript , __FILE__ ), array('jquery'), MODAL_SURVEY_VERSION );
			wp_enqueue_script( 'jquery-circliful', plugins_url( '/templates/assets/js/jquery.circliful.min.js', __FILE__ ), array( 'jquery', 'modal_survey_answer_script' ), '1.0.2' );
				$survey_viewed = array();$sv_condition = '';
					if ( isset( $_COOKIE[ 'modal_survey' ] ) ) {
						if ( $_COOKIE[ 'modal_survey' ] != "undefined" ) {
							$survey_viewed = unserialize( stripslashes( $_COOKIE[ 'modal_survey' ] ) );
						}
					}
					if ( ! empty( $survey_viewed ) ) {
						$sv_condition = "AND (mss.autoid NOT IN ( '" . implode( $survey_viewed, "', '" ) . "' ))";
					}
			$sql = "SELECT *,msq.id as question_id FROM " . $wpdb->base_prefix . "modal_survey_surveys mss LEFT JOIN " . $wpdb->base_prefix . "modal_survey_questions msq on mss.id = msq.survey_id WHERE global = 1 AND (`expiry_time`>'".current_time( 'mysql' )."' OR `expiry_time`='0000-00-00 00:00:00') AND (`start_time`<'" . current_time( 'mysql' ) . "' OR `start_time`='0000-00-00 00:00:00') " . $sv_condition . " ORDER BY msq.id ASC";
			$questions_sql = $wpdb->get_results( $sql );
			if ( ! empty( $questions_sql ) ) {
			$survey = array();
			$survey[ 'social' ] = array( get_option( 'setting_social' ), get_option( 'setting_social_sites' ), get_option( 'setting_social_style' ), get_option( 'setting_social_pos' ), ( home_url(add_query_arg(array(),$wp->request ) ) . '/' ), $this->get_featured_image(), get_the_title(), $this->get_short_desc(), get_option( 'setting_fbappid' ), get_option( 'setting_fbappid' ) );
			foreach( $questions_sql as $key=>$qs ) {
				if ( $key == 0 ) {
					$survey['options'] = stripslashes( str_replace( '\\\'', '|', $qs->options ) );
					$survey['plugin_url'] = plugins_url( '' , __FILE__ );
					$survey['admin_url'] = admin_url( 'admin-ajax.php');
					$survey['survey_id'] = $qs->survey_id;
					$survey['auto_id'] = $qs->autoid;
					$survey['style'] = 'modal';
					$survey['expired'] = 'false';
					$survey['debug'] = 'true';
					$survey['form'] = '';
					$survey[ 'grid_items' ] = GRID_ITEMS;
					$survey[ 'lastsessionqid' ] = $this->get_last_qid( $survey[ 'survey_id' ] );
					if ( $survey[ 'grid_items' ] == "" && $survey[ 'options' ][ 142 ] != "" ) {
						$ssoa = json_decode( $survey[ 'options' ] );
						if ( isset( $ssoa[ 142 ] ) ) {
							$survey[ 'grid_items' ] = $ssoa[ 142 ];
						}
						$survey[ 'options' ] = json_encode( $ssoa );
					}
				}
				$survey[ 'questions' ][ $key ][] = nl2br( $qs->question );
							$sql = "SELECT * FROM ".$wpdb->base_prefix."modal_survey_answers WHERE survey_id = '" . $qs->survey_id . "' AND question_id = '" . $qs->question_id . "' ORDER BY autoid ASC";
							$answers_sql = $wpdb->get_results($sql);
							foreach( $answers_sql as $key2=>$as ) {
								$survey[ 'questions' ][ $key ][] = $as->answer;
								$survey[ 'ao' ][ ( $key + 1 ) . "_" . ( $key2 + 1 ) ] = unserialize( $as->aoptions );
							}
				$survey[ 'qo' ][ $key ] = unserialize( $qs->qoptions );
			}
			$soa = json_decode( $survey[ 'options' ] );
			for ( $x = 1; $x <= 100; $x++ ) {
				if ( ! isset( $soa[ $x ] ) ) {
					$soa[ $x ] = '';
				}
			}
			if ( $soa[ 18 ] == 1 ) {
				if ( ! is_user_logged_in() ) return;
				if ( get_option( 'setting_display_once_per_filled' ) == "on" ) {
					$check_user = $wpdb->get_var( $wpdb->prepare( "SELECT autoid FROM " . $wpdb->base_prefix . "modal_survey_participants msp LEFT JOIN " . $wpdb->base_prefix . "modal_survey_participants_details mspd on msp.autoid = mspd.uid WHERE mspd.sid = %s AND msp.username = %s", $qs->survey_id, $current_user->user_login ) );
					if ( ! empty( $check_user ) ) {
						return;
					}
				}
			}
			$survey[ 'display_once' ] = '';
			$survey[ 'postid' ] = $postid;
			if ( $soa[ 17 ] != "modal" ) {
				$survey[ 'style' ] = $soa[ 17 ];
				$this->esurvey = $survey;
			}
			else {
				$msplugininit_array[ $survey[ 'survey_id' ] . '-' . $unique_key ] = array( "survey_id" => $survey[ 'survey_id' ], "unique_key" => $unique_key, "survey_options" => json_encode( $survey ) );
			}
			if ( get_option( 'setting_display_once' ) == "on" ) {
				$survey_viewed = array();
				if ( isset( $_COOKIE[ 'modal_survey' ] ) ) {
					$survey_viewed = unserialize( stripslashes( $_COOKIE[ 'modal_survey' ] ) );
					if ( ! in_array( $survey[ 'auto_id' ], $survey_viewed ) ) {
						$survey_viewed[] = $survey[ 'auto_id' ];
						setcookie( "modal_survey", serialize( $survey_viewed ), time() + ( $soa[ 143 ] * 3600 ), COOKIEPATH, COOKIE_DOMAIN );
					}
				}
				else {
					$survey_viewed[] = $survey[ 'auto_id' ];
					setcookie( "modal_survey", serialize( $survey_viewed ), time() + ( $soa[ 143 ] * 3600 ), COOKIEPATH, COOKIE_DOMAIN );
				}
			}
				$answers_text_sql = $wpdb->get_results( $wpdb->prepare( "SELECT msat.survey_id, msat.id, msat.answertext, msa.aoptions FROM ".$wpdb->base_prefix."modal_survey_answers_text msat INNER JOIN ".$wpdb->base_prefix."modal_survey_answers msa on msat.id = msa.uniqueid WHERE msat.survey_id = %d ORDER BY answertext ASC", $survey[ 'survey_id' ] ) );
				$datalist = array();
				$dlist = "";
				if ( ! empty( $answers_text_sql ) ) {
					foreach( $answers_text_sql as $atkey => $ats ) {
						if ( isset( $ats->aoptions ) ) {
							$aoptions = unserialize( $ats->aoptions );
							if ( isset( $aoptions[ 2 ] ) ) {
								if ( $aoptions[ 2 ] == "1" ) {
									$arraykey = $ats->survey_id . "_" . $ats->id;
									$datalist[ $arraykey ][] = $ats->answertext;
								}
							}
						}
					}
					foreach( $datalist as $dlkey => $dl ) {
						$dlist .= '<datalist id="ms_answers_' . $dlkey . '">';
						foreach( $dl as $answer ) {
							$dlist .= '<option value="' . $answer . '">';
						}
						$dlist .= '</datalist>';							
					}
				}
				$script = $dlist;
			}
			$custom_css = get_option( 'setting_customcss' );
			if ( $custom_css != ""  ) {
				wp_enqueue_style( 'modal-survey-custom-style', plugins_url( '/templates/assets/css/custom_ms.css', __FILE__ ) );
				wp_add_inline_style( 'modal-survey-custom-style', $custom_css );
			}
		}
		/**
		* Add the settings link to the plugins page
		**/
		function plugin_settings_link($links) {
			$settings_link = '<a href="options-general.php?page=modal_survey">' . __( 'Settings', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>';
			array_unshift($links, $settings_link); 
			return $links; 
		}
		
		function add_localization( $ma ) {
		global $current_user;
			foreach( $ma as $key => $array ) {
			$so = json_decode( $array['survey_options'] );
				$so->languages = array(
					"pform_description" => __( 'Please enter your details below to continue.', MODAL_SURVEY_TEXT_DOMAIN ),
					"name_placeholder" => __( 'Enter your name', MODAL_SURVEY_TEXT_DOMAIN ),
					"email_placeholder" => __( 'Enter your email address', MODAL_SURVEY_TEXT_DOMAIN ),
					"send_button" => __( 'SEND', MODAL_SURVEY_TEXT_DOMAIN ),
					"next_button" => __( 'NEXT', MODAL_SURVEY_TEXT_DOMAIN ),
					"success" => __( 'SUCCESS', MODAL_SURVEY_TEXT_DOMAIN ),
					"shortname" => __( 'Name too short', MODAL_SURVEY_TEXT_DOMAIN ),
					"invalidemail" => __( 'Invalid Email Address', MODAL_SURVEY_TEXT_DOMAIN ),
					"alreadyfilled" => __( 'You already filled out this survey!', MODAL_SURVEY_TEXT_DOMAIN ),
					"campaignerror" => __( 'Connection Error', MODAL_SURVEY_TEXT_DOMAIN ),
					"timeisup" => __( 'Time is up!', MODAL_SURVEY_TEXT_DOMAIN ),
					"mailconfirmation" => __( 'Subscribe to our mailing list', MODAL_SURVEY_TEXT_DOMAIN ),
					"checkboxvalue" => __( 'Yes', MODAL_SURVEY_TEXT_DOMAIN ),
					"checkboxoffvalue" => __( 'No', MODAL_SURVEY_TEXT_DOMAIN )
					);
				$so->user = array( "email" => "", "name" => "" );
				if ( ! empty( $current_user->user_email ) ) {
					$so->user[ "email" ] = $current_user->user_email;
				}
				if ( ( ! empty( $current_user->user_firstname ) && ! empty( $current_user->user_lastname ) ) || ( ! empty( $current_user->display_name ) ) ) {
					if ( ( ! empty( $current_user->user_firstname ) ) && ! empty( $current_user->user_lastname ) ) {
						$so->user[ "name" ] = $current_user->user_firstname . ' ' . $current_user->user_lastname;
					}
					elseif ( ! empty( $current_user->display_name ) ) {
						$so->user[ "name" ] = $current_user->display_name;
					}
				}
				$ma[ $key ][ "survey_options" ] = json_encode( $so );
			}
			return $ma;
		}
		
		function get_last_qid( $sid ) {
		global $wpdb;
		$current_question = '-1';
			if ( isset( $_COOKIE[ 'ms-session' ] ) ) {
				$ms_session = $_COOKIE[ 'ms-session' ];
			}
			if ( ! empty( $ms_session ) ) {
				$last_vote = $wpdb->get_row( $wpdb->prepare( "SELECT `qid`,`aid` FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE `sid` = %d AND `samesession` = %d ORDER BY `time` DESC", $sid, $ms_session ) );
				if ( $last_vote ) {
					$last_aopts = $wpdb->get_var( $wpdb->prepare( "SELECT `aoptions` FROM " . $wpdb->base_prefix . "modal_survey_answers WHERE `survey_id` = %d AND `question_id` = %d AND `autoid` = %d", $sid, $last_vote->qid, $last_vote->aid ) );
					$laopts = unserialize( $last_aopts );
					if ( $laopts[ 11 ] > 0 ) {
						$current_question = $laopts[ 11 ] - 1;
					}
					else {
						$current_question = $last_vote->qid;
					}
				}
				return $current_question;
			}
		}		
		
		function initialize_plugin() {
		global $msplugininit_array, $msplugininit_answer_array, $script;
			if ( $this->mspreinit == "false" ) {
				$this->mspreinit = "true";
				if ( ! empty( $script ) ) {
					echo $script;
				}
				if ( ! empty( $msplugininit_array ) ) {
					$msplugininit_array = $this->add_localization( $msplugininit_array );
					wp_register_script( 'modal_survey_script_init', plugins_url( '/templates/assets/js/modal_survey_init.js', __FILE__ ), array( 'jquery', 'modal_survey_script' ), MODAL_SURVEY_VERSION, true );
					wp_localize_script( 'modal_survey_script_init', 'ms_init_params', $msplugininit_array );
					wp_enqueue_script( 'modal_survey_script_init' );
					do_action( 'modal_survey_action_init', $msplugininit_array );
				}
				if ( ! empty( $msplugininit_answer_array ) ) {
					wp_register_script( 'modal_survey_answer_script_init', plugins_url( '/templates/assets/js/modal_survey_answer_init.js', __FILE__ ), array( 'jquery' ), MODAL_SURVEY_VERSION, true );
					wp_localize_script( 'modal_survey_answer_script_init', 'ms_answer_init_params', $msplugininit_answer_array );
					wp_enqueue_script( 'modal_survey_answer_script_init' );			
					do_action( 'modal_survey_action_answer_init', $msplugininit_answer_array );
				}
			}
		}

		public static function update_modal_survey_db() {
			global $wpdb;
			$updated = false;
			try {
				$c_sql = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."modal_survey_questions LIMIT 1");
				if ( ! empty( $c_sql ) ) {
					if ( ! isset( $c_sql[ 0 ]->qoptions ) ) {
						$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_questions' . " ADD qoptions text" );
						$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_answers' . " ADD aoptions text" );
						$updated = true;
					}
				}
				$d_sql = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_surveys LIMIT 1" );
				if ( ! empty( $d_sql ) ) {
					if ( ! isset( $d_sql[ 0 ]->created ) ) {
						$wpdb->query("ALTER IGNORE TABLE ".$wpdb->base_prefix.'modal_survey_surveys'." ADD created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL");
						$wpdb->query("ALTER IGNORE TABLE ".$wpdb->base_prefix.'modal_survey_surveys'." ADD updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL");
						$wpdb->query("ALTER IGNORE TABLE ".$wpdb->base_prefix.'modal_survey_surveys'." ADD owner bigint NOT NULL");
						$wpdb->update( $wpdb->base_prefix."modal_survey_surveys", array( "owner" => get_current_user_id() ),array('owner' => "NULL"));
						$wpdb->update( $wpdb->base_prefix."modal_survey_surveys", array( "created" => date("Y-m-d H:i:s") ),array('created' => "0000-00-00 00:00:00"));
						$updated = true;
					}
				}
				$e_sql = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_answers LIMIT 1" );
				if ( ! empty( $e_sql ) ) {
					if ( ! isset( $e_sql[ 0 ]->uniqueid ) ) {
						$wpdb->query("ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_answers'." ADD uniqueid varchar(255) NOT NULL");
						$updated = true;
					}
				}
				$f_sql = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_participants_details LIMIT 1" );
				if ( ! empty( $f_sql ) ) {
					if ( ! isset( $f_sql[ 0 ]->postid ) ) {
						$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_participants_details' . " ADD postid bigint NOT NULL" );
						$updated = true;
					}
				}
				$g_sql = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "modal_survey_participants_details LIMIT 1");
				if ( ! empty( $g_sql ) ) {
					if ( ! isset( $g_sql[ 0 ]->samesession ) ) {
						$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_participants_details'." ADD samesession varchar(255) NOT NULL" );
						$updated = true;
					}
				}

				/** 1.9.5 START updating participants_details table if necessary **/
				$wpdb->insert( $wpdb->base_prefix . "modal_survey_participants_details", array('uid' => '1', 'sid' => '1', 'qid' => '1', 'aid' => '1', 'time' => date( "Y-m-d H:i:s" ), 'ip' => 'x.x.x.x', 'postid' => '1'), array( '%s', '%s', '%d', '%s', '%s', '%s', '%d' )  );
				$g_sql = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "modal_survey_participants_details LIMIT 1");
				if ( ! empty( $g_sql ) ) {
					if ( ! isset( $g_sql[ 0 ]->samesession ) ) {
						$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_participants_details'." ADD samesession varchar(255) NOT NULL" );
						$updated = true;
					}
					if ( ! isset( $g_sql[ 0 ]->timer ) ) {
						$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_participants_details'." ADD timer int NULL" );
						$updated = true;
					}
				}
				
				/** 1.9.7.2 START updating participants table if necessary **/
				$wpdb->insert( $wpdb->base_prefix . "modal_survey_participants", array('id' => '1', 'username' => 'ms-test', 'email' => 'ms-test@test.com', 'name' => 'ms-test'), array( '%s', '%s', '%s', '%s' )  );
				$h_sql = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "modal_survey_participants LIMIT 1" );
				if ( ! isset( $h_sql[ 0 ]->custom ) ) {
					$wpdb->query( "ALTER IGNORE TABLE " . $wpdb->base_prefix . 'modal_survey_participants' . " ADD custom TEXT NOT NULL" );
					$updated = true;
				}
			  /** END updating participants_details table if necessary **/
				
				/** START CHECKING CHANGES **/
				$ms_db_version = get_option( 'setting_db_modal_survey' );
				$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->base_prefix . "modal_survey_participants_details WHERE `ip` = %s AND `uid` = %s AND `sid` = %s", 'x.x.x.x', '1', '1' ) );
				$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->base_prefix . "modal_survey_participants WHERE `username` = %s AND `email` = %s AND `name` = %s", 'ms-test', 'ms-test@test.com', 'ms-test' ) );
					update_option( 'setting_db_modal_survey', MODAL_SURVEY_VERSION );				
					return true;
				/** END CHECKING CHANGES **/
			}
			catch ( Exception $e ) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
	}
}
if ( class_exists( 'modal_survey' ) ) {
	// call the main class
	$modal_survey = modal_survey::getInstance();
}
?>