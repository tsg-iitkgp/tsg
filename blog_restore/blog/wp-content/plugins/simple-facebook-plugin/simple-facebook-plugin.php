<?php
/*
Plugin Name: Simple Facebook Plugin
Plugin URI: http://plugins.topdevs.net/simple-facebook-plugin
Description: Allows you to integrate Facebook "Page Plugin" into your WordPress Site.
Version: 1.5
Author: topdevs.net
Author URI: http://codecanyon.net/user/topdevs/portfolio?ref=topdevs
License: GPLv2 or later
*/

define( "SFP_VERSION", '1.5' );

/**
* Main SF Plugin Class
*
* Contains the main functions for SF and stores variables
*
* @since SF Plugin 1.2
* @author Ilya K.
*/

// Check if class already exist
if ( !class_exists( 'SFPlugin' ) ) {

	// Main plugin class
	class SFPlugin {
		
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		public $optionName;

		public $facebookLocalesUrl = "http://www.facebook.com/translations/FacebookLocales.xml";
		public $locales;

		/**
		* SF Plugin Constructor
		*
		* Gets things started
		*/
		public function __construct( ) {
			$this->pluginPath 	= plugin_dir_path(__FILE__);
			$this->pluginUrl 	= plugin_dir_url(__FILE__);

			$this->optionName	= "simple_facebook_plugin_options";
			
			$this->locales 		= $this->parseLocales($this->facebookLocalesUrl);

			$this->loadFiles();
			$this->addActions();
			$this->addShortcodes();
		}
		
		/**
		 * Load all the required files.
		 */
		protected function loadFiles() {
			// Include social plugins files
			require_once( $this->pluginPath . 'lib/sfp-page-plugin.php' );

			// Allow addons load files
			do_action('sfp_load_files');
		}
		
		/**
		* Add all the required actions.
		*/
		protected function addActions() {
			
			//add_action( 'admin_menu', 	array( $this, 'pluginMenu') );
			add_action( 'admin_notices',    array( $this, 'adminNotice') );
			add_action( 'widgets_init', 	array( $this, 'addWidgets') );
			//add_action( 'wp_footer',		array( $this, 'addJavaScriptSDK') );
			add_action( 'admin_init', 		array( $this, 'saveOptions' ) );
			add_action( 'admin_init', 		array( $this, 'ignoreNotices' ) );
			add_action( 'admin_enqueue_scripts',	array( $this, 'enqueueScriptsAdmin') );

			// Add settings link on Plugins page
			$plugin = "simple-facebook-plugin/simple-facebook-plugin.php";
			//add_filter( "plugin_action_links_$plugin", array( $this, 'pluginSettingsLink') );

			// Allow addons add actions
			do_action( 'sfp_add_actions', $this );
		}
		
		/**
		* Register all widgets
		*/
		public function addWidgets() {
			
			register_widget('SFPPagePluginWidget');
		
			// Allow addons add widgets
			do_action('sfp_add_widgets');
		}
		
		/**
		* Register all shortcodes
		*/
		public function addShortcodes() {
		
			add_shortcode('sfp-page-plugin', 'sfp_page_plugin_shortcode');
			
			// Allow addons add shortcodes
			do_action('sfp_add_shortcodes');
		}

		/**
		 * Get remote XML file by URL
		 * 
		 * @param  string $url 
		 * @return array
		 * @since 1.3
		 */
		public function parseLocales ( $url = "" ) {

			if ( file_exists( $url ) && function_exists( "simplexml_load_file" ) ) {
				
				$locales 	= array();
				$xml 		= simplexml_load_file( $url );
				
				foreach ( $xml as $key => $locale ) {
					
					$name = (array) $locale->englishName;
					$name = $name[0];

					$code = (array) $locale->codes->code->standard->representation;
					$code = $code[0];

					$locales[$code] = $name; 
				};
			}
			else 
				$locales = array( 
					"af_ZA"=>   "Afrikaans",
					"ar_AR"=>   "Arabic",
					"az_AZ"=>	"Azerbaijani",
					"be_BY"=>   "Belarusian",
					"bg_BG"=>   "Bulgarian",
					"bn_IN"=>   "Bengali",
					"bs_BA"=>   "Bosnian",
					"ca_ES"=>   "Catalan",
					"cs_CZ"=>   "Czech",
					"cy_GB"=>   "Welsh",
					"da_DK"=>   "Danish",
					"de_DE"=>   "German",
					"el_GR"=>   "Greek",
					"en_GB"=>   "English (UK)",
					"en_PI"=>   "English (Pirate)",
					"en_UD"=>   "English (Upside Down)",
					"en_US"=>   "English (US)",
					"eo_EO"=>   "Esperanto",
					"es_ES"=>   "Spanish (Spain)",
					"es_LA"=>   "Spanish",
					"et_EE"=>   "Estonian",
					"eu_ES"=>   "Basque",
					"fa_IR"=>   "Persian",
					"fb_LT"=>   "Leet Speak",
					"fi_FI"=>   "Finnish",
					"fo_FO"=>   "Faroese",
					"fr_CA"=>   "French (Canada)",
					"fr_FR"=>   "French (France)",
					"fy_NL"=>   "Frisian",
					"ga_IE"=>   "Irish",
					"gl_ES"=>   "Galician",
					"he_IL"=>   "Hebrew",
					"hi_IN"=>   "Hindi",
					"hr_HR"=>   "Croatian",
					"hu_HU"=>   "Hungarian",
					"hy_AM"=>   "Armenian",
					"id_ID"=>   "Indonesian",
					"is_IS"=>   "Icelandic",
					"it_IT"=>   "Italian",
					"ja_JP"=>   "Japanese",
					"ka_GE"=>   "Georgian",
					"km_KH"=>   "Khmer",
					"ko_KR"=>   "Korean",
					"ku_TR"=>   "Kurdish",
					"la_VA"=>   "Latin",
					"lt_LT"=>   "Lithuanian",
					"lv_LV"=>   "Latvian",
					"mk_MK"=>   "Macedonian",
					"ml_IN"=>   "Malayalam",
					"ms_MY"=>   "Malay",
					"nb_NO"=>   "Norwegian (bokmal)",
					"ne_NP"=>   "Nepali",
					"nl_NL"=>   "Dutch",
					"nn_NO"=>   "Norwegian (nynorsk)",
					"pa_IN"=>   "Punjabi",
					"pl_PL"=>   "Polish",
					"ps_AF"=>   "Pashto",
					"pt_BR"=>   "Portuguese (Brazil)",
					"pt_PT"=>   "Portuguese (Portugal)",
					"ro_RO"=>   "Romanian",
					"ru_RU"=>   "Russian",
					"sk_SK"=>   "Slovak",
					"sl_SI"=>   "Slovenian",
					"sq_AL"=>   "Albanian",
					"sr_RS"=>   "Serbian",
					"sv_SE"=>   "Swedish",
					"sw_KE"=>   "Swahili",
					"ta_IN"=>   "Tamil",
					"te_IN"=>   "Telugu",
					"th_TH"=>   "Thai",
					"tl_PH"=>   "Filipino",
					"tr_TR"=>   "Turkish",
					"uk_UA"=>   "Ukrainian",
					"vi_VN"=>   "Vietnamese",
					"zh_CN"=>   "Simplified Chinese (China)",
					"zh_HK"=>   "Traditional Chinese (Hong Kong)",
					"zh_TW"=>   "Traditional Chinese (Taiwan)" 
				);

			return $locales;
		}

		/**
		 * Load styles for dashboard
		 *
		 * @since 1.3.1
		 */

		static function enqueueScriptsAdmin() {
			
			// add custom css
			wp_register_style( 'sfp-admin-style', plugin_dir_url(__FILE__) . '/lib/css/sfp-admin-style.css' );
			wp_enqueue_style( 'sfp-admin-style' );
		}

		/**
		 * Load Facebook JavaScript SDK
		 *
		 * @since 1.3
		 */
		
		public function addJavaScriptSDK() { 

			$options 	= $this->getPluginOptions();
			$locale 	= $options['locale'];

			?>

		<div id="fb-root"></div>
		<script>
			(function(d){
				var js, id = 'facebook-jssdk';
				if (d.getElementById(id)) {return;}
				js = d.createElement('script');
				js.id = id;
				js.async = true;
				js.src = "//connect.facebook.net/<?php echo $locale; ?>/all.js#xfbml=1";
				d.getElementsByTagName('head')[0].appendChild(js);
			}(document));
		</script>

		<?php }

		/**
		 * Add Dashboard > Plugins Menu Page
		 *
		 * @since 1.3
		 */

		public function pluginMenu() {
			
			add_plugins_page('Simple Facebook Plugin Menu', 'Simple Facebook', 'read', 'simple_facebook_plugin', array( $this, "pluginMenuView" ) );
		}

		/**
		 * Show Menu Page View
		 *
		 * @since 1.3
		 */

		public function pluginMenuView() {

			$options = $this->getPluginOptions();
			
			// include Like Box view
			include( $sfplugin->pluginPath . 'views/view-menu.php' );

		}

		/**
		* Show admin notice
		* 
		* @since 1.3
		*/

		public function adminNotice() {

			global $current_user;
			$user_id = $current_user->ID;

			/* Check that the user hasn't already clicked to ignore the message */
			if ( ! get_user_meta( $user_id, 'sfp_ignore_notice_2') ) {

				echo '<div class="updated"><p>';

				printf( __('Thanks for using our <strong>Simple Facebook Plugin</strong>! We have some other great WordPress plugins <a href="http://codecanyon.net/user/topdevs/portfolio?ref=topdevs">View Portfolio</a> | <a href="%1$s">Hide this</a>'), '?sfp_ignore_2=0');

				echo "</p></div>";

			}
		}

		public function ignoreNotices() {
			
			global $current_user;
			$user_id = $current_user->ID;
			
			/* If user clicks to ignore the notice, add that to their user meta */
			if ( isset( $_GET['sfp_ignore_2'] ) && '0' == $_GET['sfp_ignore_2'] ) {
				add_user_meta( $user_id, 'sfp_ignore_notice_2', 'true', true);
			}
		}

		/**
		* Add status link on plugins page
		*
		* @since 1.3
		*/

		public function pluginSettingsLink ( $links ) {

			$settings_link = '<a href="' . menu_page_url( "simple_facebook_plugin", false ) . '">Settings</a>'; 

			array_unshift( $links, $settings_link );

			return $links; 
		}

		/**
		* Get plugin options
		* 
		* @since 1.3
		*/

		public function getPluginOptions() {

			$defaults = array( 
				'locale' => "en_US" );

			$defaults = apply_filters( "sfp_default_options", $defaults );

			$options = get_option( $this->optionName, $defaults );

			return $options;
		}

		/**
		* Save plugin options
		* 
		* @since 1.3
		*/

		public function savePluginOptions( $options = array() ) {

			update_option( $this->optionName, $options );
		}

		/**
		* Trigger when settings page form submitted
		*
		* @since 1.3
		*/

		public function saveOptions() {

			//delete_option( $this->optionName );

			// If submit button pressed
			if ( isset( $_POST['sfp_options_saved'] ) ) {

				$options = $this->getPluginOptions();

				if ( isset( $_POST['locale'] ) && !empty( $_POST['locale'] ) ) {

					$options['locale'] = $_POST['locale'];
				}

				$this->savePluginOptions( $options );
			}
		}
		
	} // end SFPlugin class

} // end if !class_exists

// Create new SFPlugin instance
$GLOBALS["sfplugin"] = new SFPlugin();

// testing updates, may be removed later
?>