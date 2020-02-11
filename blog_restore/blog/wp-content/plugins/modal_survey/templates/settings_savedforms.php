<div id="screen_preloader" style="position: absolute;width: 100%;height: 1000px;z-index: 9999;text-align: center;background: #fff;padding-top: 200px;"><h3>Modal Survey for WordPress</h3><img src="<?php print( plugins_url( '/assets/img/screen_preloader.gif', __FILE__ ) );?>"><h5><?php _e( 'LOADING', MODAL_SURVEY_TEXT_DOMAIN );?><br><br><?php _e( 'Please wait...', MODAL_SURVEY_TEXT_DOMAIN );?></h5></div>
<div class="wrap pantherius-jquery-ui wrap-padding" style="visibility:hidden">
<br />
<div class="title-border">
	<?php
	if ( isset( $_REQUEST[ 'modal_survey_id' ] ) ) {
		global $wpdb;
		$surveys = $this->wpdb->get_results($wpdb->prepare( "SELECT * FROM " . $this->wpdb->base_prefix . "modal_survey_surveys mss WHERE mss.id = %d ORDER BY autoid DESC", $_REQUEST[ 'modal_survey_id' ] ) );
		print( "<h3>" . $surveys[ 0 ]->name . "</h3>" );	
	}
	else {
	?>
		<h3><?php _e( 'Saved Surveys', MODAL_SURVEY_TEXT_DOMAIN );?></h3>
	<?php } ?>
	<div class="help_link">
	<?php 
	if ( isset( $_REQUEST['modal_survey_id'] ) ) {
		print('<div class="ms-wizard heartbeat" data-step="1" data-tutorial="survey-form">' . __( 'Tutorial', MODAL_SURVEY_TEXT_DOMAIN ) . '</div>');
	}
	?>
	<a target="_blank" href="http://modalsurvey.pantherius.com/documentation/#line2"><?php _e( 'Documentation', MODAL_SURVEY_TEXT_DOMAIN );?></a>
	</div>
</div>
<?php
	if ( isset ( $_REQUEST[ 'result' ] ) ) {
		$result = $_REQUEST[ 'result' ];
		$reason = "";
		if ( isset ( $_REQUEST[ 'reason' ] ) ) {
			if ( $_REQUEST[ 'reason' ] == "exists" ) {
				$reason = __( 'Survey name already exists', MODAL_SURVEY_TEXT_DOMAIN );
			}
		}
		if ( $result == "deleted" ) echo '<div class="updated"><p>'.__( 'Survey successfully deleted!', MODAL_SURVEY_TEXT_DOMAIN ).'</p></div>'; 
		if ( $result == "duplicated" ) echo '<div class="updated"><p>'.__( 'Survey successfully duplicated!', MODAL_SURVEY_TEXT_DOMAIN ).'</p></div>';  
		if ( $result == "reset" ) echo '<div class="updated"><p>'.__( 'Survey successfully reseted!', MODAL_SURVEY_TEXT_DOMAIN ).'</p></div>'; 
		if ( $result == "fail" ) echo '<div class="updated"><p>'.__( 'Action failed due to error! '.$reason, MODAL_SURVEY_TEXT_DOMAIN ).'</p></div>'; 
	}
if ( isset( $_REQUEST['modal_survey_id'] ) ) {
	$modal_survey_id = $_REQUEST['modal_survey_id'];
}
else {
	$modal_survey_id = "";
}
if ( ! empty( $modal_survey_id ) ) {
?>
	<div id="gradX" ></div>
	<div id="modal_survey_settings">
<div id="modal_survey_accordion">
				<?php
				foreach( $surveys as $sv ) {
					$options = json_decode( stripslashes( $sv->options ) );
					/*print('<pre>');
					print_r($options);
					print('</pre>');*/
					for ($y = 0; $y <= 1000; $y++) {
						if ( ! isset( $options[ $y ] ) ) {
							$options[ $y ] = "";
						}
					}
					if ( empty( $options[ 16 ] ) ) $options[ 16 ] = 'center';
					if ( empty( $options[ 17 ] ) ) $options[ 17 ] = "modal";
					if ( empty( $options[ 18 ] ) ) $options[ 18 ] = 0;
					if ( empty( $options[ 19 ] ) ) $options[ 19 ] = "";
					if ( empty( $options[ 20 ] ) ) $options[ 20 ] = "0";
					if ( empty( $options[ 22 ] ) ) $options[ 22 ] = "0";
					if ( $options[ 128 ] == "" ) $options[ 128 ] = "0";
					if ( $options[ 129 ] == "" ) $options[ 129 ] = "1";
					if ( $options[ 130 ] == "" ) $options[ 130 ] = "1";
					if ( $options[ 131 ] == "" ) $options[ 131 ] = "0";
					if ( ! isset( $options[ 143 ] ) || $options[ 143 ] == "" || empty( $options[ 143 ] ) ) {
						$options[ 143 ] = "9999";
					}
					if ( empty( $options[ 132 ] ) ) $options[ 132 ] = "#ececec";
					if ( $sv->global == 1 ) {
						$global_opt = "checked";
					}
					else {
						$global_opt = "";
					}
					if ( $options[ 13 ] == 1 ) {
						$opt_13 = "checked";
					}
					else {
						$opt_13 = "";
					}
					if ( $options[ 14 ] == 1 ) {
						$opt_14 = "checked";
					}
					else {
						$opt_14 = "";
					}
					if ( $options[ 15 ] == 1 ) {
						$opt_15 = "checked";
					}
					else {
						$opt_15 = "";
					}
					if ( $options[ 18 ] == 1 ) {
						$opt_18 = "checked";
					}
					else {
						$opt_18 = "";
					}
					if ( $options[ 20 ] == 1 ) {
						$opt_20 = "checked";
					}
					else {
						$opt_20 = "";
					}
					if ( $options[ 22 ] == 1 ) {
						$opt_22 = "checked";
					}
					else {
						$opt_22 = "";
					}
					if ( $options[ 152 ] == 1 ) {
						$opt_152 = "checked";
					}
					else {
						$opt_152 = "";
					}
					if ( $options[ 154 ] == 1 ) {
						$opt_154 = "checked";
					}
					else {
						$opt_154 = "";
					}
					if ( $options[ 155 ] == 1 ) {
						$opt_155 = "checked";
					}
					else {
						$opt_155 = "";
					}
					if ( $options[ 157 ] == 1 ) {
						$opt_157 = "checked";
					}
					else {
						$opt_157 = "";
					}
					if ( $options[ 136 ] == 1 ) {
						$endchart_status = "checked";
						$endchart_status_value = "1";
					}
					else {
						$endchart_status = "";
						$endchart_status_value = "0";
					}
					if ( ! isset( $options[23] ) ) {
						$options[23] = 5000;
					}			
					if ( $options[ 24 ] == '1' ) {
						$activecampaign = "checked";
						$activecampaign_value = '1';
					}
					else {
						$activecampaign = "";
						$activecampaign_value = '0';
					}
					if ( $options[ 28 ] == '1' ) {
						$aweber = "checked";
						$aweber_value = '1';
					}
					else {
						$aweber = "";
						$aweber_value = '0';
					}
					if ( $options[ 35 ] == '1' ) {
						$benchmark = "checked";
						$benchmark_value = '1';
					}
					else {
						$benchmark = "";
						$benchmark_value = '0';
					}
					if ( $options[ 36 ] == '1' ) {
						$benchmark_doubleoptin = "checked";
						$benchmark_doubleoptin_value = '1';
					}
					else {
						$benchmark_doubleoptin = "";
						$benchmark_doubleoptin_value = '0';
					}
					if ( $options[ 39 ] == '1' ) {
						$campaignmonitor = "checked";
						$campaignmonitor_value = '1';
					}
					else {
						$campaignmonitor = "";
						$campaignmonitor_value = '0';
					}
					if ( $options[ 42 ] == '1' ) {
						$campayn = "checked";
						$campayn_value = '1';
					}
					else {
						$campayn = "";
						$campayn_value = '0';
					}
					if ( $options[ 46 ] == '1' ) {
						$constantcontact = "checked";
						$constantcontact_value = '1';
					}
					else {
						$constantcontact = "";
						$constantcontact_value = '0';
					}
					if ( $options[ 50 ] == '1' ) {
						$freshmail = "checked";
						$freshmail_value = '1';
					}
					else {
						$freshmail = "";
						$freshmail_value = '0';
					}
					if ( $options[ 54 ] == '1' ) {
						$getresponse = "checked";
						$getresponse_value = '1';
					}
					else {
						$getresponse = "";
						$getresponse_value = '0';
					}
					if ( $options[ 57 ] == '1' ) {
						$icontact = "checked";
						$icontact_value = '1';
					}
					else {
						$icontact = "";
						$icontact_value = '0';
					}
					if ( $options[ 62 ] == '1' ) {
						$infusionsoft = "checked";
						$infusionsoft_value = '1';
					}
					else {
						$infusionsoft = "";
						$infusionsoft_value = '0';
					}
					if ( $options[ 66 ] == '1' ) {
						$interspire = "checked";
						$interspire_value = '1';
					}
					else {
						$interspire = "";
						$interspire_value = '0';
					}
					if ( $options[ 70 ] == '1' ) {
						$madmimi = "checked";
						$madmimi_value = '1';
					}
					else {
						$madmimi = "";
						$madmimi_value = '0';
					}
					if ( $options[ 74 ] == '1' ) {
						$mailchimp = "checked";
						$mailchimp_value = '1';
					}
					else {
						$mailchimp = "";
						$mailchimp_value = '0';
					}
					if ( $options[ 77 ] == '1' ) {
						$mailerlite = "checked";
						$mailerlite_value = '1';
					}
					else {
						$mailerlite = "";
						$mailerlite_value = '0';
					}
					if ( $options[ 80 ] == '1' ) {
						$mailigen = "checked";
						$mailigen_value = '1';
					}
					else {
						$mailigen = "";
						$mailigen_value = '0';
					}
					if ( $options[ 81 ] == '1' ) {
						$mailigen_doubleoptin = "checked";
						$mailigen_doubleoptin_value = '1';
					}
					else {
						$mailigen_doubleoptin = "";
						$mailigen_doubleoptin_value = '0';
					}
					if ( $options[ 84 ] == '1' ) {
						$mailjet = "checked";
						$mailjet_value='1';
					}
					else {
						$mailjet = "";
						$mailjet_value = '0';
					}
					if ( $options[ 88 ] == '1' ) {
						$mailpoet = "checked";
						$mailpoet_value = '1';
					}
					else {
						$mailpoet = "";
						$mailpoet_value = '0';
					}
					if ( $options[ 90 ] == '1' ) {
						$emma = "checked";
						$emma_value = '1';
					}
					else {
						$emma = "";
						$emma_value = '0';
					}
					if ( $options[ 94 ] == '1' ) {
						$mymail = "checked";
						$mymail_value = '1';
					}
					else {
						$mymail = "";
						$mymail_value = '0';
					}
					if ( $options[ 96 ] == '1' ) {
						$ontraport = "checked";
						$ontraport_value = '1';
					}
					else {
						$ontraport = "";
						$ontraport_value = '0';
					}
					if ( $options[ 101 ] == '1' ) {
						$pinpointe = "checked";
						$pinpointe_value = '1';
					}
					else {
						$pinpointe = "";
						$pinpointe_value = '0';
					}
					if ( $options[ 105 ] == '1' ) {
						$sendinblue = "checked";
						$sendinblue_value = '1';
					}
					else {
						$sendinblue = "";
						$sendinblue_value = '0';
					}
					if ( $options[ 108 ] == '1' ) {
						$sendreach = "checked";
						$sendreach_value = '1';
					}
					else {
						$sendreach = "";
						$sendreach_value = '0';
					}
					if ( $options[ 113 ] == '1' ) {
						$sendy = "checked";
						$sendy_value = '1';
						}
					else {
						$sendy = "";
						$sendy_value = '0';
					}
					if ( $options[ 117 ] == '1' ) {
						$simplycast = "checked";
						$simplycast_value = '1';
					}
					else {
						$simplycast = "";
						$simplycast_value = '0';
					}
					if ( $options[ 121 ] == '1' ) {
						$ymlp = "checked";
						$ymlp_value='1';
					}
					else {
						$ymlp = "";
						$ymlp_value = '0';
					}
					if ( $options[ 125 ] == '1' ) {
						$msform_status = "checked";
						$msform_status_value='1';
					}
					else {
						$msform_status = "";
						$msform_status_value = '0';
					}
					if ( $options[ 160 ] == '1' ) {
						$msform_confirmation = "checked";
						$msform_confirmation_value='1';
					}
					else {
						$msform_confirmation = "";
						$msform_confirmation_value = '0';
					}
					if ( $options[ 161 ] == '1' ) {
						$msform_wosignup = "checked";
						$msform_wosignup_value='1';
					}
					else {
						$msform_wosignup = "";
						$msform_wosignup_value = '0';
					}
					if ( $options[ 126 ] == '1' ) {
						$msform_name_field = "checked";
						$msform_name_field_value='1';
					}
					else {
						$msform_name_field = "";
						$msform_name_field_value = '0';
					}
					if ( $options[ 127 ] == '1' ) {
						$msform_email_field = "checked";
						$msform_email_field_value='1';
					}
					else {
						$msform_email_field = "";
						$msform_email_field_value = '0';
					}
					if ( $options[ 146 ] == '1' ) {
						$msform_email_validate_field = "checked";
						$msform_email_validate_field_value='1';
					}
					else {
						$msform_email_validate_field = "";
						$msform_email_validate_field_value = '0';
					}
					if ( $sv->start_time != '0000-00-00 00:00:00' ) {
						$thisstart_time = $this->get_date_datetime( $sv->start_time );
					}
					else {
						$thisstart_time = $sv->start_time;
					}
					if ( $sv->expiry_time != '0000-00-00 00:00:00' ) {
						$thisexpiry_time = $this->get_date_datetime( $sv->expiry_time );
					}
					else {
						$thisexpiry_time = $sv->expiry_time;
					}
					$custom_fields = "";
					if ( ! isset( $options[ 159 ] ) ) {
						$options[ 159 ] = array();
					}
					print('<h3 class="dnone header_' . $sv->id . '">' . $sv->name . '</h3>
			<div id="'.$sv->id.'">
			<div class="configuration_accordion"><h4>' . __( 'General Behavior', MODAL_SURVEY_TEXT_DOMAIN ) . '</h4><div>
				<div>
					<div class="text">' . __( 'Display style:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="display_style" class="display_style">
						<option '.selected( $options[0], "bottom", false ).' value="bottom">' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[0], "top", false ).' value="top">' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[0], "center", false ).' value="center">' . __( 'Center', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div>
					<div class="text">' . __( 'Mode:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="survey_mode" class="survey_mode">
						<option '.selected( $options[17], "modal", false ).' value="modal">' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[17], "embed_end", false ).' value="embed_end">' . __( 'Embed to Posts End', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[17], "embed_start", false ).' value="embed_start">' . __( 'Embed to Posts Start', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[17], "embed_start_pages", false ).' value="embed_start_pages">' . __( 'Embed to Pages Start', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[17], "embed_end_pages", false ).' value="embed_end_pages">' . __( 'Embed to Pages End', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[17], "embed_topics", false ).' value="embed_topics">' . __( 'Embed to bbPress Topics', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div>	
					<div class="text">' . __( 'Animation Type:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="animation_type" class="animation_type">
									<option '.selected( $options[ 158 ], 'slide', false ).' value="slide">Slide</option>
									<option '.selected( $options[ 158 ], 'fade', false ).' value="fade">Fade</option>
					</select>
					</div>
					<div class="text">' . __( 'Animation Easing:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="animation_easing" class="animation_easing">
									<option '.selected( $options[1], 'linear', false ).' value="linear">linear</option>
									<option '.selected( $options[1], 'swing', false ).' value="swing">swing</option>
									<option '.selected( $options[1], 'easeInQuad', false ).' value="easeInQuad">easeInQuad</option>
									<option '.selected( $options[1], 'easeOutQuad', false ).' value="easeOutQuad">easeOutQuad</option>
									<option '.selected( $options[1], 'easeInOutQuad', false ).' value="easeInOutQuad">easeInOutQuad</option>
									<option '.selected( $options[1], 'easeInCubic', false ).' value="easeInCubic">easeInCubic</option>
									<option '.selected( $options[1], 'easeOutCubic', false ).' value="easeOutCubic">easeOutCubic</option>
									<option '.selected( $options[1], 'easeInOutCubic', false ).' value="easeInOutCubic">easeInOutCubic</option>
									<option '.selected( $options[1], 'easeInQuart', false ).' value="easeInQuart">easeInQuart</option>
									<option '.selected( $options[1], 'easeOutQuart', false ).' value="easeOutQuart">easeOutQuart</option>
									<option '.selected( $options[1], 'easeInOutQuart', false ).' value="easeInOutQuart">easeInOutQuart</option>
									<option '.selected( $options[1], 'easeInQuint', false ).' value="easeInQuint">easeInQuint</option>
									<option '.selected( $options[1], 'easeOutQuint', false ).' value="easeOutQuint">easeOutQuint</option>
									<option '.selected( $options[1], 'boteaseInOutQuinttom' ).' value="easeInOutQuint">easeInOutQuint</option>
									<option '.selected( $options[1], 'easeInExpo', false ).' value="easeInExpo">easeInExpo</option>
									<option '.selected( $options[1], 'easeOutExpo', false ).' value="easeOutExpo">easeOutExpo</option>
									<option '.selected( $options[1], 'easeInOutExpo', false ).' value="easeInOutExpo">easeInOutExpo</option>
									<option '.selected( $options[1], 'easeInSine', false ).' value="easeInSine">easeInSine</option>
									<option '.selected( $options[1], 'easeOutSine', false ).' value="easeOutSine">easeOutSine</option>
									<option '.selected( $options[1], 'easeInOutSine', false ).' value="easeInOutSine">easeInOutSine</option>
									<option '.selected( $options[1], 'easeInCirc', false ).' value="easeInCirc">easeInCirc</option>
									<option '.selected( $options[1], 'easeOutCirc', false ).' value="easeOutCirc">easeOutCirc</option>
									<option '.selected( $options[1], 'easeInOutCirc', false ).' value="easeInOutCirc">easeInOutCirc</option>
									<option '.selected( $options[1], 'easeInElastic', false ).' value="easeInElastic">easeInElastic</option>
									<option '.selected( $options[1], 'easeOutElastic', false ).' value="easeOutElastic">easeOutElastic</option>
									<option '.selected( $options[1], 'easeInOutElastic', false ).' value="easeInOutElastic">easeInOutElastic</option>
									<option '.selected( $options[1], 'easeInBack', false ).' value="easeInBack">easeInBack</option>
									<option '.selected( $options[1], 'easeOutBack', false ).' value="easeOutBack">easeOutBack</option>
									<option '.selected( $options[1], 'easeInOutBack', false ).' value="easeInOutBack">easeInOutBack</option>
									<option '.selected( $options[1], 'easeInBounce', false ).' value="easeInBounce">easeInBounce</option>
									<option '.selected( $options[1], 'easeOutBounce', false ).' value="easeOutBounce">easeOutBounce</option>
									<option '.selected( $options[1], 'easeInOutBounce', false ).' value="easeInOutBounce">easeInOutBounce</option>
					</select>
					</div> 
					<div class="modal_survey_sliders full"><input value="' . __( 'Animation Speed:', MODAL_SURVEY_TEXT_DOMAIN ) . ' ' . ( $options[ 11 ] / 1000 ) . 'sec" type="text" style="width:150px;" class="modal_survey_animation_speed_value" /><div class="modal_survey_animation_speed"></div></div>
					<div class="modal_survey_sliders full"><input value="' . __( 'End Delay:', MODAL_SURVEY_TEXT_DOMAIN ) . ' ' . ( $options[ 23 ] / 1000 ) . 'sec" type="text" style="width:150px;" class="modal_survey_end_delay_value" /><div class="modal_survey_end_delay modal_survey_tooltip" title="' . __( 'Delay time to hide the survey after the completion. Set it to 0 to disable automatic hiding.', MODAL_SURVEY_TEXT_DOMAIN ) . '"></div></div>
					<div class="modal_survey_sliders full"><input value="' . __( 'Display Timer:', MODAL_SURVEY_TEXT_DOMAIN ) . ' ' . ( $options[ 135 ] / 1000 ) . 'sec" type="text" style="width:150px;" class="modal_survey_display_timer_value" /><div class="modal_survey_display_timer modal_survey_tooltip" title="' . __( 'Delay time to display the survey.', MODAL_SURVEY_TEXT_DOMAIN ) . '"></div></div>
					<div class="modal_survey_sliders full"><input value="' . __( 'Quiz Timer:', MODAL_SURVEY_TEXT_DOMAIN ) . ' ' . ( $options[ 156 ] / 1000 ) . 'sec" type="text" style="width:150px;" class="modal_survey_quiz_timer_value" /><div class="modal_survey_quiz_timer modal_survey_tooltip" title="' . __( 'Increase it above 0sec to enable Quiz Timer.', MODAL_SURVEY_TEXT_DOMAIN ) . '"></div></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Enable if you want to display the survey on the entire website', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="global_survey" class="inputtext global_survey" '.$global_opt.' value="'.$sv->global.'" /> ' . __( 'Global Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<hr>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Lock the screen with a transparent background', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="lock_bg" ' . $opt_13 . ' class="inputtext lock_bg" value="' . $options[ 13 ] . '" /> ' . __( 'Lock Screen', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Users can close the survey', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="closeable" ' . $opt_14 . ' class="inputtext closeable" value="' . $options[ 14 ] . '" /> ' . __( 'Closeable', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'The survey will appear when the user scrolled down at the bottom of the page', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="atbottom" ' . $opt_15 . ' class="inputtext atbottom" value="' . $options[ 15 ] . '" /> ' . __( 'Display at bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Show survey for logged in users only', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="loggedin" ' . $opt_18 . ' class="inputtext loggedin" value="'.$options[15].'" /> ' . __( 'Logged In Only', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'If the user interrupt the survey, then it can be continued on the next visit.', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="remandcont" '.$opt_155.' class="inputtext remandcont ms-checkbox" value="' . $options[ 155 ] . '" /> ' . __( 'Allow Continue', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Use the Quiz Timer as Question Timer.', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="qtimer" '.$opt_157.' class="inputtext qtimer ms-checkbox" value="' . $options[ 157 ] . '" /> ' . __( 'Question Timer', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					</div></div>
			</div>
			<div class="configuration_accordion style-configuration"><h4>' . __( 'Style and Appearance', MODAL_SURVEY_TEXT_DOMAIN ) . '</h4><div>
				<div>		
					<div class="text">' . __( 'Colors:', MODAL_SURVEY_TEXT_DOMAIN ) . '
						<div style="clear:both;"></div><div title="' . __( 'Background Color', MODAL_SURVEY_TEXT_DOMAIN ) . '" id="ms_preview_inner'.$sv->id.'" class="ms-color-settings modal_survey_preview1001 modal_survey_preview modal_survey_tooltip"><div class="inner" style="background:'.str_replace(";",";background:",substr($options[3],0,-1)).'"><input type="hidden" class="bgcolor" value="'.$options[3].'"></div></div>
						<div title="' . __( 'Font Color', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="background-color:'.$options[4].'" class="modal_survey_preview1002 modal_survey_preview modal_survey_tooltip"></div>
						<div title="' . __( 'Border Color', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="background-color:'.$options[5].'" class="modal_survey_preview1003 modal_survey_preview modal_survey_tooltip"></div>
						<div title="' . __( 'Shadow Color', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="background-color:'.$options[132].'" class="modal_survey_preview1004 modal_survey_preview modal_survey_tooltip"></div><div style="clear:both;"></div>
					</div>	
					<div class="text">' . __( 'Text Align:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="text_align" class="text_align">
						<option '.selected( $options[16], "left", false ).' value="left">' . __( 'Left', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[16], "center", false ).' value="center">' . __( 'Center', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[16], "right", false ).' value="right">' . __( 'Right', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div> 
					<div class="text">' . __( 'Font Family:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <select name="font_family" class="font_family">
									<option '.selected( $options[2], '', false ).' value="">' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
									<option '.selected( $options[2], 'ABeeZee', false ).' value="ABeeZee">ABeeZee</option>
									<option '.selected( $options[2], 'Abel', false ).' value="Abel">Abel</option>
									<option '.selected( $options[2], 'Abril Fatface', false ).' value="Abril Fatface">Abril Fatface</option>
									<option '.selected( $options[2], 'Aclonica', false ).' value="Aclonica">Aclonica</option>
									<option '.selected( $options[2], 'Acme', false ).' value="Acme">Acme</option>
									<option '.selected( $options[2], 'Actor', false ).' value="Actor">Actor</option>
									<option '.selected( $options[2], 'Adamina', false ).' value="Adamina">Adamina</option>
									<option '.selected( $options[2], 'Advent Pro', false ).' value="Advent Pro">Advent Pro</option>
									<option '.selected( $options[2], 'Aguafina Script', false ).' value="Aguafina Script">Aguafina Script</option>
									<option '.selected( $options[2], 'Akronim', false ).' value="Akronim">Akronim</option>
									<option '.selected( $options[2], 'Aladin', false ).' value="Aladin">Aladin</option>
									<option '.selected( $options[2], 'Aldrich', false ).' value="Aldrich">Aldrich</option>
									<option '.selected( $options[2], 'Alef', false ).' value="Alef">Alef</option>
									<option '.selected( $options[2], 'Alegreya', false ).' value="Alegreya">Alegreya</option>
									<option '.selected( $options[2], 'Alegreya SC', false ).' value="Alegreya SC">Alegreya SC</option>
									<option '.selected( $options[2], 'Alex Brush', false ).' value="Alex Brush">Alex Brush</option>
									<option '.selected( $options[2], 'Alfa Slab One', false ).' value="Alfa Slab One">Alfa Slab One</option>
									<option '.selected( $options[2], 'Alice', false ).' value="Alice">Alice</option>
									<option '.selected( $options[2], 'Alike', false ).' value="Alike">Alike</option>
									<option '.selected( $options[2], 'Alike Angular', false ).' value="Alike Angular">Alike Angular</option>
									<option '.selected( $options[2], 'Allan', false ).' value="Allan">Allan</option>
									<option '.selected( $options[2], 'Allerta', false ).' value="Allerta">Allerta</option>
									<option '.selected( $options[2], 'Allerta Stencil', false ).' value="Allerta Stencil">Allerta Stencil</option>
									<option '.selected( $options[2], 'Allura', false ).' value="Allura">Allura</option>
									<option '.selected( $options[2], 'Almendra', false ).' value="Almendra">Almendra</option>
									<option '.selected( $options[2], 'Almendra Display', false ).' value="Almendra Display">Almendra Display</option>
									<option '.selected( $options[2], 'Almendra SC', false ).' value="Almendra SC">Almendra SC</option>
									<option '.selected( $options[2], 'Amarante', false ).' value="Amarante">Amarante</option>
									<option '.selected( $options[2], 'Amaranth', false ).' value="Amaranth">Amaranth</option>
									<option '.selected( $options[2], 'Amatic SC', false ).' value="Amatic SC">Amatic SC</option>
									<option '.selected( $options[2], 'Amethysta', false ).' value="Amethysta">Amethysta</option>
									<option '.selected( $options[2], 'Anaheim', false ).' value="Anaheim">Anaheim</option>
									<option '.selected( $options[2], 'Andada', false ).' value="Andada">Andada</option>
									<option '.selected( $options[2], 'Andika', false ).' value="Andika">Andika</option>
									<option '.selected( $options[2], 'Angkor', false ).' value="Angkor">Angkor</option>
									<option '.selected( $options[2], 'Annie Use Your Telescope', false ).' value="Annie Use Your Telescope">Annie Use Your Telescope</option>
									<option '.selected( $options[2], 'Anonymous Pro', false ).' value="Anonymous Pro">Anonymous Pro</option>
									<option '.selected( $options[2], 'Antic', false ).' value="Antic">Antic</option>
									<option '.selected( $options[2], 'Antic Didone', false ).' value="Antic Didone">Antic Didone</option>
									<option '.selected( $options[2], 'Antic Slab', false ).' value="Antic Slab">Antic Slab</option>
									<option '.selected( $options[2], 'Anton', false ).' value="Anton">Anton</option>
									<option '.selected( $options[2], 'Arapey', false ).' value="Arapey">Arapey</option>
									<option '.selected( $options[2], 'Arbutus', false ).' value="Arbutus">Arbutus</option>
									<option '.selected( $options[2], 'Arbutus Slab', false ).' value="Arbutus Slab">Arbutus Slab</option>
									<option '.selected( $options[2], 'Architects Daughter', false ).' value="Architects Daughter">Architects Daughter</option>
									<option '.selected( $options[2], 'Archivo Black', false ).' value="Archivo Black">Archivo Black</option>
									<option '.selected( $options[2], 'Archivo Narrow', false ).' value="Archivo Narrow">Archivo Narrow</option>
									<option '.selected( $options[2], 'Arimo', false ).' value="Arimo">Arimo</option>
									<option '.selected( $options[2], 'Arizonia', false ).' value="Arizonia">Arizonia</option>
									<option '.selected( $options[2], 'Armata', false ).' value="Armata">Armata</option>
									<option '.selected( $options[2], 'Artifika', false ).' value="Artifika">Artifika</option>
									<option '.selected( $options[2], 'Arvo', false ).' value="Arvo">Arvo</option>
									<option '.selected( $options[2], 'Asap', false ).' value="Asap">Asap</option>
									<option '.selected( $options[2], 'Asset', false ).' value="Asset">Asset</option>
									<option '.selected( $options[2], 'Astloch', false ).' value="Astloch">Astloch</option>
									<option '.selected( $options[2], 'Asul', false ).' value="Asul">Asul</option>
									<option '.selected( $options[2], 'Atomic Age', false ).' value="Atomic Age">Atomic Age</option>
									<option '.selected( $options[2], 'Aubrey', false ).' value="Aubrey">Aubrey</option>
									<option '.selected( $options[2], 'Audiowide', false ).' value="Audiowide">Audiowide</option>
									<option '.selected( $options[2], 'Autour One', false ).' value="Autour One">Autour One</option>
									<option '.selected( $options[2], 'Average', false ).' value="Average">Average</option>
									<option '.selected( $options[2], 'Average Sans', false ).' value="Average Sans">Average Sans</option>
									<option '.selected( $options[2], 'Averia Gruesa Libre', false ).' value="Averia Gruesa Libre">Averia Gruesa Libre</option>
									<option '.selected( $options[2], 'Averia Libre', false ).' value="Averia Libre">Averia Libre</option>
									<option '.selected( $options[2], 'Averia Sans Libre', false ).' value="Averia Sans Libre">Averia Sans Libre</option>
									<option '.selected( $options[2], 'Averia Serif Libre', false ).' value="Averia Serif Libre">Averia Serif Libre</option>
									<option '.selected( $options[2], 'Bad Script', false ).' value="Bad Script">Bad Script</option>
									<option '.selected( $options[2], 'Balthazar', false ).' value="Balthazar">Balthazar</option>
									<option '.selected( $options[2], 'Bangers', false ).' value="Bangers">Bangers</option>
									<option '.selected( $options[2], 'Basic', false ).' value="Basic">Basic</option>
									<option '.selected( $options[2], 'Battambang', false ).' value="Battambang">Battambang</option>
									<option '.selected( $options[2], 'Baumans', false ).' value="Baumans">Baumans</option>
									<option '.selected( $options[2], 'Bayon', false ).' value="Bayon">Bayon</option>
									<option '.selected( $options[2], 'Belgrano', false ).' value="Belgrano">Belgrano</option>
									<option '.selected( $options[2], 'Belleza', false ).' value="Belleza">Belleza</option>
									<option '.selected( $options[2], 'BenchNine', false ).' value="BenchNine">BenchNine</option>
									<option '.selected( $options[2], 'Bentham', false ).' value="Bentham">Bentham</option>
									<option '.selected( $options[2], 'Berkshire Swash', false ).' value="Berkshire Swash">Berkshire Swash</option>
									<option '.selected( $options[2], 'Bevan', false ).' value="Bevan">Bevan</option>
									<option '.selected( $options[2], 'Bigelow Rules', false ).' value="Bigelow Rules">Bigelow Rules</option>
									<option '.selected( $options[2], 'Bigshot One', false ).' value="Bigshot One">Bigshot One</option>
									<option '.selected( $options[2], 'Bilbo', false ).' value="Bilbo">Bilbo</option>
									<option '.selected( $options[2], 'Bilbo Swash Caps', false ).' value="Bilbo Swash Caps">Bilbo Swash Caps</option>
									<option '.selected( $options[2], 'Bitter', false ).' value="Bitter">Bitter</option>
									<option '.selected( $options[2], 'Black Ops One', false ).' value="Black Ops One">Black Ops One</option>
									<option '.selected( $options[2], 'Bokor', false ).' value="Bokor">Bokor</option>
									<option '.selected( $options[2], 'Bonbon', false ).' value="Bonbon">Bonbon</option>
									<option '.selected( $options[2], 'Boogaloo', false ).' value="Boogaloo">Boogaloo</option>
									<option '.selected( $options[2], 'Bowlby One', false ).' value="Bowlby One">Bowlby One</option>
									<option '.selected( $options[2], 'Bowlby One SC', false ).' value="Bowlby One SC">Bowlby One SC</option>
									<option '.selected( $options[2], 'Brawler', false ).' value="Brawler">Brawler</option>
									<option '.selected( $options[2], 'Bree Serif', false ).' value="Bree Serif">Bree Serif</option>
									<option '.selected( $options[2], 'Bubblegum Sans', false ).' value="Bubblegum Sans">Bubblegum Sans</option>
									<option '.selected( $options[2], 'Bubbler One', false ).' value="Bubbler One">Bubbler One</option>
									<option '.selected( $options[2], 'Buenard', false ).' value="Buenard">Buenard</option>
									<option '.selected( $options[2], 'Butcherman', false ).' value="Butcherman">Butcherman</option>
									<option '.selected( $options[2], 'Butterfly Kids', false ).' value="Butterfly Kids">Butterfly Kids</option>
									<option '.selected( $options[2], 'Cabin', false ).' value="Cabin">Cabin</option>
									<option '.selected( $options[2], 'Cabin Condensed', false ).' value="Cabin Condensed">Cabin Condensed</option>
									<option '.selected( $options[2], 'Cabin Sketch', false ).' value="Cabin Sketch">Cabin Sketch</option>
									<option '.selected( $options[2], 'Caesar Dressing', false ).' value="Caesar Dressing">Caesar Dressing</option>
									<option '.selected( $options[2], 'Cagliostro', false ).' value="Cagliostro">Cagliostro</option>
									<option '.selected( $options[2], 'Calligraffitti', false ).' value="Calligraffitti">Calligraffitti</option>
									<option '.selected( $options[2], 'ABeeCamboZee', false ).' value="Cambo">Cambo</option>
									<option '.selected( $options[2], 'Candal', false ).' value="Candal">Candal</option>
									<option '.selected( $options[2], 'Cantarell', false ).' value="Cantarell">Cantarell</option>
									<option '.selected( $options[2], 'Cantata One', false ).' value="Cantata One">Cantata One</option>
									<option '.selected( $options[2], 'Cantora One', false ).' value="Cantora One">Cantora One</option>
									<option '.selected( $options[2], 'Capriola', false ).' value="Capriola">Capriola</option>
									<option '.selected( $options[2], 'Cardo', false ).' value="Cardo">Cardo</option>
									<option '.selected( $options[2], 'Carme', false ).' value="Carme">Carme</option>
									<option '.selected( $options[2], 'Carrois Gothic', false ).' value="Carrois Gothic">Carrois Gothic</option>
									<option '.selected( $options[2], 'Carrois Gothic SC', false ).' value="Carrois Gothic SC">Carrois Gothic SC</option>
									<option '.selected( $options[2], 'Carter One', false ).' value="Carter One">Carter One</option>
									<option '.selected( $options[2], 'Caudex', false ).' value="Caudex">Caudex</option>
									<option '.selected( $options[2], 'Cedarville Cursive', false ).' value="Cedarville Cursive">Cedarville Cursive</option>
									<option '.selected( $options[2], 'Ceviche One', false ).' value="Ceviche One">Ceviche One</option>
									<option '.selected( $options[2], 'Changa One', false ).' value="Changa One">Changa One</option>
									<option '.selected( $options[2], 'Chango', false ).' value="Chango">Chango</option>
									<option '.selected( $options[2], 'Chau Philomene One', false ).' value="Chau Philomene One">Chau Philomene One</option>
									<option '.selected( $options[2], 'Chela One', false ).' value="Chela One">Chela One</option>
									<option '.selected( $options[2], 'Chelsea Market', false ).' value="Chelsea Market">Chelsea Market</option>
									<option '.selected( $options[2], 'Chenla', false ).' value="Chenla">Chenla</option>
									<option '.selected( $options[2], 'Cherry Cream Soda', false ).' value="Cherry Cream Soda">Cherry Cream Soda</option>
									<option '.selected( $options[2], 'Cherry Swash', false ).' value="Cherry Swash">Cherry Swash</option>
									<option '.selected( $options[2], 'Chewy', false ).' value="Chewy">Chewy</option>
									<option '.selected( $options[2], 'Chicle', false ).' value="Chicle">Chicle</option>
									<option '.selected( $options[2], 'Chivo', false ).' value="Chivo">Chivo</option>
									<option '.selected( $options[2], 'Cinzel', false ).' value="Cinzel">Cinzel</option>
									<option '.selected( $options[2], 'Cinzel Decorative', false ).' value="Cinzel Decorative">Cinzel Decorative</option>
									<option '.selected( $options[2], 'Clicker Script', false ).' value="Clicker Script">Clicker Script</option>
									<option '.selected( $options[2], 'Coda', false ).' value="Coda">Coda</option>
									<option '.selected( $options[2], 'Coda Caption:800', false ).' value="Coda Caption:800">Coda Caption</option>
									<option '.selected( $options[2], 'Codystar', false ).' value="Codystar">Codystar</option>
									<option '.selected( $options[2], 'Combo', false ).' value="Combo">Combo</option>
									<option '.selected( $options[2], 'Comfortaa', false ).' value="Comfortaa">Comfortaa</option>
									<option '.selected( $options[2], 'Coming Soon', false ).' value="Coming Soon">Coming Soon</option>
									<option '.selected( $options[2], 'Concert One', false ).' value="Concert One">Concert One</option>
									<option '.selected( $options[2], 'Condiment', false ).' value="Condiment">Condiment</option>
									<option '.selected( $options[2], 'Content', false ).' value="Content">Content</option>
									<option '.selected( $options[2], 'Contrail One', false ).' value="Contrail One">Contrail One</option>
									<option '.selected( $options[2], 'Convergence', false ).' value="Convergence">Convergence</option>
									<option '.selected( $options[2], 'Cookie', false ).' value="Cookie">Cookie</option>
									<option '.selected( $options[2], 'Copse', false ).' value="Copse">Copse</option>
									<option '.selected( $options[2], 'Corben', false ).' value="Corben">Corben</option>
									<option '.selected( $options[2], 'Courgette', false ).' value="Courgette">Courgette</option>
									<option '.selected( $options[2], 'Cousine', false ).' value="Cousine">Cousine</option>
									<option '.selected( $options[2], 'Coustard', false ).' value="Coustard">Coustard</option>
									<option '.selected( $options[2], 'Covered By Your Grace', false ).' value="Covered By Your Grace">Covered By Your Grace</option>
									<option '.selected( $options[2], 'Crafty Girls', false ).' value="Crafty Girls">Crafty Girls</option>
									<option '.selected( $options[2], 'Creepster', false ).' value="Creepster">Creepster</option>
									<option '.selected( $options[2], 'Crete Round', false ).' value="Crete Round">Crete Round</option>
									<option '.selected( $options[2], 'Crimson Text', false ).' value="Crimson Text">Crimson Text</option>
									<option '.selected( $options[2], 'Croissant One', false ).' value="Croissant One">Croissant One</option>
									<option '.selected( $options[2], 'Crushed', false ).' value="Crushed">Crushed</option>
									<option '.selected( $options[2], 'Cuprum', false ).' value="Cuprum">Cuprum</option>
									<option '.selected( $options[2], 'Cutive', false ).' value="Cutive">Cutive</option>
									<option '.selected( $options[2], 'Cutive Mono', false ).' value="Cutive Mono">Cutive Mono</option>
									<option '.selected( $options[2], 'Damion', false ).' value="Damion">Damion</option>
									<option '.selected( $options[2], 'Dancing Script', false ).' value="Dancing Script">Dancing Script</option>
									<option '.selected( $options[2], 'Dangrek', false ).' value="Dangrek">Dangrek</option>
									<option '.selected( $options[2], 'Dawning of a New Day', false ).' value="Dawning of a New Day">Dawning of a New Day</option>
									<option '.selected( $options[2], 'Days One', false ).' value="Days One">Days One</option>
									<option '.selected( $options[2], 'Delius', false ).' value="Delius">Delius</option>
									<option '.selected( $options[2], 'Delius Swash Caps', false ).' value="Delius Swash Caps">Delius Swash Caps</option>
									<option '.selected( $options[2], 'Delius Unicase', false ).' value="Delius Unicase">Delius Unicase</option>
									<option '.selected( $options[2], 'Della Respira', false ).' value="Della Respira">Della Respira</option>
									<option '.selected( $options[2], 'Denk One', false ).' value="Denk One">Denk One</option>
									<option '.selected( $options[2], 'Devonshire', false ).' value="Devonshire">Devonshire</option>
									<option '.selected( $options[2], 'Didact Gothic', false ).' value="Didact Gothic">Didact Gothic</option>
									<option '.selected( $options[2], 'Diplomata', false ).' value="Diplomata">Diplomata</option>
									<option '.selected( $options[2], 'Diplomata SC', false ).' value="Diplomata SC">Diplomata SC</option>
									<option '.selected( $options[2], 'Domine', false ).' value="Domine">Domine</option>
									<option '.selected( $options[2], 'Donegal One', false ).' value="Donegal One">Donegal One</option>
									<option '.selected( $options[2], 'Doppio One', false ).' value="Doppio One">Doppio One</option>
									<option '.selected( $options[2], 'Dorsa', false ).' value="Dorsa">Dorsa</option>
									<option '.selected( $options[2], 'Dosis', false ).' value="Dosis">Dosis</option>
									<option '.selected( $options[2], 'Dr Sugiyama', false ).' value="Dr Sugiyama">Dr Sugiyama</option>
									<option '.selected( $options[2], 'Droid Sans', false ).' value="Droid Sans">Droid Sans</option>
									<option '.selected( $options[2], 'Droid Sans Mono', false ).' value="Droid Sans Mono">Droid Sans Mono</option>
									<option '.selected( $options[2], 'Droid Serif', false ).' value="Droid Serif">Droid Serif</option>
									<option '.selected( $options[2], 'Duru Sans', false ).' value="Duru Sans">Duru Sans</option>
									<option '.selected( $options[2], 'Dynalight', false ).' value="Dynalight">Dynalight</option>
									<option '.selected( $options[2], 'Eagle Lake', false ).' value="Eagle Lake">Eagle Lake</option>
									<option '.selected( $options[2], 'Eater', false ).' value="Eater">Eater</option>
									<option '.selected( $options[2], 'EB Garamond', false ).' value="EB Garamond">EB Garamond</option>
									<option '.selected( $options[2], 'Economica', false ).' value="Economica">Economica</option>
									<option '.selected( $options[2], 'Electrolize', false ).' value="Electrolize">Electrolize</option>
									<option '.selected( $options[2], 'Elsie', false ).' value="Elsie">Elsie</option>
									<option '.selected( $options[2], 'Elsie Swash Caps', false ).' value="Elsie Swash Caps">Elsie Swash Caps</option>
									<option '.selected( $options[2], 'Emblema One', false ).' value="Emblema One">Emblema One</option>
									<option '.selected( $options[2], 'Emilys Candy', false ).' value="Emilys Candy">Emilys Candy</option>
									<option '.selected( $options[2], 'Engagement', false ).' value="Engagement">Engagement</option>
									<option '.selected( $options[2], 'Englebert', false ).' value="Englebert">Englebert</option>
									<option '.selected( $options[2], 'Enriqueta', false ).' value="Enriqueta">Enriqueta</option>
									<option '.selected( $options[2], 'Erica One', false ).' value="Erica One">Erica One</option>
									<option '.selected( $options[2], 'Esteban', false ).' value="Esteban">Esteban</option>
									<option '.selected( $options[2], 'Euphoria Script', false ).' value="Euphoria Script">Euphoria Script</option>
									<option '.selected( $options[2], 'Ewert', false ).' value="Ewert">Ewert</option>
									<option '.selected( $options[2], 'Exo', false ).' value="Exo">Exo</option>
									<option '.selected( $options[2], 'Expletus Sans', false ).' value="Expletus Sans">Expletus Sans</option>
									<option '.selected( $options[2], 'Fanwood Text', false ).' value="Fanwood Text">Fanwood Text</option>
									<option '.selected( $options[2], 'Fascinate', false ).' value="Fascinate">Fascinate</option>
									<option '.selected( $options[2], 'Fascinate Inline', false ).' value="Fascinate Inline">Fascinate Inline</option>
									<option '.selected( $options[2], 'Faster One', false ).' value="Faster One">Faster One</option>
									<option '.selected( $options[2], 'Fasthand', false ).' value="Fasthand">Fasthand</option>
									<option '.selected( $options[2], 'Fauna One', false ).' value="Fauna One">Fauna One</option>
									<option '.selected( $options[2], 'Federant', false ).' value="Federant">Federant</option>
									<option '.selected( $options[2], 'Federo', false ).' value="Federo">Federo</option>
									<option '.selected( $options[2], 'Felipa', false ).' value="Felipa">Felipa</option>
									<option '.selected( $options[2], 'Fenix', false ).' value="Fenix">Fenix</option>
									<option '.selected( $options[2], 'Finger Paint', false ).' value="Finger Paint">Finger Paint</option>
									<option '.selected( $options[2], 'Fjalla One', false ).' value="Fjalla One">Fjalla One</option>
									<option '.selected( $options[2], 'Fjord One', false ).' value="Fjord One">Fjord One</option>
									<option '.selected( $options[2], 'Flamenco', false ).' value="Flamenco">Flamenco</option>
									<option '.selected( $options[2], 'Flavors', false ).' value="Flavors">Flavors</option>
									<option '.selected( $options[2], 'Fondamento', false ).' value="Fondamento">Fondamento</option>
									<option '.selected( $options[2], 'Fontdiner Swanky', false ).' value="Fontdiner Swanky">Fontdiner Swanky</option>
									<option '.selected( $options[2], 'Forum', false ).' value="Forum">Forum</option>
									<option '.selected( $options[2], 'Francois One', false ).' value="Francois One">Francois One</option>
									<option '.selected( $options[2], 'Freckle Face', false ).' value="Freckle Face">Freckle Face</option>
									<option '.selected( $options[2], 'Fredericka the Great', false ).' value="Fredericka the Great">Fredericka the Great</option>
									<option '.selected( $options[2], 'Fredoka One', false ).' value="Fredoka One">Fredoka One</option>
									<option '.selected( $options[2], 'Freehand', false ).' value="Freehand">Freehand</option>
									<option '.selected( $options[2], 'Fresca', false ).' value="Fresca">Fresca</option>
									<option '.selected( $options[2], 'Frijole', false ).' value="Frijole">Frijole</option>
									<option '.selected( $options[2], 'Fruktur', false ).' value="Fruktur">Fruktur</option>
									<option '.selected( $options[2], 'Fugaz One', false ).' value="Fugaz One">Fugaz One</option>
									<option '.selected( $options[2], 'Gabriela', false ).' value="Gabriela">Gabriela</option>
									<option '.selected( $options[2], 'Gafata', false ).' value="Gafata">Gafata</option>
									<option '.selected( $options[2], 'Galdeano', false ).' value="Galdeano">Galdeano</option>
									<option '.selected( $options[2], 'Galindo', false ).' value="Galindo">Galindo</option>
									<option '.selected( $options[2], 'Gentium Basic', false ).' value="Gentium Basic">Gentium Basic</option>
									<option '.selected( $options[2], 'Gentium Book Basic', false ).' value="Gentium Book Basic">Gentium Book Basic</option>
									<option '.selected( $options[2], 'Geo', false ).' value="Geo">Geo</option>
									<option '.selected( $options[2], 'Geostar', false ).' value="Geostar">Geostar</option>
									<option '.selected( $options[2], 'Geostar Fill', false ).' value="Geostar Fill">Geostar Fill</option>
									<option '.selected( $options[2], 'Germania One', false ).' value="Germania One">Germania One</option>
									<option '.selected( $options[2], 'GFS Didot', false ).' value="GFS Didot">GFS Didot</option>
									<option '.selected( $options[2], 'GFS Neohellenic', false ).' value="GFS Neohellenic">GFS Neohellenic</option>
									<option '.selected( $options[2], 'GFS Neohellenic', false ).' value="c">Gilda Display</option>
									<option '.selected( $options[2], 'Give You Glory', false ).' value="Give You Glory">Give You Glory</option>
									<option '.selected( $options[2], 'Glass Antiqua', false ).' value="Glass Antiqua">Glass Antiqua</option>
									<option '.selected( $options[2], 'Glegoo', false ).' value="Glegoo">Glegoo</option>
									<option '.selected( $options[2], 'Gloria Hallelujah', false ).' value="Gloria Hallelujah">Gloria Hallelujah</option>
									<option '.selected( $options[2], 'Goblin One', false ).' value="Goblin One">Goblin One</option>
									<option '.selected( $options[2], 'Gochi Hand', false ).' value="Gochi Hand">Gochi Hand</option>
									<option '.selected( $options[2], 'Gorditas', false ).' value="Gorditas">Gorditas</option>
									<option '.selected( $options[2], 'Goudy Bookletter 1911', false ).' value="Goudy Bookletter 1911">Goudy Bookletter 1911</option>
									<option '.selected( $options[2], 'Graduate', false ).' value="Graduate">Graduate</option>
									<option '.selected( $options[2], 'Grand Hotel', false ).' value="Grand Hotel">Grand Hotel</option>
									<option '.selected( $options[2], 'Gravitas One', false ).' value="Gravitas One">Gravitas One</option>
									<option '.selected( $options[2], 'Great Vibes', false ).' value="Great Vibes">Great Vibes</option>
									<option '.selected( $options[2], 'Griffy', false ).' value="Griffy">Griffy</option>
									<option '.selected( $options[2], 'Gruppo', false ).' value="Gruppo">Gruppo</option>
									<option '.selected( $options[2], 'Gudea', false ).' value="Gudea">Gudea</option>
									<option '.selected( $options[2], 'Habibi', false ).' value="Habibi">Habibi</option>
									<option '.selected( $options[2], 'Hammersmith One', false ).' value="Hammersmith One">Hammersmith One</option>
									<option '.selected( $options[2], 'Hanalei', false ).' value="Hanalei">Hanalei</option>
									<option '.selected( $options[2], 'Hanalei Fill', false ).' value="Hanalei Fill">Hanalei Fill</option>
									<option '.selected( $options[2], 'Handlee', false ).' value="Handlee">Handlee</option>
									<option '.selected( $options[2], 'Hanuman', false ).' value="Hanuman">Hanuman</option>
									<option '.selected( $options[2], 'Happy Monkey', false ).' value="Happy Monkey">Happy Monkey</option>
									<option '.selected( $options[2], 'Headland One', false ).' value="Headland One">Headland One</option>
									<option '.selected( $options[2], 'Henny Penny', false ).' value="Henny Penny">Henny Penny</option>
									<option '.selected( $options[2], 'Herr Von Muellerhoff', false ).' value="Herr Von Muellerhoff">Herr Von Muellerhoff</option>
									<option '.selected( $options[2], 'Holtwood One SC', false ).' value="Holtwood One SC">Holtwood One SC</option>
									<option '.selected( $options[2], 'Homemade Apple', false ).' value="Homemade Apple">Homemade Apple</option>
									<option '.selected( $options[2], 'Homenaje', false ).' value="Homenaje">Homenaje</option>
									<option '.selected( $options[2], 'Iceberg', false ).' value="Iceberg">Iceberg</option>
									<option '.selected( $options[2], 'Iceland', false ).' value="Iceland">Iceland</option>
									<option '.selected( $options[2], 'IM Fell Double Pica', false ).' value="IM Fell Double Pica">IM Fell Double Pica</option>
									<option '.selected( $options[2], 'IM Fell Double Pica SC', false ).' value="IM Fell Double Pica SC">IM Fell Double Pica SC</option>
									<option '.selected( $options[2], 'IM Fell DW Pica', false ).' value="IM Fell DW Pica">IM Fell DW Pica</option>
									<option '.selected( $options[2], 'IM Fell DW Pica SC', false ).' value="IM Fell DW Pica SC">IM Fell DW Pica SC</option>
									<option '.selected( $options[2], 'IM Fell English', false ).' value="IM Fell English">IM Fell English</option>
									<option '.selected( $options[2], 'IM Fell English SC', false ).' value="IM Fell English SC">IM Fell English SC</option>
									<option '.selected( $options[2], 'IM Fell French Canon', false ).' value="IM Fell French Canon">IM Fell French Canon</option>
									<option '.selected( $options[2], 'IM Fell French Canon SC', false ).' value="IM Fell French Canon SC">IM Fell French Canon SC</option>
									<option '.selected( $options[2], 'IM Fell Great Primer', false ).' value="IM Fell Great Primer">IM Fell Great Primer</option>
									<option '.selected( $options[2], 'IM Fell Great Primer SC', false ).' value="IM Fell Great Primer SC">IM Fell Great Primer SC</option>
									<option '.selected( $options[2], 'Imprima', false ).' value="Imprima">Imprima</option>
									<option '.selected( $options[2], 'Inconsolata', false ).' value="Inconsolata">Inconsolata</option>
									<option '.selected( $options[2], 'Inder', false ).' value="Inder">Inder</option>
									<option '.selected( $options[2], 'Indie Flower', false ).' value="Indie Flower">Indie Flower</option>
									<option '.selected( $options[2], 'Inika', false ).' value="Inika">Inika</option>
									<option '.selected( $options[2], 'Irish Grover', false ).' value="Irish Grover">Irish Grover</option>
									<option '.selected( $options[2], 'Istok Web', false ).' value="Istok Web">Istok Web</option>
									<option '.selected( $options[2], 'Italiana', false ).' value="Italiana">Italiana</option>
									<option '.selected( $options[2], 'Italianno', false ).' value="Italianno">Italianno</option>
									<option '.selected( $options[2], 'Jacques Francois', false ).' value="Jacques Francois">Jacques Francois</option>
									<option '.selected( $options[2], 'Jacques Francois Shadow', false ).' value="Jacques Francois Shadow">Jacques Francois Shadow</option>
									<option '.selected( $options[2], 'Jim Nightshade', false ).' value="Jim Nightshade">Jim Nightshade</option>
									<option '.selected( $options[2], 'Jockey One', false ).' value="Jockey One">Jockey One</option>
									<option '.selected( $options[2], 'Jolly Lodger', false ).' value="Jolly Lodger">Jolly Lodger</option>
									<option '.selected( $options[2], 'Josefin Sans', false ).' value="Josefin Sans">Josefin Sans</option>
									<option '.selected( $options[2], 'Josefin Slab', false ).' value="Josefin Slab">Josefin Slab</option>
									<option '.selected( $options[2], 'Joti One', false ).' value="Joti One">Joti One</option>
									<option '.selected( $options[2], 'Judson', false ).' value="Judson">Judson</option>
									<option '.selected( $options[2], 'Julee', false ).' value="Julee">Julee</option>
									<option '.selected( $options[2], 'Julius Sans One', false ).' value="Julius Sans One">Julius Sans One</option>
									<option '.selected( $options[2], 'Junge', false ).' value="Junge">Junge</option>
									<option '.selected( $options[2], 'Jura', false ).' value="Jura">Jura</option>
									<option '.selected( $options[2], 'Just Another Hand', false ).' value="Just Another Hand">Just Another Hand</option>
									<option '.selected( $options[2], 'Just Me Again Down Here', false ).' value="Just Me Again Down Here">Just Me Again Down Here</option>
									<option '.selected( $options[2], 'Kameron', false ).' value="Kameron">Kameron</option>
									<option '.selected( $options[2], 'Karla', false ).' value="Karla">Karla</option>
									<option '.selected( $options[2], 'Kaushan Script', false ).' value="Kaushan Script">Kaushan Script</option>
									<option '.selected( $options[2], 'Kavoon', false ).' value="Kavoon">Kavoon</option>
									<option '.selected( $options[2], 'Keania One', false ).' value="Keania One">Keania One</option>
									<option '.selected( $options[2], 'Kelly Slab', false ).' value="Kelly Slab">Kelly Slab</option>
									<option '.selected( $options[2], 'Kenia', false ).' value="Kenia">Kenia</option>
									<option '.selected( $options[2], 'Khmer', false ).' value="Khmer">Khmer</option>
									<option '.selected( $options[2], 'Khmer', false ).' value="c">Kite One</option>
									<option '.selected( $options[2], 'Knewave', false ).' value="Knewave">Knewave</option>
									<option '.selected( $options[2], 'Kotta One', false ).' value="Kotta One">Kotta One</option>
									<option '.selected( $options[2], 'Koulen', false ).' value="Koulen">Koulen</option>
									<option '.selected( $options[2], 'Kranky', false ).' value="Kranky">Kranky</option>
									<option '.selected( $options[2], 'Kreon', false ).' value="Kreon">Kreon</option>
									<option '.selected( $options[2], 'Kristi', false ).' value="Kristi">Kristi</option>
									<option '.selected( $options[2], 'Krona One', false ).' value="Krona One">Krona One</option>
									<option '.selected( $options[2], 'La Belle Aurore', false ).' value="La Belle Aurore">La Belle Aurore</option>
									<option '.selected( $options[2], 'Lancelot', false ).' value="Lancelot">Lancelot</option>
									<option '.selected( $options[2], 'Lato', false ).' value="Lato">Lato</option>
									<option '.selected( $options[2], 'League Script', false ).' value="League Script">League Script</option>
									<option '.selected( $options[2], 'Leckerli One', false ).' value="Leckerli One">Leckerli One</option>
									<option '.selected( $options[2], 'Ledger', false ).' value="Ledger">Ledger</option>
									<option '.selected( $options[2], 'Lekton', false ).' value="Lekton">Lekton</option>
									<option '.selected( $options[2], 'Lemon', false ).' value="Lemon">Lemon</option>
									<option '.selected( $options[2], 'Libre Baskerville', false ).' value="Libre Baskerville">Libre Baskerville</option>
									<option '.selected( $options[2], 'Life Savers', false ).' value="Life Savers">Life Savers</option>
									<option '.selected( $options[2], 'Lilita One', false ).' value="Lilita One">Lilita One</option>
									<option '.selected( $options[2], 'Lily Script One', false ).' value="Lily Script One">Lily Script One</option>
									<option '.selected( $options[2], 'Limelight', false ).' value="Limelight">Limelight</option>
									<option '.selected( $options[2], 'Linden Hill', false ).' value="Linden Hill">Linden Hill</option>
									<option '.selected( $options[2], 'Lobster', false ).' value="Lobster">Lobster</option>
									<option '.selected( $options[2], 'Lobster Two', false ).' value="Lobster Two">Lobster Two</option>
									<option '.selected( $options[2], 'Londrina Outline', false ).' value="Londrina Outline">Londrina Outline</option>
									<option '.selected( $options[2], 'Londrina Shadow', false ).' value="Londrina Shadow">Londrina Shadow</option>
									<option '.selected( $options[2], 'Londrina Sketch', false ).' value="Londrina Sketch">Londrina Sketch</option>
									<option '.selected( $options[2], 'Londrina Solid', false ).' value="Londrina Solid">Londrina Solid</option>
									<option '.selected( $options[2], 'Lora', false ).' value="Lora">Lora</option>
									<option '.selected( $options[2], 'Love Ya Like A Sister', false ).' value="Love Ya Like A Sister">Love Ya Like A Sister</option>
									<option '.selected( $options[2], 'Loved by the King', false ).' value="Loved by the King">Loved by the King</option>
									<option '.selected( $options[2], 'Lovers Quarrel', false ).' value="Lovers Quarrel">Lovers Quarrel</option>
									<option '.selected( $options[2], 'Luckiest Guy', false ).' value="Luckiest Guy">Luckiest Guy</option>
									<option '.selected( $options[2], 'Lusitana', false ).' value="Lusitana">Lusitana</option>
									<option '.selected( $options[2], 'Lustria', false ).' value="Lustria">Lustria</option>
									<option '.selected( $options[2], 'Macondo', false ).' value="Macondo">Macondo</option>
									<option '.selected( $options[2], 'Macondo Swash Caps', false ).' value="Macondo Swash Caps">Macondo Swash Caps</option>
									<option '.selected( $options[2], 'ABeeMagraZee', false ).' value="Magra">Magra</option>
									<option '.selected( $options[2], 'Maiden Orange', false ).' value="Maiden Orange">Maiden Orange</option>
									<option '.selected( $options[2], 'Mako', false ).' value="Mako">Mako</option>
									<option '.selected( $options[2], 'Marcellus', false ).' value="Marcellus">Marcellus</option>
									<option '.selected( $options[2], 'Marcellus SC', false ).' value="Marcellus SC">Marcellus SC</option>
									<option '.selected( $options[2], 'Marck Script', false ).' value="Marck Script">Marck Script</option>
									<option '.selected( $options[2], 'Margarine', false ).' value="Margarine">Margarine</option>
									<option '.selected( $options[2], 'Marko One', false ).' value="Marko One">Marko One</option>
									<option '.selected( $options[2], 'Marmelad', false ).' value="Marmelad">Marmelad</option>
									<option '.selected( $options[2], 'Marvel', false ).' value="Marvel">Marvel</option>
									<option '.selected( $options[2], 'Mate', false ).' value="Mate">Mate</option>
									<option '.selected( $options[2], 'Mate SC', false ).' value="Mate SC">Mate SC</option>
									<option '.selected( $options[2], 'Maven Pro', false ).' value="Maven Pro">Maven Pro</option>
									<option '.selected( $options[2], 'McLaren', false ).' value="McLaren">McLaren</option>
									<option '.selected( $options[2], 'Meddon', false ).' value="Meddon">Meddon</option>
									<option '.selected( $options[2], 'MedievalSharp', false ).' value="MedievalSharp">MedievalSharp</option>
									<option '.selected( $options[2], 'Medula One', false ).' value="Medula One">Medula One</option>
									<option '.selected( $options[2], 'Megrim', false ).' value="Megrim">Megrim</option>
									<option '.selected( $options[2], 'Meie Script', false ).' value="Meie Script">Meie Script</option>
									<option '.selected( $options[2], 'Merienda', false ).' value="Merienda">Merienda</option>
									<option '.selected( $options[2], 'Merienda One', false ).' value="Merienda One">Merienda One</option>
									<option '.selected( $options[2], 'Merriweather', false ).' value="Merriweather">Merriweather</option>
									<option '.selected( $options[2], 'Merriweather Sans', false ).' value="Merriweather Sans">Merriweather Sans</option>
									<option '.selected( $options[2], 'Metal', false ).' value="Metal">Metal</option>
									<option '.selected( $options[2], 'Metal Mania', false ).' value="Metal Mania">Metal Mania</option>
									<option '.selected( $options[2], 'Metamorphous', false ).' value="Metamorphous">Metamorphous</option>
									<option '.selected( $options[2], 'Metrophobic', false ).' value="Metrophobic">Metrophobic</option>
									<option '.selected( $options[2], 'Michroma', false ).' value="Michroma">Michroma</option>
									<option '.selected( $options[2], 'Milonga', false ).' value="Milonga">Milonga</option>
									<option '.selected( $options[2], 'Miltonian', false ).' value="Miltonian">Miltonian</option>
									<option '.selected( $options[2], 'Miltonian Tattoo', false ).' value="Miltonian Tattoo">Miltonian Tattoo</option>
									<option '.selected( $options[2], 'Miniver', false ).' value="Miniver">Miniver</option>
									<option '.selected( $options[2], 'Miss Fajardose', false ).' value="Miss Fajardose">Miss Fajardose</option>
									<option '.selected( $options[2], 'Modern Antiqua', false ).' value="Modern Antiqua">Modern Antiqua</option>
									<option '.selected( $options[2], 'Molengo', false ).' value="Molengo">Molengo</option>
									<option '.selected( $options[2], 'Molle:400italic', false ).' value="Molle:400italic">Molle</option>
									<option '.selected( $options[2], 'Monda', false ).' value="Monda">Monda</option>
									<option '.selected( $options[2], 'Monofett', false ).' value="Monofett">Monofett</option>
									<option '.selected( $options[2], 'Monoton', false ).' value="Monoton">Monoton</option>
									<option '.selected( $options[2], 'Monsieur La Doulaise', false ).' value="Monsieur La Doulaise">Monsieur La Doulaise</option>
									<option '.selected( $options[2], 'Montaga', false ).' value="Montaga">Montaga</option>
									<option '.selected( $options[2], 'Montez', false ).' value="Montez">Montez</option>
									<option '.selected( $options[2], 'Montserrat', false ).' value="Montserrat">Montserrat</option>
									<option '.selected( $options[2], 'Montserrat Alternates', false ).' value="Montserrat Alternates">Montserrat Alternates</option>
									<option '.selected( $options[2], 'Montserrat Subrayada', false ).' value="Montserrat Subrayada">Montserrat Subrayada</option>
									<option '.selected( $options[2], 'Moul', false ).' value="Moul">Moul</option>
									<option '.selected( $options[2], 'Moulpali', false ).' value="Moulpali">Moulpali</option>
									<option '.selected( $options[2], 'Mountains of Christmas', false ).' value="Mountains of Christmas">Mountains of Christmas</option>
									<option '.selected( $options[2], 'Mouse Memoirs', false ).' value="Mouse Memoirs">Mouse Memoirs</option>
									<option '.selected( $options[2], 'Mr Bedfort', false ).' value="Mr Bedfort">Mr Bedfort</option>
									<option '.selected( $options[2], 'Mr Dafoe', false ).' value="Mr Dafoe">Mr Dafoe</option>
									<option '.selected( $options[2], 'Mr De Haviland', false ).' value="Mr De Haviland">Mr De Haviland</option>
									<option '.selected( $options[2], 'Mrs Saint Delafield', false ).' value="Mrs Saint Delafield">Mrs Saint Delafield</option>
									<option '.selected( $options[2], 'Mrs Sheppards', false ).' value="Mrs Sheppards">Mrs Sheppards</option>
									<option '.selected( $options[2], 'Muli', false ).' value="Muli">Muli</option>
									<option '.selected( $options[2], 'Mystery Quest', false ).' value="Mystery Quest">Mystery Quest</option>
									<option '.selected( $options[2], 'Neucha', false ).' value="Neucha">Neucha</option>
									<option '.selected( $options[2], 'Neuton', false ).' value="Neuton">Neuton</option>
									<option '.selected( $options[2], 'New Rocker', false ).' value="New Rocker">New Rocker</option>
									<option '.selected( $options[2], 'News Cycle', false ).' value="News Cycle">News Cycle</option>
									<option '.selected( $options[2], 'Niconne', false ).' value="Niconne">Niconne</option>
									<option '.selected( $options[2], 'Nixie One', false ).' value="Nixie One">Nixie One</option>
									<option '.selected( $options[2], 'Nobile', false ).' value="Nobile">Nobile</option>
									<option '.selected( $options[2], 'Nokora', false ).' value="Nokora">Nokora</option>
									<option '.selected( $options[2], 'Norican', false ).' value="Norican">Norican</option>
									<option '.selected( $options[2], 'Nosifer', false ).' value="Nosifer">Nosifer</option>
									<option '.selected( $options[2], 'Nothing You Could Do', false ).' value="Nothing You Could Do">Nothing You Could Do</option>
									<option '.selected( $options[2], 'Noticia Text', false ).' value="Noticia Text">Noticia Text</option>
									<option '.selected( $options[2], 'Noto Sans', false ).' value="Noto Sans">Noto Sans</option>
									<option '.selected( $options[2], 'Noto Serif', false ).' value="Noto Serif">Noto Serif</option>
									<option '.selected( $options[2], 'Nova Cut', false ).' value="Nova Cut">Nova Cut</option>
									<option '.selected( $options[2], 'Nova Flat', false ).' value="Nova Flat">Nova Flat</option>
									<option '.selected( $options[2], 'Nova Mono', false ).' value="Nova Mono">Nova Mono</option>
									<option '.selected( $options[2], 'Nova Oval', false ).' value="Nova Oval">Nova Oval</option>
									<option '.selected( $options[2], 'Nova Round', false ).' value="Nova Round">Nova Round</option>
									<option '.selected( $options[2], 'Nova Script', false ).' value="Nova Script">Nova Script</option>
									<option '.selected( $options[2], 'Nova Slim', false ).' value="Nova Slim">Nova Slim</option>
									<option '.selected( $options[2], 'Nova Square', false ).' value="Nova Square">Nova Square</option>
									<option '.selected( $options[2], 'Numans', false ).' value="Numans">Numans</option>
									<option '.selected( $options[2], 'Nunito', false ).' value="Nunito">Nunito</option>
									<option '.selected( $options[2], 'Odor Mean Chey', false ).' value="Odor Mean Chey">Odor Mean Chey</option>
									<option '.selected( $options[2], 'Offside', false ).' value="Offside">Offside</option>
									<option '.selected( $options[2], 'Old Standard TT', false ).' value="Old Standard TT">Old Standard TT</option>
									<option '.selected( $options[2], 'Oldenburg', false ).' value="Oldenburg">Oldenburg</option>
									<option '.selected( $options[2], 'Oleo Script', false ).' value="Oleo Script">Oleo Script</option>
									<option '.selected( $options[2], 'Oleo Script Swash Caps', false ).' value="Oleo Script Swash Caps">Oleo Script Swash Caps</option>
									<option '.selected( $options[2], 'Open Sans', false ).' value="Open Sans">Open Sans</option>
									<option '.selected( $options[2], 'Open Sans Condensed:300', false ).' value="Open Sans Condensed:300">Open Sans Condensed</option>
									<option '.selected( $options[2], 'Oranienbaum', false ).' value="Oranienbaum">Oranienbaum</option>
									<option '.selected( $options[2], 'Orbitron', false ).' value="Orbitron">Orbitron</option>
									<option '.selected( $options[2], 'Oregano', false ).' value="Oregano">Oregano</option>
									<option '.selected( $options[2], 'Orienta', false ).' value="Orienta">Orienta</option>
									<option '.selected( $options[2], 'Original Surfer', false ).' value="Original Surfer">Original Surfer</option>
									<option '.selected( $options[2], 'Oswald', false ).' value="Oswald">Oswald</option>
									<option '.selected( $options[2], 'Over the Rainbow', false ).' value="Over the Rainbow">Over the Rainbow</option>
									<option '.selected( $options[2], 'Overlock', false ).' value="Overlock">Overlock</option>
									<option '.selected( $options[2], 'Overlock SC', false ).' value="Overlock SC">Overlock SC</option>
									<option '.selected( $options[2], 'Ovo', false ).' value="Ovo">Ovo</option>
									<option '.selected( $options[2], 'Oxygen', false ).' value="Oxygen">Oxygen</option>
									<option '.selected( $options[2], 'Oxygen Mono', false ).' value="Oxygen Mono">Oxygen Mono</option>
									<option '.selected( $options[2], 'Pacifico', false ).' value="Pacifico">Pacifico</option>
									<option '.selected( $options[2], 'Paprika', false ).' value="Paprika">Paprika</option>
									<option '.selected( $options[2], 'Parisienne', false ).' value="Parisienne">Parisienne</option>
									<option '.selected( $options[2], 'Passero One', false ).' value="Passero One">Passero One</option>
									<option '.selected( $options[2], 'Passion One', false ).' value="Passion One">Passion One</option>
									<option '.selected( $options[2], 'Pathway Gothic One', false ).' value="Pathway Gothic One">Pathway Gothic One</option>
									<option '.selected( $options[2], 'Patrick Hand', false ).' value="Patrick Hand">Patrick Hand</option>
									<option '.selected( $options[2], 'Patrick Hand SC', false ).' value="Patrick Hand SC">Patrick Hand SC</option>
									<option '.selected( $options[2], 'Patua One', false ).' value="Patua One">Patua One</option>
									<option '.selected( $options[2], 'Paytone One', false ).' value="Paytone One">Paytone One</option>
									<option '.selected( $options[2], 'Peralta', false ).' value="Peralta">Peralta</option>
									<option '.selected( $options[2], 'Permanent Marker', false ).' value="Permanent Marker">Permanent Marker</option>
									<option '.selected( $options[2], 'Petit Formal Script', false ).' value="Petit Formal Script">Petit Formal Script</option>
									<option '.selected( $options[2], 'Petrona', false ).' value="Petrona">Petrona</option>
									<option '.selected( $options[2], 'Philosopher', false ).' value="Philosopher">Philosopher</option>
									<option '.selected( $options[2], 'Piedra', false ).' value="Piedra">Piedra</option>
									<option '.selected( $options[2], 'Pinyon Script', false ).' value="Pinyon Script">Pinyon Script</option>
									<option '.selected( $options[2], 'Pirata One', false ).' value="Pirata One">Pirata One</option>
									<option '.selected( $options[2], 'Plaster', false ).' value="Plaster">Plaster</option>
									<option '.selected( $options[2], 'Play', false ).' value="Play">Play</option>
									<option '.selected( $options[2], 'Playball', false ).' value="Playball">Playball</option>
									<option '.selected( $options[2], 'Playfair Display', false ).' value="Playfair Display">Playfair Display</option>
									<option '.selected( $options[2], 'Playfair Display SC', false ).' value="Playfair Display SC">Playfair Display SC</option>
									<option '.selected( $options[2], 'Podkova', false ).' value="Podkova">Podkova</option>
									<option '.selected( $options[2], 'Poiret One', false ).' value="Poiret One">Poiret One</option>
									<option '.selected( $options[2], 'Poller One', false ).' value="Poller One">Poller One</option>
									<option '.selected( $options[2], 'Poly', false ).' value="Poly">Poly</option>
									<option '.selected( $options[2], 'Pompiere', false ).' value="Pompiere">Pompiere</option>
									<option '.selected( $options[2], 'Pontano Sans', false ).' value="Pontano Sans">Pontano Sans</option>
									<option '.selected( $options[2], 'Port Lligat Sans', false ).' value="Port Lligat Sans">Port Lligat Sans</option>
									<option '.selected( $options[2], 'Port Lligat Slab', false ).' value="Port Lligat Slab">Port Lligat Slab</option>
									<option '.selected( $options[2], 'Prata', false ).' value="Prata">Prata</option>
									<option '.selected( $options[2], 'Preahvihear', false ).' value="Preahvihear">Preahvihear</option>
									<option '.selected( $options[2], 'Press Start 2P', false ).' value="Press Start 2P">Press Start 2P</option>
									<option '.selected( $options[2], 'Princess Sofia', false ).' value="Princess Sofia">Princess Sofia</option>
									<option '.selected( $options[2], 'Prociono', false ).' value="Prociono">Prociono</option>
									<option '.selected( $options[2], 'Prosto One', false ).' value="Prosto One">Prosto One</option>
									<option '.selected( $options[2], 'PT Mono', false ).' value="PT Mono">PT Mono</option>
									<option '.selected( $options[2], 'PT Sans', false ).' value="PT Sans">PT Sans</option>
									<option '.selected( $options[2], 'PT Sans Caption', false ).' value="PT Sans Caption">PT Sans Caption</option>
									<option '.selected( $options[2], 'PT Sans Narrow', false ).' value="PT Sans Narrow">PT Sans Narrow</option>
									<option '.selected( $options[2], 'PT Serif', false ).' value="PT Serif">PT Serif</option>
									<option '.selected( $options[2], 'PT Serif Caption', false ).' value="PT Serif Caption">PT Serif Caption</option>
									<option '.selected( $options[2], 'Puritan', false ).' value="Puritan">Puritan</option>
									<option '.selected( $options[2], 'Purple Purse', false ).' value="Purple Purse">Purple Purse</option>
									<option '.selected( $options[2], 'Quando', false ).' value="Quando">Quando</option>
									<option '.selected( $options[2], 'Quantico', false ).' value="Quantico">Quantico</option>
									<option '.selected( $options[2], 'Quattrocento', false ).' value="Quattrocento">Quattrocento</option>
									<option '.selected( $options[2], 'Quattrocento Sans', false ).' value="Quattrocento Sans">Quattrocento Sans</option>
									<option '.selected( $options[2], 'Questrial', false ).' value="Questrial">Questrial</option>
									<option '.selected( $options[2], 'Quicksand', false ).' value="Quicksand">Quicksand</option>
									<option '.selected( $options[2], 'Quintessential', false ).' value="Quintessential">Quintessential</option>
									<option '.selected( $options[2], 'Qwigley', false ).' value="Qwigley">Qwigley</option>
									<option '.selected( $options[2], 'Racing Sans One', false ).' value="Racing Sans One">Racing Sans One</option>
									<option '.selected( $options[2], 'Radley', false ).' value="Radley">Radley</option>
									<option '.selected( $options[2], 'Raleway', false ).' value="Raleway">Raleway</option>
									<option '.selected( $options[2], 'Raleway Dots', false ).' value="Raleway Dots">Raleway Dots</option>
									<option '.selected( $options[2], 'Rambla', false ).' value="Rambla">Rambla</option>
									<option '.selected( $options[2], 'Rammetto One', false ).' value="Rammetto One">Rammetto One</option>
									<option '.selected( $options[2], 'Ranchers', false ).' value="Ranchers">Ranchers</option>
									<option '.selected( $options[2], 'Rancho', false ).' value="Rancho">Rancho</option>
									<option '.selected( $options[2], 'Rationale', false ).' value="Rationale">Rationale</option>
									<option '.selected( $options[2], 'Redressed', false ).' value="Redressed">Redressed</option>
									<option '.selected( $options[2], 'Reenie Beanie', false ).' value="Reenie Beanie">Reenie Beanie</option>
									<option '.selected( $options[2], 'Revalia', false ).' value="Revalia">Revalia</option>
									<option '.selected( $options[2], 'Ribeye', false ).' value="Ribeye">Ribeye</option>
									<option '.selected( $options[2], 'Ribeye Marrow', false ).' value="Ribeye Marrow">Ribeye Marrow</option>
									<option '.selected( $options[2], 'Righteous', false ).' value="Righteous">Righteous</option>
									<option '.selected( $options[2], 'Risque', false ).' value="Risque">Risque</option>
									<option '.selected( $options[2], 'Roboto', false ).' value="Roboto">Roboto</option>
									<option '.selected( $options[2], 'Roboto Condensed', false ).' value="Roboto Condensed">Roboto Condensed</option>
									<option '.selected( $options[2], 'Roboto Slab', false ).' value="Roboto Slab">Roboto Slab</option>
									<option '.selected( $options[2], 'Rochester', false ).' value="Rochester">Rochester</option>
									<option '.selected( $options[2], 'Rock Salt', false ).' value="Rock Salt">Rock Salt</option>
									<option '.selected( $options[2], 'Rokkitt', false ).' value="Rokkitt">Rokkitt</option>
									<option '.selected( $options[2], 'Romanesco', false ).' value="Romanesco">Romanesco</option>
									<option '.selected( $options[2], 'Ropa Sans', false ).' value="Ropa Sans">Ropa Sans</option>
									<option '.selected( $options[2], 'Rosario', false ).' value="Rosario">Rosario</option>
									<option '.selected( $options[2], 'Rosarivo', false ).' value="Rosarivo">Rosarivo</option>
									<option '.selected( $options[2], 'Rouge Script', false ).' value="Rouge Script">Rouge Script</option>
									<option '.selected( $options[2], 'Ruda', false ).' value="Ruda">Ruda</option>
									<option '.selected( $options[2], 'Rufina', false ).' value="Rufina">Rufina</option>
									<option '.selected( $options[2], 'Ruge Boogie', false ).' value="Ruge Boogie">Ruge Boogie</option>
									<option '.selected( $options[2], 'Ruluko', false ).' value="Ruluko">Ruluko</option>
									<option '.selected( $options[2], 'Rum Raisin', false ).' value="Rum Raisin">Rum Raisin</option>
									<option '.selected( $options[2], 'Ruslan Display', false ).' value="Ruslan Display">Ruslan Display</option>
									<option '.selected( $options[2], 'Russo One', false ).' value="Russo One">Russo One</option>
									<option '.selected( $options[2], 'Ruthie', false ).' value="Ruthie">Ruthie</option>
									<option '.selected( $options[2], 'Rye', false ).' value="Rye">Rye</option>
									<option '.selected( $options[2], 'Sacramento', false ).' value="Sacramento">Sacramento</option>
									<option '.selected( $options[2], 'Sail', false ).' value="Sail">Sail</option>
									<option '.selected( $options[2], 'Salsa', false ).' value="Salsa">Salsa</option>
									<option '.selected( $options[2], 'Sanchez', false ).' value="Sanchez">Sanchez</option>
									<option '.selected( $options[2], 'Sancreek', false ).' value="Sancreek">Sancreek</option>
									<option '.selected( $options[2], 'Sansita One', false ).' value="Sansita One">Sansita One</option>
									<option '.selected( $options[2], 'Sarina', false ).' value="Sarina">Sarina</option>
									<option '.selected( $options[2], 'Satisfy', false ).' value="Satisfy">Satisfy</option>
									<option '.selected( $options[2], 'Scada', false ).' value="Scada">Scada</option>
									<option '.selected( $options[2], 'Schoolbell', false ).' value="Schoolbell">Schoolbell</option>
									<option '.selected( $options[2], 'Seaweed Script', false ).' value="Seaweed Script">Seaweed Script</option>
									<option '.selected( $options[2], 'Sevillana', false ).' value="Sevillana">Sevillana</option>
									<option '.selected( $options[2], 'Seymour One', false ).' value="Seymour One">Seymour One</option>
									<option '.selected( $options[2], 'Shadows Into Light', false ).' value="Shadows Into Light">Shadows Into Light</option>
									<option '.selected( $options[2], 'Shadows Into Light Two', false ).' value="Shadows Into Light Two">Shadows Into Light Two</option>
									<option '.selected( $options[2], 'Shanti', false ).' value="Shanti">Shanti</option>
									<option '.selected( $options[2], 'Share', false ).' value="Share">Share</option>
									<option '.selected( $options[2], 'Share Tech', false ).' value="Share Tech">Share Tech</option>
									<option '.selected( $options[2], 'Share Tech Mono', false ).' value="Share Tech Mono">Share Tech Mono</option>
									<option '.selected( $options[2], 'Shojumaru', false ).' value="Shojumaru">Shojumaru</option>
									<option '.selected( $options[2], 'Short Stack', false ).' value="Short Stack">Short Stack</option>
									<option '.selected( $options[2], 'Siemreap', false ).' value="Siemreap">Siemreap</option>
									<option '.selected( $options[2], 'Sigmar One', false ).' value="Sigmar One">Sigmar One</option>
									<option '.selected( $options[2], 'Signika', false ).' value="Signika">Signika</option>
									<option '.selected( $options[2], 'Signika Negative', false ).' value="Signika Negative">Signika Negative</option>
									<option '.selected( $options[2], 'Simonetta', false ).' value="Simonetta">Simonetta</option>
									<option '.selected( $options[2], 'Sintony', false ).' value="Sintony">Sintony</option>
									<option '.selected( $options[2], 'Sirin Stencil', false ).' value="Sirin Stencil">Sirin Stencil</option>
									<option '.selected( $options[2], 'Six Caps', false ).' value="Six Caps">Six Caps</option>
									<option '.selected( $options[2], 'Skranji', false ).' value="Skranji">Skranji</option>
									<option '.selected( $options[2], 'Slackey', false ).' value="Slackey">Slackey</option>
									<option '.selected( $options[2], 'Smokum', false ).' value="Smokum">Smokum</option>
									<option '.selected( $options[2], 'Smythe', false ).' value="Smythe">Smythe</option>
									<option '.selected( $options[2], 'Sniglet:800', false ).' value="Sniglet:800">Sniglet</option>
									<option '.selected( $options[2], 'Snippet', false ).' value="Snippet">Snippet</option>
									<option '.selected( $options[2], 'Snowburst One', false ).' value="Snowburst One">Snowburst One</option>
									<option '.selected( $options[2], 'Sofadi One', false ).' value="Sofadi One">Sofadi One</option>
									<option '.selected( $options[2], 'Sofia', false ).' value="Sofia">Sofia</option>
									<option '.selected( $options[2], 'Sonsie One', false ).' value="Sonsie One">Sonsie One</option>
									<option '.selected( $options[2], 'Sorts Mill Goudy', false ).' value="Sorts Mill Goudy">Sorts Mill Goudy</option>
									<option '.selected( $options[2], 'Source Code Pro', false ).' value="Source Code Pro">Source Code Pro</option>
									<option '.selected( $options[2], 'Source Sans Pro', false ).' value="Source Sans Pro">Source Sans Pro</option>
									<option '.selected( $options[2], 'Special Elite', false ).' value="Special Elite">Special Elite</option>
									<option '.selected( $options[2], 'Spicy Rice', false ).' value="Spicy Rice">Spicy Rice</option>
									<option '.selected( $options[2], 'Spinnaker', false ).' value="Spinnaker">Spinnaker</option>
									<option '.selected( $options[2], 'Spirax', false ).' value="Spirax">Spirax</option>
									<option '.selected( $options[2], 'Squada One', false ).' value="Squada One">Squada One</option>
									<option '.selected( $options[2], 'Stalemate', false ).' value="Stalemate">Stalemate</option>
									<option '.selected( $options[2], 'Stalinist One', false ).' value="Stalinist One">Stalinist One</option>
									<option '.selected( $options[2], 'Stardos Stencil', false ).' value="Stardos Stencil">Stardos Stencil</option>
									<option '.selected( $options[2], 'Stint Ultra Condensed', false ).' value="Stint Ultra Condensed">Stint Ultra Condensed</option>
									<option '.selected( $options[2], 'Stint Ultra Expanded', false ).' value="Stint Ultra Expanded">Stint Ultra Expanded</option>
									<option '.selected( $options[2], 'Stoke', false ).' value="Stoke">Stoke</option>
									<option '.selected( $options[2], 'Strait', false ).' value="Strait">Strait</option>
									<option '.selected( $options[2], 'Sue Ellen Francisco', false ).' value="Sue Ellen Francisco">Sue Ellen Francisco</option>
									<option '.selected( $options[2], 'Sunshiney', false ).' value="Sunshiney">Sunshiney</option>
									<option '.selected( $options[2], 'Supermercado One', false ).' value="Supermercado One">Supermercado One</option>
									<option '.selected( $options[2], 'Suwannaphum', false ).' value="Suwannaphum">Suwannaphum</option>
									<option '.selected( $options[2], 'Swanky and Moo Moo', false ).' value="Swanky and Moo Moo">Swanky and Moo Moo</option>
									<option '.selected( $options[2], 'Syncopate', false ).' value="Syncopate">Syncopate</option>
									<option '.selected( $options[2], 'Tangerine', false ).' value="Tangerine">Tangerine</option>
									<option '.selected( $options[2], 'Taprom', false ).' value="Taprom">Taprom</option>
									<option '.selected( $options[2], 'Tauri', false ).' value="Tauri">Tauri</option>
									<option '.selected( $options[2], 'Telex', false ).' value="Telex">Telex</option>
									<option '.selected( $options[2], 'Tenor Sans', false ).' value="Tenor Sans">Tenor Sans</option>
									<option '.selected( $options[2], 'Text Me One', false ).' value="Text Me One">Text Me One</option>
									<option '.selected( $options[2], 'The Girl Next Door', false ).' value="The Girl Next Door">The Girl Next Door</option>
									<option '.selected( $options[2], 'Tienne', false ).' value="Tienne">Tienne</option>
									<option '.selected( $options[2], 'Tinos', false ).' value="Tinos">Tinos</option>
									<option '.selected( $options[2], 'Titan One', false ).' value="Titan One">Titan One</option>
									<option '.selected( $options[2], 'Titillium Web', false ).' value="Titillium Web">Titillium Web</option>
									<option '.selected( $options[2], 'Trade Winds', false ).' value="Trade Winds">Trade Winds</option>
									<option '.selected( $options[2], 'Trocchi', false ).' value="Trocchi">Trocchi</option>
									<option '.selected( $options[2], 'Trochut', false ).' value="Trochut">Trochut</option>
									<option '.selected( $options[2], 'Trykker', false ).' value="Trykker">Trykker</option>
									<option '.selected( $options[2], 'Tulpen One', false ).' value="Tulpen One">Tulpen One</option>
									<option '.selected( $options[2], 'Ubuntu', false ).' value="Ubuntu">Ubuntu</option>
									<option '.selected( $options[2], 'Ubuntu Condensed', false ).' value="Ubuntu Condensed">Ubuntu Condensed</option>
									<option '.selected( $options[2], 'Ubuntu Mono', false ).' value="Ubuntu Mono">Ubuntu Mono</option>
									<option '.selected( $options[2], 'Ultra', false ).' value="Ultra">Ultra</option>
									<option '.selected( $options[2], 'Uncial Antiqua', false ).' value="Uncial Antiqua">Uncial Antiqua</option>
									<option '.selected( $options[2], 'Underdog', false ).' value="Underdog">Underdog</option>
									<option '.selected( $options[2], 'Unica One', false ).' value="Unica One">Unica One</option>
									<option '.selected( $options[2], 'UnifrakturCook:700', false ).' value="UnifrakturCook:700">UnifrakturCook</option>
									<option '.selected( $options[2], 'UnifrakturMaguntia', false ).' value="UnifrakturMaguntia">UnifrakturMaguntia</option>
									<option '.selected( $options[2], 'Unkempt', false ).' value="Unkempt">Unkempt</option>
									<option '.selected( $options[2], 'Unlock', false ).' value="Unlock">Unlock</option>
									<option '.selected( $options[2], 'Unna', false ).' value="Unna">Unna</option>
									<option '.selected( $options[2], 'Vampiro One', false ).' value="Vampiro One">Vampiro One</option>
									<option '.selected( $options[2], 'Varela', false ).' value="Varela">Varela</option>
									<option '.selected( $options[2], 'Varela Round', false ).' value="Varela Round">Varela Round</option>
									<option '.selected( $options[2], 'Vast Shadow', false ).' value="Vast Shadow">Vast Shadow</option>
									<option '.selected( $options[2], 'Vibur', false ).' value="Vibur">Vibur</option>
									<option '.selected( $options[2], 'Vidaloka', false ).' value="Vidaloka">Vidaloka</option>
									<option '.selected( $options[2], 'Viga', false ).' value="Viga">Viga</option>
									<option '.selected( $options[2], 'Voces', false ).' value="Voces">Voces</option>
									<option '.selected( $options[2], 'Volkhov', false ).' value="Volkhov">Volkhov</option>
									<option '.selected( $options[2], 'Vollkorn', false ).' value="Vollkorn">Vollkorn</option>
									<option '.selected( $options[2], 'Voltaire', false ).' value="Voltaire">Voltaire</option>
									<option '.selected( $options[2], 'VT323', false ).' value="VT323">VT323</option>
									<option '.selected( $options[2], 'Waiting for the Sunrise', false ).' value="Waiting for the Sunrise">Waiting for the Sunrise</option>
									<option '.selected( $options[2], 'Wallpoet', false ).' value="Wallpoet">Wallpoet</option>
									<option '.selected( $options[2], 'Walter Turncoat', false ).' value="Walter Turncoat">Walter Turncoat</option>
									<option '.selected( $options[2], 'Warnes', false ).' value="Warnes">Warnes</option>
									<option '.selected( $options[2], 'Wellfleet', false ).' value="Wellfleet">Wellfleet</option>
									<option '.selected( $options[2], 'Wendy One', false ).' value="Wendy One">Wendy One</option>
									<option '.selected( $options[2], 'Wire One', false ).' value="Wire One">Wire One</option>
									<option '.selected( $options[2], 'Yanone Kaffeesatz', false ).' value="Yanone Kaffeesatz">Yanone Kaffeesatz</option>
									<option '.selected( $options[2], 'Yellowtail', false ).' value="Yellowtail">Yellowtail</option>
									<option '.selected( $options[2], 'Yeseva One', false ).' value="Yeseva One">Yeseva One</option>
									<option '.selected( $options[2], 'Yesteryear', false ).' value="Yesteryear">Yesteryear</option>
									<option '.selected( $options[2], 'Zeyada', false ).' value="Zeyada">Zeyada</option>
								</select>
					</div>
					<div style="clear:both;"></div>
					<div class="text">' . __( 'Number of Answers in One Row:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="grid_items" class="grid_items">
						<option ' . selected( $options[ 142 ], "", false ) . ' value="">Auto</option>
						<option ' . selected( $options[ 142 ], "1", false ) . ' value="1">1</option>
						<option ' . selected( $options[ 142 ], "2", false ) . ' value="2">2</option>
						<option ' . selected( $options[ 142 ], "3", false ) . ' value="3">3</option>
						<option ' . selected( $options[ 142 ], "4", false ) . ' value="4">4</option>
						<option ' . selected( $options[ 142 ], "5", false ) . ' value="5">5</option>
						<option ' . selected( $options[ 142 ], "6", false ) . ' value="6">6</option>
						<option ' . selected( $options[ 142 ], "7", false ) . ' value="7">7</option>
						<option ' . selected( $options[ 142 ], "8", false ) . ' value="8">8</option>
						<option ' . selected( $options[ 142 ], "9", false ) . ' value="9">9</option>
						<option ' . selected( $options[ 142 ], "10", false ) . ' value="10">10</option>
					</select>
					</div> 
					<div class="text">' . __( 'Next / Back Button Position:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="next_button_style" class="next_button_style">
						<option ' . selected( $options[ 151 ], "", false ) . ' value="">' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 151 ], "1", false ) . ' value="1">' . __( 'At the Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>

					</select>
					</div> 
					<div class="text">' . __( 'Rating Question Style:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="rating_question_style" class="rating_question_style">
						<option ' . selected( $options[ 153 ], "", false ) . ' value="">' . __( 'Default - Stars', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 153 ], "number1", false ) . ' value="number1">' . __( 'Numbers 1', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 153 ], "number2", false ) . ' value="number2">' . __( 'Numbers 2', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 153 ], "trophy", false ) . ' value="trophy">' . __( 'Trophy', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 153 ], "smiley", false ) . ' value="smiley">' . __( 'Smiley', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 153 ], "soccer", false ) . ' value="soccer">' . __( 'Soccer', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option ' . selected( $options[ 153 ], "coffee", false ) . ' value="coffee">' . __( 'Coffee', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>

					</select>
					</div> 
					<div class="modal_survey_sliders half"><input value="' . __( 'Shadow Horizontal:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[ 128 ].'px" type="text" class="modal_survey_shadowh_value" /><div class="modal_survey_shadowh"></div></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Shadow Vertical:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[ 129 ].'px" type="text" class="modal_survey_shadowv_value" /><div class="modal_survey_shadowv"></div></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Shadow Blur:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[ 130 ].'px" type="text" class="modal_survey_shadowb_value" /><div class="modal_survey_shadowb"></div></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Shadow Spread:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[ 131 ].'px" type="text" class="modal_survey_shadows_value" /><div class="modal_survey_shadows"></div></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Border Width:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[6].'px" type="text" class="modal_survey_border_width_value" /><div class="modal_survey_border_width"></div></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Border Radius:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[7].'px" type="text" class="modal_survey_border_radius_value" /><div class="modal_survey_border_radius"></div></div>
					<div style="clear:both;"></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Font Size:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[8].'px" type="text" class="modal_survey_font_size_value" /><div class="modal_survey_font_size"></div></div>
					<div class="modal_survey_sliders half"><input value="' . __( 'Padding:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[9].'px" type="text" class="modal_survey_padding_value" /><div class="modal_survey_padding"></div></div>
					<div class="modal_survey_sliders full"><input value="' . __( 'Line Height:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[10].'px" type="text" class="modal_survey_line_height_value" /><div class="modal_survey_line_height"></div></div>
					<div style="clear:both;"></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Display Progress of the Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="progressbar" ' . $opt_20 . ' class="inputtext progressbar ms-checkbox" value="' . $options[ 20 ] . '" /> ' . __( 'Show Progress Bar', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Display Answers as List Layout', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="listlayout" ' . $opt_22 . ' class="inputtext listlayout ms-checkbox" value="' . $options[ 22 ] . '" /> ' . __( 'List Layout', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Always Show the Next Button', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="alwaysnext" ' . $opt_152 . ' class="inputtext alwaysnext ms-checkbox" value="' . $options[ 152 ] . '" /> ' . __( 'Always Show Next Button', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					<div class="modal_survey_checkbox"><label class="text modal_survey_tooltip" title="' . __( 'Allows to Go Back ONE STEP only in the Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="enableback" ' . $opt_154 . ' class="inputtext enableback ms-checkbox" value="' . $options[ 154 ] . '" /> ' . __( 'Enable Back Button', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
				</div></div>
			</div>
			<div class="configuration_accordion"><h4>' . __( 'Miscellaneous', MODAL_SURVEY_TEXT_DOMAIN ) . '</h4><div>
				<div>			
					<div class="text">' . __( 'Preloader:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="survey_preloader" class="survey_preloader">
						<option '.selected( $options[133], "preloader", false ).' value="preloader">' . __( 'Preloader 1', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader2", false ).' value="preloader2">' . __( 'Preloader 2', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader3", false ).' value="preloader3">' . __( 'Preloader 3', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader4", false ).' value="preloader4">' . __( 'Preloader 4', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader5", false ).' value="preloader5">' . __( 'Preloader 5', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader6", false ).' value="preloader6">' . __( 'Preloader 6', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader7", false ).' value="preloader7">' . __( 'Preloader 7', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader8", false ).' value="preloader8">' . __( 'Preloader 8', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader9", false ).' value="preloader9">' . __( 'Preloader 9', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[133], "preloader10", false ).' value="preloader10">' . __( 'Preloader 10', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div>
					<div class="text">' . __( 'Hover Effect:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="survey_hover" class="survey_hover">
						<option '.selected( $options[134], "", false ).' value="">' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle1", false ).' value="ahoverstyle1">' . __( 'Style 1', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle2", false ).' value="ahoverstyle2">' . __( 'Style 2', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle3", false ).' value="ahoverstyle3">' . __( 'Style 3', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle4", false ).' value="ahoverstyle4">' . __( 'Style 4', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle5", false ).' value="ahoverstyle5">' . __( 'Style 5', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle6", false ).' value="ahoverstyle6">' . __( 'Style 6', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle7", false ).' value="ahoverstyle7">' . __( 'Style 7', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle8", false ).' value="ahoverstyle8">' . __( 'Style 8', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle9", false ).' value="ahoverstyle9">' . __( 'Style 9 Traditional Checkbox', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle10", false ).' value="ahoverstyle10">' . __( 'Style 10 Traditional Checkbox / Radio Button', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle11", false ).' value="ahoverstyle11">' . __( 'Style 11 Traditional Checkbox Style 2', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[134], "ahoverstyle12", false ).' value="ahoverstyle12">' . __( 'Style 12 Traditional Checkbox Style 3', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div>
					<div class="text">' . __( 'Close Icon Style:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="survey_closeicon" class="survey_closeicon">
						<option '.selected( $options[141], "remove", false ).' value="remove">' . __( 'Style 1', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove_inverse", false ).' value="remove_inverse">' . __( 'Style 2', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove2", false ).' value="remove2">' . __( 'Style 3', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove2_inverse", false ).' value="remove2_inverse">' . __( 'Style 4', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove3", false ).' value="remove3">' . __( 'Style 5', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove3_inverse", false ).' value="remove3_inverse">' . __( 'Style 6', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove4", false ).' value="remove4">' . __( 'Style 7', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove4_inverse", false ).' value="remove4_inverse">' . __( 'Style 8', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove5", false ).' value="remove5">' . __( 'Style 9', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[141], "remove5_inverse", false ).' value="remove5_inverse">' . __( 'Style 10', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div>
					<div class="text">' . __( 'Close Icon Size:', MODAL_SURVEY_TEXT_DOMAIN ) . '
					<select name="survey_closeiconsize" class="survey_closeiconsize">
						<option '.selected( $options[ 145 ], "small", false ).' value="small">' . __( 'Small', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[ 145 ], "medium", false ).' value="medium">' . __( 'Medium', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
						<option '.selected( $options[ 145 ], "large", false ).' value="large">' . __( 'Large', MODAL_SURVEY_TEXT_DOMAIN ) . '</option>
					</select>
					</div>
					<div class="text">' . __( 'Start time:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="text" name="start_time" class="datepicker inputtext start_time modal_survey_tooltip" title="' . __( 'Leave it blank to activate the survey immediately', MODAL_SURVEY_TEXT_DOMAIN ) . '" value="'.str_replace("0000-00-00 00:00:00","",$thisstart_time).'" /></div>
					<div class="text">' . __( 'Expiry time:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="text" name="expiry_time" class="datepicker inputtext expiry_time modal_survey_tooltip" title="' . __( 'Never expire the survey when you leave this blank', MODAL_SURVEY_TEXT_DOMAIN ) . '" value="'.str_replace("0000-00-00 00:00:00","",$thisexpiry_time).'" /></div>
					<div style="clear:both;"></div>
					<div class="modal_survey_sliders full"><input value="' . __( 'Cookie Expiration Time:', MODAL_SURVEY_TEXT_DOMAIN ) . ' '.$options[143].' hours" type="text" class="modal_survey_cookie_expiration_value" /><div class="modal_survey_cookie_expiration"></div></div>
					<div style="clear:both;"></div>
					<hr>
					<div class="text thankyou modal_survey_tooltip" title="' . __( 'Redirect the browser to the specified URL after the user complete the survey', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Redirect URL:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input name="redirecturl" type="text" class="inputtext redirecturl thankyou" value="'.str_replace('"','\'',$options[19]).'" placeholder="http://www.yourwebsiteurl.com/subpage" /></div>
					<div class="text thankyou modal_survey_tooltip" title="' . __( 'Display message at the end of the survey', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Thank you message:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <textarea name="thankyou" class="inputtext thankyou">'.str_replace('"','\'',$options[12]).'</textarea></div>
					<div class="text thankyou modal_survey_tooltip" title="' . __( 'Send the individual results of the completed surveys to the specified email address. Keep empty to disable.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Send Results to:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input name="notificationemail" type="text" class="inputtext notificationemail thankyou" value="'.str_replace('"','\'',$options[144]).'" placeholder="email@address.com" /></div>
					<div class="play_button dnone"><img class="modal_survey_tooltip" title="' . __( 'Play Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="'.plugins_url( '/assets/img/play.png' , __FILE__ ).'"></div>
					<div style="clear:both;"></div>
				</div></div>
			</div>
			<div class="conditions additional-options-accordion"><h4>' . __( 'Conditions', MODAL_SURVEY_TEXT_DOMAIN ) . '<span>' . __( 'Set Actions based on the Votes', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></h4><div>
			<div class="new_condition_line">
				If 
				<select class="conds">
					<option value="time">' . __( 'Time is Up', MODAL_SURVEY_TEXT_DOMAIN ) . '</options>
					<option value="score">' . __( 'Final Score', MODAL_SURVEY_TEXT_DOMAIN ) . '</options>
					<option value="correct">' . __( 'Correct Answers', MODAL_SURVEY_TEXT_DOMAIN ) . '</options>');
					$questions = $this->wpdb->get_results("SELECT * FROM ".$this->wpdb->base_prefix."modal_survey_questions WHERE `survey_id`='".$sv->id."' ORDER BY id ASC");
					$qcategories = array();
					foreach( $questions as $key=>$qv ) {
						print('<option value="questionscore_' . ( $key + 1 ) . '">' . __( 'Question', MODAL_SURVEY_TEXT_DOMAIN ) . ' ' . ( $key + 1 ) . ' ' . __( 'Score', MODAL_SURVEY_TEXT_DOMAIN ) . '</options>');
						preg_match( '/\[([^\]]*)\]/', $qv->question, $qcats );
						if ( ! empty( $qcats ) ) {
							$qncat = strtr( $qcats[ 0 ], array( "[" => "", "]" => "" ) );
							if ( ! in_array( $qncat, $qcategories ) ) {
								$qcategories[] = $qncat;
							}
						}
					$answers = $this->wpdb->get_results("SELECT * FROM " . $this->wpdb->base_prefix . "modal_survey_answers WHERE `survey_id`='" . $sv->id . "' AND `question_id`='" . $qv->id . "' ORDER BY autoid ASC");
						foreach( $answers as $key2=>$av ) {
						preg_match( '/\[([^\]]*)\]/', $av->answer, $qcats2 );
							if ( ! empty( $qcats2 ) ) {
								$qncat2 = strtr( $qcats2[ 0 ], array( "[" => "", "]" => "" ) );
								if ( strpos( $qncat2, ',' ) ) {
									$cut_qncat2 = explode( ",", $qncat2 );
									foreach( $cut_qncat2 as $cq2 ) {
										if ( ! in_array( trim( $cq2 ), $qcategories ) ) {
											$qcategories[] = trim( $cq2 );
										}									
									}
								}
								else {
									if ( ! in_array( $qncat2, $qcategories ) && ! is_numeric( $qncat2 ) ) {
										$qcategories[] = $qncat2;
									}
								}
							}
						}
					}
					if ( ! empty( $qcategories ) ) {
						foreach( $qcategories as $key=>$qc ) {
							print('<option value="questioncatscore_' . $qc . '">' . __( 'Category', MODAL_SURVEY_TEXT_DOMAIN ) . ' ' . $qc . ' ' . __( 'Score', MODAL_SURVEY_TEXT_DOMAIN ) . '</options>');							
						}
					}
				print('</select>
				<select class="relation">
					<option value="higher"> ' . __( 'higher than', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="equal"> ' . __( 'equal with', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="lower"> ' . __( 'lower than', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
				</select>
				<input type="text" name="condvalue" class="condvalue" value="" />
				then
				<select class="action">
					<option value="redirect"> ' . __( 'redirect to', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="display"> ' . __( 'display message at the end', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="soctitle"> ' . __( 'set social sharing title to', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="socdesc"> ' . __( 'set social sharing description to', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="socimg"> ' . __( 'set social sharing image URL to', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="displayirchart"> ' . __( 'display individual rating chart at the end', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="displayischart"> ' . __( 'display individual score chart at the end', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
					<option value="displayicchart"> ' . __( 'display individual correct answers chart at the end', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
				</select>			
				<textarea name="actionvalue" class="actionvalue" placeholder="' . __( 'Place the HTML code, display message or redirection URL here', MODAL_SURVEY_TEXT_DOMAIN ) . '"></textarea><input type="hidden" name="cond_ed_field" id="cond_ed_field" value="" />
				<a href="" class="add_condition button button-primary">' . __( 'Add Condition', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>
			</div>
			<hr>
			<div class="added_conditions">');
			if ( $options[ 21 ] != "" ) {
				$options[ 21 ] = ( array ) $options[ 21 ];
				foreach( $options[ 21 ] as $condkey => $conds ) {
					$newline_str = __( 'If', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					$conds_c = array();
					$conds_n = htmlspecialchars( $conds );
					$conds_c = json_decode( html_entity_decode( $conds_n ) );
					if ( $conds_c[ 0 ] == "time" ) $newline_str .= __( 'Time is Up', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					if ( $conds_c[ 0 ] == "score" ) $newline_str .= __( 'Final Score', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					if ( $conds_c[ 0 ] == "correct" ) $newline_str .= __( 'Correct Answers', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					if ( strpos( $conds_c[ 0 ], 'questionscore_' ) !== false ) {
						$conds_exp = explode( "_", $conds_c[ 0 ] );
						$newline_str .= __( 'Question', MODAL_SURVEY_TEXT_DOMAIN ) . " " . $conds_exp[ 1 ] . " " . __( 'Score', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					}
					if ( strpos( $conds_c[ 0 ], 'questioncatscore_' ) !== false ) {
						$conds_exp = explode( "_", $conds_c[ 0 ] );
						$newline_str .= __( 'Category', MODAL_SURVEY_TEXT_DOMAIN ) . " " . $conds_exp[ 1 ] . " " . __( 'Score', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					}
					if ( $conds_c[ 0 ] != "time" ) {
						if ( $conds_c[ 1 ] == "higher" ) $newline_str .= __( 'higher than', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
						if ( $conds_c[ 1 ] == "equal" ) $newline_str .= __( 'equal with', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
						if ( $conds_c[ 1 ] == "lower" ) $newline_str .= __( 'lower than', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
						$newline_str .= $conds_c[ 2 ];
					}
					$newline_str .= " " . __( 'then', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					if ( $conds_c[ 3 ] == "redirect" ) {
						$newline_str .= __( 'redirect to', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					}
					if ( $conds_c[ 3 ] == "display" ) {
						$newline_str .= __( 'display message at the end', MODAL_SURVEY_TEXT_DOMAIN ) . ": ";
					}
					if ( $conds_c[ 3 ] == "soctitle" ) {
						$newline_str .= __( 'set social sharing title to', MODAL_SURVEY_TEXT_DOMAIN ) . ": ";
					}
					if ( $conds_c[ 3 ] == "socdesc" ) {
						$newline_str .= __( 'set social sharing description to', MODAL_SURVEY_TEXT_DOMAIN ) . ": ";
					}
					if ( $conds_c[ 3 ] == "socimg" ) {
						$newline_str .= __( 'set social sharing image URL to', MODAL_SURVEY_TEXT_DOMAIN ) . ": ";
					}
					if ( $conds_c[ 3 ] == "displayirchart" ) {
						$newline_str .= __( 'display individual rating chart at the end', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					}
					if ( $conds_c[ 3 ] == "displayischart" ) {
						$newline_str .= __( 'display individual score chart at the end', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					}
					if ( $conds_c[ 3 ] == "displayicchart" ) {
						$newline_str .= __( 'display individual correct answers chart at the end', MODAL_SURVEY_TEXT_DOMAIN ) . " ";
					}
					$newline_str .= str_replace( "|", "'", $conds_c[ 4 ] );
					print( '<div class="one_condition_line"><a href="#" class="edit_condition" data-cond="' . $condkey . '"><img class="modal_survey_tooltip" title="' . __( 'Edit Condition', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="' . plugins_url( '/assets/img/cond-edit.png' , __FILE__ ) . '"></a><a href="#" class="remove_condition" data-cond="' . $condkey . '"><img class="modal_survey_tooltip" title="' . __( 'Remove Condition', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="' . plugins_url( '/assets/img/delete.png' , __FILE__ ) . '"></a><input type="hidden" class="packed_cond" value="' . $conds_n . '">' . $newline_str . '</div>' );
				}
			}
$ms_content = $options[ 147 ];
$ms_editor_id = 'ms_autoresponse';
$ms_settings =   array(
    'wpautop' => true, // use wpautop?
    'media_buttons' => true, // show insert/upload button(s)
    'textarea_name' => $ms_editor_id, // set the textarea name to something different, square brackets [] can be used here
    'textarea_rows' => 20, // rows="..."
    'tabindex' => '', //The tabindex value used for the form field
    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
    'editor_class' => '', // add extra class(es) to the editor textarea
	'editor_height' => '', //The height to set the editor in pixels. If set, will be used instead of textarea_rows.
    'teeny' => false, // output the minimal editor config used in Press This
    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
    'quicktags' => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
	'drag_drop_upload' => true //Enable Drag & Drop Upload Support
);
			print('</div>
			</div></div>
			<div class="endcontent_accordion additional-options-accordion"><h4>' . __( 'End Content', MODAL_SURVEY_TEXT_DOMAIN ) . '<span>' . __( 'Display Chart at the end of the Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></h4><div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Chart at the completion of survey', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="endchart_status" class="inputtext endchart_status admincheckbox" '.$endchart_status.' value="'.$endchart_status_value.'" /> ' . __( 'Enable Chart', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text tinyfield">
							' . __( 'Chart Style', MODAL_SURVEY_TEXT_DOMAIN ) . ': <select class="endchart_style">
							<option ' . selected( $options[ 137 ], "progressbar", false ) . ' value="progressbar"> ' . __( 'Progress Bar', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "linebar", false ) . 'value="linebar"> ' . __( 'Line Bar', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "piechart", false ) . 'value="piechart"> ' . __( 'Pie Chart', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "barchart", false ) . 'value="barchart"> ' . __( 'Bar Chart', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "doughnutchart", false ) . 'value="doughnutchart"> ' . __( 'Doughnut Chart', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "linechart", false ) . 'value="linechart"> ' . __( 'Line Chart', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "polarchart", false ) . 'value="polarchart"> ' . __( 'Polar Chart', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 137 ], "radarchart", false ) . 'value="radarchart"> ' . __( 'Radar Chart', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
						</select>
						</div>
						<div class="text tinyfield">
							' . __( 'Chart Type', MODAL_SURVEY_TEXT_DOMAIN ) . ': <select class="endchart_type">
							<option ' . selected( $options[ 138 ], "full", false ) . 'value="full"> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 138 ], "score", false ) . 'value="score"> ' . __( 'Score', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 138 ], "average-score", false ) . 'value="average-score"> ' . __( 'Average Score', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 138 ], "rating", false ) . 'value="rating"> ' . __( 'Rating', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
						</select>
						</div>
						<div class="text tinyfield">
							' . __( 'Data Type', MODAL_SURVEY_TEXT_DOMAIN ) . ': <select class="endchart_datatype">
							<option ' . selected( $options[ 139 ], "cumulated", false ) . 'value="cumulated"> ' . __( 'Cumulated', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
							<option ' . selected( $options[ 139 ], "individual", false ) . 'value="individual"> ' . __( 'Individual', MODAL_SURVEY_TEXT_DOMAIN ) . ' </options>
						</select>
						</div>
					</div>
					<div class="text thankyou">' . __( 'Advanced Chart', MODAL_SURVEY_TEXT_DOMAIN ) . '<input type="text" name="endchart_advancedchart" class="inputtext endchart_advancedchart" placeholder="Insert results shortcode here" value="' . htmlspecialchars( $options[ 140 ] ) . '" /></div>
				</div>
			</div>
			</div>
			<div class="participants_form additional-options-accordion"><h4>' . __( 'Participants Form', MODAL_SURVEY_TEXT_DOMAIN ) . '<span>' . __( 'Display Form at the Completion of Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></h4><div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Form at the end of the Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="msform_status" class="inputtext msform_status admincheckbox" '.$msform_status.' value="'.$msform_status_value.'" /> ' . __( 'Enable Form', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield"><label class="text modal_survey_tooltip" title="' . __( 'Enable Name Field in the Form', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 250px;vertical-align:baseline;"><input type="checkbox" name="msform_name_field" class="inputtext msform_name_field admincheckbox" '.$msform_name_field.' value="'.$msform_name_field_value.'" /> ' . __( 'Request Name', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
						<div class="text shortfield"><label class="text modal_survey_tooltip" title="' . __( 'Enable Email Field in the Form', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 250px;vertical-align:baseline;"><input type="checkbox" name="msform_email_field" class="inputtext msform_email_field admincheckbox" '.$msform_email_field.' value="'.$msform_email_field_value.'" /> ' . __( 'Request Email Address', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
						<div class="text shortfield"><label class="text modal_survey_tooltip" title="' . __( 'Validate Email Address', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 250px;vertical-align:baseline;"><input type="checkbox" name="msform_email_validate_field" class="inputtext msform_email_validate_field admincheckbox" '.$msform_email_validate_field.' value="'.$msform_email_validate_field_value.'" /> ' . __( 'Validate Email Address', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					</div>
				</div>
			<div style="clear: both;"></div><br><hr><br>
			<div class="custom_field_section">
				<div class="acfield add_custom_fields button button-secondary button-large">' . __( 'Add Text Field', MODAL_SURVEY_TEXT_DOMAIN ) . '</div><div class="acfield add_custom_fields_textarea button button-secondary button-large">' . __( 'Add Textarea', MODAL_SURVEY_TEXT_DOMAIN ) . '</div><div class="acfield add_custom_fields_radio button button-secondary button-large">' . __( 'Add Radio Buttons field', MODAL_SURVEY_TEXT_DOMAIN ) . '</div><div class="acfield add_custom_fields_checkbox button button-secondary button-large">' . __( 'Add Checkbox', MODAL_SURVEY_TEXT_DOMAIN ) . '</div><div class="acfield add_custom_fields_select button button-secondary button-large">' . __( 'Add Select Box', MODAL_SURVEY_TEXT_DOMAIN ) . '</div><div class="acfield add_custom_fields_hidden button button-secondary button-large">' . __( 'Add Hidden Field', MODAL_SURVEY_TEXT_DOMAIN ) . '</div><div class="acfield add_custom_fields_html button button-secondary button-large">' . __( 'Add HTML Block', MODAL_SURVEY_TEXT_DOMAIN ) . '</div>' );
			if ( ! empty( $options[ 159 ] ) ) {
				foreach( $options[ 159 ] as $cf ) {
				if ( $cf->required == 'true' ) {
					$req = '1';
					$req_value = 'checked';
				}
				else {
					$req = '0';
					$req_value = '';
				}
				if ( ! isset( $cf->type ) ) {
					$cf->type = "text";
				}
				$onkeyup = 'onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g,\'\');"';
					if ( $cf->type == "text" ) {
						print( "<div class='one-custom-field'><input " . $onkeyup . " type='text' data-type='text' class='cfid modal_survey_tooltip' title='" . __( 'ID of input field, eg.: FNAME', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->id . "' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip' value='" . $cf->name . "' title='" . __( 'Name of custom field, eg.: First Name', MODAL_SURVEY_TEXT_DOMAIN ) . "' placeholder='" . __( 'Name', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='text' class='cfwarning modal_survey_tooltip' title='" . __( 'Warning text for the field if it is required, eg.: Firstname field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->warning . "' placeholder='" . __( 'Warning', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='text' class='cfminlength modal_survey_tooltip' title='" . __( 'Minimum character length for required field', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->minlength . "' placeholder='0'><input type='checkbox' class='cfrequired modal_survey_tooltip' " . $req_value . " title='" . __( 'Check this if the field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='".$req."'><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove Text Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png' , __FILE__ ) . "'></div>" );
					}
					if ( $cf->type == "textarea" ) {
						print( "<div class='one-custom-field'><input " . $onkeyup . " type='text' data-type='textarea' class='cfid modal_survey_tooltip' title='" . __( 'ID of textarea field, eg.: Description', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->id . "' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip' value='" . $cf->name . "' title='" . __( 'Placeholder for custom field, eg.: Description', MODAL_SURVEY_TEXT_DOMAIN ) . "' placeholder='" . __( 'Description', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='text' class='cfwarning modal_survey_tooltip' title='" . __( 'Warning text for the field if it is required, eg.: Description field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->warning . "' placeholder='" . __( 'Warning', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='text' class='cfminlength modal_survey_tooltip' title='" . $cf->warning . "' placeholder='" . __( 'Minimum character length for required field', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->minlength . "' placeholder='0'><input type='checkbox' class='cfrequired modal_survey_tooltip' " . $req_value . " title='" . __( 'Check this if the field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $req . "'><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove Custom Textarea Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png', __FILE__ ) . "'></div>" );
					}
					if ( $cf->type == "radio" ) {
						print( "<div class='one-custom-field'><input " . $onkeyup . " type='text' data-type='radio' class='cfid modal_survey_tooltip' title='" . __( 'ID of radio field, eg.: GENDER', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->id . "' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='" . $cf->name . "' title='" . __( 'Name and value pair for custom field, eg.: Female:female,Male:male', MODAL_SURVEY_TEXT_DOMAIN ) . "' placeholder='" . __( 'Female:female,Male:male', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='checkbox' class='cfrequired modal_survey_tooltip' " . $req_value . " title='" . __( 'Check this if the field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $req . "'><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove Radio Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png', __FILE__ ) . "'></div>" );
					}
					if ( $cf->type == "select" ) {
						print( "<div class='one-custom-field'><input " . $onkeyup . " type='text' data-type='select' class='cfid modal_survey_tooltip' title='" . __( 'ID of select field, eg.: FRUITS', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->id . "' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='" . $cf->name . "' title='" . __( 'Name and value pair for custom field, eg.: Select from the list,Apple:apple,Orange:orange,Lemon:lemon', MODAL_SURVEY_TEXT_DOMAIN ) . "' placeholder='" . __( 'Select from the list,Apple:applevalue,Orange:orangevalue,Lemon:lemonvalue', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='checkbox' class='cfrequired modal_survey_tooltip' " . $req_value . " title='" . __( 'Check this if the field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $req . "'><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove Select Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png', __FILE__ ) . "'></div>" );
					}
					if ( $cf->type == "hidden" ) {
						print( "<div class='one-custom-field'><input " . $onkeyup . " type='text' data-type='hidden' class='cfid modal_survey_tooltip' title='" . __( 'ID of hidden field, eg.: SIGNUP', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->id . "' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='" . $cf->name . "' title='" . __( 'Value of the field, eg.: blog name', MODAL_SURVEY_TEXT_DOMAIN ) . "'><div class='emptycheckbox'></div><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove Hidden Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png', __FILE__ ) . "'></div>" );
					}
					if ( $cf->type == "checkbox" ) {
						print( "<div class='one-custom-field'><input " . $onkeyup . " type='text' data-type='checkbox' class='cfid modal_survey_tooltip' title='" . __( 'ID of checkbox field, eg.: CONFIRMATION', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $cf->id . "' placeholder='ID'><input type='text' class='cfname modal_survey_tooltip longinput' value='" . $cf->name . "' title='" . __( 'Text for checkbox field, eg.: Confirm to subscribe to our Mail List', MODAL_SURVEY_TEXT_DOMAIN ) . "' placeholder='" . __( 'Please confirm your subscription', MODAL_SURVEY_TEXT_DOMAIN ) . "'><input type='checkbox' class='cfrequired modal_survey_tooltip' " . $req_value . " title='" . __( 'Check this if the field is mandatory', MODAL_SURVEY_TEXT_DOMAIN ) . "' value='" . $req . "'><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove Checkbox Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png', __FILE__ ) . "'></div>" );
					}
					if ( $cf->type == "html" ) {
						print( "<div class='one-custom-field custom_field_section_html' data-type='html' data-id='" . $cf->id . "'>" );
						if ( ! property_exists( $cf, 'name' ) ) {
							$cf->name = "";
						}
						if ( ! property_exists( $cf, 'position' ) ) {
							$cf->position = "2";
						}
						wp_editor( $cf->name, "customhtml_" . $cf->id, array(
							'wpautop' => true, // use wpautop?
							'media_buttons' => true, // show insert/upload button(s)
							'textarea_name' => "customhtml_" . $cf->id, // set the textarea name to something different, square brackets [] can be used here
							'textarea_rows' => 20, // rows="..."
							'tabindex' => '', //The tabindex value used for the form field
							'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
							'editor_class' => '', // add extra class(es) to the editor textarea
							'editor_height' => '', //The height to set the editor in pixels. If set, will be used instead of textarea_rows.
							'teeny' => false, // output the minimal editor config used in Press This
							'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
							'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
							'quicktags' => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
							'drag_drop_upload' => true //Enable Drag & Drop Upload Support
						) );
						print( "<div class='emptycheckbox'></div><img class='remove_cfield modal_survey_tooltip' title='" . __( 'Remove HTML Field', MODAL_SURVEY_TEXT_DOMAIN ) . "' src='" . plugins_url( '/assets/img/delete.png', __FILE__ ) . "'><div class='customhtml_position'><label><input type='radio' name='customhtml_pos_" . $cf->id . "' class='customhtml_pos' " . ( $cf->position == '1' ? 'checked' : '' ) . " value='1'>" . __( 'At the top of the form', MODAL_SURVEY_TEXT_DOMAIN ) . "</label><label><input type='radio' name='customhtml_pos_" . $cf->id . "' class='customhtml_pos' " . ( $cf->position == '2' ? 'checked' : '' ) . " value='2'>" . __( 'Above the Send button', MODAL_SURVEY_TEXT_DOMAIN ) . "</label><label><input type='radio' name='customhtml_pos_" . $cf->id . "' class='customhtml_pos' " . ( $cf->position == '3' ? 'checked' : '' ) . " value='3'>" . __( 'Below the Send button', MODAL_SURVEY_TEXT_DOMAIN ) . "</label></div></div>" );
					}
				}
			}				
			print( '</div>
			<div style="clear: both;"></div><br><hr><br>
			<div class="ms_inner_wpeditor"><h5>' . __( 'Auto Response', MODAL_SURVEY_TEXT_DOMAIN ) . '</h5>
				<div class="autoresponse_details"><div class="inputtext autoresponse_sendername text">' . __( 'Sender Name', MODAL_SURVEY_TEXT_DOMAIN ) . ':<br><input type="text" value="' . $options[ 148 ] . '"></div><div class="inputtext autoresponse_senderemail text">' . __( 'Sender Email Address', MODAL_SURVEY_TEXT_DOMAIN ) . ':<br><input type="text" value="' . $options[ 149 ] . '">@' . str_replace( "www.", "", $_SERVER[ 'HTTP_HOST' ] ) . '</div><div class="inputtext autoresponse_subject text">' . __( 'Subject', MODAL_SURVEY_TEXT_DOMAIN ) . ':<br><input type="text" value="' . $options[ 150 ] . '"></div></div><div>');
				wp_editor( $ms_content, $ms_editor_id, $ms_settings );
				print('</div></div>
				<a class="participants_form_help" target="_blank" href="http://modalsurvey.pantherius.com/documentation/#line2_8">Click here for the usable smart tags and shortcodes!</a>
			</div>
			</div>
			<div class="ms_accordion_more_api additional-options-accordion"><h4>' . __( 'Campaigns', MODAL_SURVEY_TEXT_DOMAIN ) . '<span>' . __( 'Save the survey\'s form datas automatically to Newsletter Campaigns', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></h4><div>
			<div>
				<div>
					<div style="display: inline-block;">
						<label class="text modal_survey_tooltip" title="' . __( 'Display confirmation checkbox to subscribe the user to the mail list', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 300px;"><input type="checkbox" name="msform_confirmation" class="inputtext msform_confirmation admincheckbox" '.$msform_confirmation.' value="'.$msform_confirmation_value.'" /> ' . __( 'Enable Subscription Confirmation', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div style="display: inline-block;">
						<label class="text modal_survey_tooltip" title="' . __( 'Allows sending the form without signing up (works with the Subscription Confirmation only)', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 300px;"><input type="checkbox" name="msform_wosignup" class="inputtext msform_wosignup admincheckbox" '.$msform_wosignup.' value="'.$msform_wosignup_value.'" /> ' . __( 'Submit Form without Confirm Signup', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div style="clear: both;"></div>
					<hr style="margin: 40px 0px 10px 0px">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Active Campaign', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="activecampaign_status" class="inputtext addmoreapi_activecampaign admincheckbox" '.$activecampaign.' value="'.$activecampaign_value.'" /> ' . __( 'Enable Active Campaign', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Find your Active Campaign API URL in your Active Campaign Account->My Settings->API menu after you logged in', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Active Campaign API URL', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield activecampaign_url" placeholder="API URL" value="' . str_replace( '"', '\'', $options[ 25 ] ) . '" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Find your Active Campaign API Key in your Active Campaign Account->My Settings->API menu after you logged in', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Active Campaign API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield activecampaign_apikey" placeholder="API Key" value="' . str_replace( '"', '\'', $options[ 26 ] ) . '" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Select your list in your Active Campaign account, then see the List ID in the URL. Eg.: List ID=1 from username.activehosted.com/contact/?listid=1&status=1', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Active Campaign List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield activecampaign_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[27]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable AWeber', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="aweber_status" class="inputtext addmoreapi_aweber admincheckbox" '.$aweber.' value="'.$aweber_value.'" /> ' . __( 'Enable AWeber', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>' );
					$aweber_display = "";$aweber_connection = "";
					if ( ! empty( $options[ 30 ] ) && ! empty( $options[ 31 ] ) && ! empty( $options[ 32 ] ) && ! empty( $options[ 33 ] ) ) {
						$aweber_display = "dnone";
						$aweber_connection = "<p>" .__( 'Successfully Connected to AWeber', MODAL_SURVEY_TEXT_DOMAIN ) . " <a class='campaign-disconnect' data-show='aweber_connect_container' href='#'>" . __( 'If you would like to reconnect, please click here.', MODAL_SURVEY_TEXT_DOMAIN ) . "</a></p>";
					}
					print('<div class="aright">
						<div class="aweber_connect_container text fwidth modal_survey_tooltip ' . $aweber_display . '" title="' . __( 'Enter your AWeber Authorization Code', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Authorization Code', MODAL_SURVEY_TEXT_DOMAIN ) . ': <textarea cols="40" rows="4" style="display:block;" name="shortfield" class="inputtext aweber_authorizationcode" placeholder="AWeber Authorization Code">'.str_replace('"','\'',$options[29]).'</textarea>
						<div style="clear:both;"></div>
						<a href="https://auth.aweber.com/1.0/oauth/authorize_app/3333db95" class="button button-secondary button-small" id="awebercredentials" target="_blank">' . __( 'Get Authorization Code', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>
						<span><a href="" id="aweber-authorization" class="connect-campaign button button-secondary button-small" target="_blank">' . __( 'Connect to AWeber', MODAL_SURVEY_TEXT_DOMAIN ) . '</a></span></div>
						' . $aweber_connection . '<div style="clear:both;"></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your AWeber List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="aweberlists" title="' . __( 'Click here to get your AWeber Lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '<br>' . __( 'Authorization Code and Connected state are required for getting the lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield aweber_listid" placeholder="AWeber List ID" value="' . str_replace( '"', '\'', $options[ 34 ] ) . '" /></div>
						<div class="text shortfield modal_survey_tooltip dnone" title="' . __( 'AWeber Consumer Key will be filled out automatically once you enter the Authorization Code and click on the Connect button', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Consumer Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" readonly="true" class="inputtext shortfield aweber_consumerkey" placeholder="AWeber Consumer Key" value="' . str_replace( '"', '\'', $options[ 30 ] ) . '" /></div>
						<div class="text shortfield modal_survey_tooltip dnone" title="' . __( 'AWeber Consumer Secret will be filled out automatically once you enter the Authorization Code and click on the Connect button', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Consumer Secret', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" readonly="true" class="inputtext shortfield aweber_consumersecret" placeholder="AWeber Consumer Secret" value="' . str_replace( '"', '\'', $options[ 31 ] ) . '" /></div>
						<div class="text shortfield modal_survey_tooltip dnone" title="' . __( 'AWeber Access Key will be filled out automatically once you enter the Authorization Code and click on the Connect button', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Access Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" readonly="true" name="shortfield" class="inputtext shortfield aweber_accesskey" placeholder="AWeber Access Key" value="' . str_replace( '"', '\'', $options[ 32 ] ) . '" /></div>
						<div class="text shortfield modal_survey_tooltip dnone" title="' . __( 'AWeber Access Secret will be filled out automatically once you enter the Authorization Code and click on the Connect button', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Access Secret', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" readonly="true" class="inputtext shortfield aweber_accesssecret" placeholder="AWeber Access Secret" value="' . str_replace( '"', '\'', $options[ 33 ] ) . '" /></div>
					</div>
					<div class="aweberlists_container autocont"></div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Benchmark', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="benchmark_status" class="inputtext addmoreapi_benchmark admincheckbox" '.$benchmark.' value="'.$benchmark_value.'" /> ' . __( 'Enable Benchmark', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield"><a target="_blank" class="modal_survey_tooltip" title="' . __( 'Click here to get your API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '<br>' . __( 'Registration required', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://ui.benchmarkemail.com/EditSetting#apikey">' . __( 'Benchmark API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="modal_survey_tooltip inputtext shortfield benchmark_apikey" placeholder="API Key" title="' . __( 'Enter your Benchmark API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '" value="'.str_replace('"','\'',$options[37]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Benchmark List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="benchmarklists" title="' . __( 'Click here to get your Benchmark Lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '<br>' . __( 'Valid API Key is required for getting the lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Benchmark List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield benchmark_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[38]).'" /></div>
						<div class="text shortfield"><label class="text modal_survey_tooltip" title="' . __( 'Enable Double-Optin', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;vertical-align:baseline;"><input type="checkbox" name="benchmark_doubleoptin" class="inputtext benchmark_doubleoptin admincheckbox" '.$benchmark_doubleoptin.' value="'.$benchmark_doubleoptin_value.'" /> ' . __( 'Enable Double Optin', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
						<div class="benchmarklists_container autocont"></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Campaign Monitor', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="campaignmonitor_status" class="inputtext addmoreapi_campaignmonitor admincheckbox" '.$campaignmonitor.' value="'.$campaignmonitor_value.'" /> ' . __( 'Enable Campaign Monitor', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield">' . __( 'Campaign Monitor API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="modal_survey_tooltip inputtext shortfield campaignmonitor_apikey" title="' . __( 'Log in to your Campaign Monitor account and find the API Key in ', MODAL_SURVEY_TEXT_DOMAIN ) . '<i>' . __( 'Account Settings', MODAL_SURVEY_TEXT_DOMAIN ) . '</i>" placeholder="API Key" value="'.str_replace('"','\'',$options[40]).'" /></div>
						<div class="text shortfield">' . __( 'Campaign Monitor List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield campaignmonitor_listid modal_survey_tooltip" title="' . __( 'Find your Campaign Monitor List ID in the bottom of the Edit screen of your chosen List', MODAL_SURVEY_TEXT_DOMAIN ) . '" placeholder="List ID" value="'.str_replace('"','\'',$options[41]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Campayn', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="campayn_status" class="inputtext addmoreapi_campayn admincheckbox" '.$campayn.' value="'.$campayn_value.'" /> ' . __( 'Enable Campayn', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'You can see in the URL after you logged in to Campayn', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Campayn Domain', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield campayn_domain" placeholder="Domain" value="'.str_replace('"','\'',$options[43]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Find it here', MODAL_SURVEY_TEXT_DOMAIN ) . ': http://xxx.campayn.com/users/api<br>' . __( 'replace xxx to your Campayn Domain', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Campayn API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield campayn_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[44]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Campayn List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="campaynlists" title="' . __( 'Click here to get your Campayn Lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '<br>' . __( 'Valid API Key and Domain are required for getting the lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Campayn List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield campayn_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[45]).'" /></div>
						<div class="campaynlists_container autocont"></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Constant Contact', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="constantcontact_status" class="inputtext addmoreapi_constantcontact admincheckbox" '.$constantcontact.' value="'.$constantcontact_value.'" /> ' . __( 'Enable Constant Contact', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Constant Contact API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Constant Contact API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield constantcontact_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[47]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Constant Contact Access Token', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Constant Contact Access Token', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield constantcontact_accesstoken" placeholder="Access Token" value="'.str_replace('"','\'',$options[48]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Constant Contact List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="constantcontactlists" title="' . __( 'Click here to get your Constant Contact Lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '<br>' . __( 'Valid API Key and Access Token are required for getting the lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Constant Contact List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield constantcontact_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[49]).'" /></div>
						<div class="constantcontactlists_container autocont"></div>
					</div>
					<p class="tinynote">' . __( 'To get API Key,', MODAL_SURVEY_TEXT_DOMAIN ) . ' <a target="_blank" href="https://constantcontact.mashery.com/member/register">' . __( 'register here', MODAL_SURVEY_TEXT_DOMAIN ) . '</a> ' . __( 'and', MODAL_SURVEY_TEXT_DOMAIN ) . ' <a  target="_blank" href="https://constantcontact.mashery.com/apps/register">' . __( 'create you APP here', MODAL_SURVEY_TEXT_DOMAIN ) . ' </a>. <a  target="_blank" href="https://constantcontact.mashery.com/io-docs">' . __( 'Select your app and get the Access Token here.', MODAL_SURVEY_TEXT_DOMAIN ) . '</a></p>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Freshmail', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="freshmail_status" class="inputtext addmoreapi_freshmail admincheckbox" '.$freshmail.' value="'.$freshmail_value.'" /> ' . __( 'Enable Freshmail', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield"><a target="_blank" class="modal_survey_tooltip" title="' . __( 'Click here to get your Freshmail API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://app.freshmail.com/en/settings/integration/">' . __( 'Freshmail API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield freshmail_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[51]).'" /></div>
						<div class="text shortfield"><a target="_blank" class="modal_survey_tooltip" title="' . __( 'Click here to get your Freshmail API Secret', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://app.freshmail.com/en/settings/integration/">' . __( 'Freshmail API Secret', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield freshmail_apisecret" placeholder="API Secret" value="'.str_replace('"','\'',$options[52]).'" /></div>
						<div class="text shortfield><a target="_blank" class="modal_survey_tooltip" title="' . __( 'Click here to get your Lists, then select one and see the Freshmail List Hash in the URL. Eg.:', MODAL_SURVEY_TEXT_DOMAIN ) . ' List Hash = nynedux96k ' . __( 'from', MODAL_SURVEY_TEXT_DOMAIN ) . ' https://app.freshmail.com/en/subscribers/index/?id_hash=nynedux96k" href="https://app.freshmail.com/en/lists/index/">' . __( 'Freshmail List Hash', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield freshmail_listhash" placeholder="List Hash" value="'.str_replace('"','\'',$options[53]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable GetResponse', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="getresponse_status" class="inputtext addmoreapi_getresponse admincheckbox" '.$getresponse.' value="'.$getresponse_value.'" /> ' . __( 'Enable GetResponse', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield"><a target="_blank" class="modal_survey_tooltip" title="' . __( 'Click here to get your GetResponse API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://app.getresponse.com/account.html#api">' . __( 'GetResponse API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield getresponse_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[55]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your GetResponse Campaign ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="getresponselists" title="' . __( 'Click here to get your GetResponse Campaigns.', MODAL_SURVEY_TEXT_DOMAIN ) . '<br>' . __( 'Valid API Key is required for getting the lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'GetResponse Campaign ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield getresponse_campaignid" placeholder="Campaign ID" value="'.str_replace('"','\'',$options[56]).'" /></div>
						<div class="getresponselists_container autocont"></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable iContact', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="icontact_status" class="inputtext addmoreapi_icontact admincheckbox" '.$icontact.' value="'.$icontact_value.'" /> ' . __( 'Enable iContact', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your iContact appId', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'iContact appId', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield icontact_appid" placeholder="appId" value="'.str_replace('"','\'',$options[58]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your iContact API Username', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'iContact API Username', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield icontact_apiusername" placeholder="API Username" value="'.str_replace('"','\'',$options[59]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your iContact API Password', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'iContact API Password', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield icontact_apipassword" placeholder="API Password" value="'.str_replace('"','\'',$options[60]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your iContact List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'iContact List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield icontact_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[61]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Infusionsoft', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="infusionsoft_status" class="inputtext addmoreapi_infusionsoft admincheckbox" '.$infusionsoft.' value="'.$infusionsoft_value.'" /> ' . __( 'Enable Infusionsoft', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Infusionsoft API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Infusionsoft API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield infusionsoft_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[63]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Infusionsoft Campaign ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Infusionsoft Campaign ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield infusionsoft_campaignid" placeholder="Campaign ID" value="'.str_replace('"','\'',$options[64]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Infusionsoft Group ID (optional)', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Infusionsoft Group ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield infusionsoft_groupid" placeholder="Group ID" value="'.str_replace('"','\'',$options[65]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Interspire', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="interspire_status" class="inputtext addmoreapi_interspire admincheckbox" '.$interspire.' value="'.$interspire_value.'" /> ' . __( 'Enable Interspire', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Interspire Username', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Interspire Username', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield interspire_username" placeholder="Username" value="'.str_replace('"','\'',$options[67]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Interspire User Token', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Interspire User Token', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield interspire_usertoken" placeholder="User Token" value="'.str_replace('"','\'',$options[68]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Interspire List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Interspire List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield interspire_listid" placeholder=" List ID" value="'.str_replace('"','\'',$options[69]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Mad Mimi', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="madmimi_status" class="inputtext addmoreapi_madmimi admincheckbox" '.$madmimi.' value="'.$madmimi_value.'" /> ' . __( 'Enable Mad Mimi', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mad Mimi Username', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Mad Mimi Username(or email)', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield madmimi_username" placeholder="Username" value="'.str_replace('"','\'',$options[71]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mad Mimi API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a target="_blank" href="https://madmimi.com/user/edit?account_info_tabs=account_info_personal">' . __( 'Mad Mimi API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield madmimi_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[72]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mad Mimi List Name', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="https://madmimi.com/audience_members" target="_blank">' . __( 'Mad Mimi List Name', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield madmimi_listname" placeholder="List Name" value="'.str_replace('"','\'',$options[73]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable MailChimp••G•F•X•F•U•L•L•.•N•E•T••', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="mailchimp_status" class="inputtext addmoreapi_mailchimp admincheckbox" '.$mailchimp.' value="'.$mailchimp_value.'" /> ' . __( 'Enable MailChimp', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your MailChimp API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'MailChimp API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailchimp_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[75]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your MailChimp List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'MailChimp List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailchimp_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[76]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable MailerLite', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="mailerlite_status" class="inputtext addmoreapi_mailerlite admincheckbox" '.$mailerlite.' value="'.$mailerlite_value.'" /> ' . __( 'Enable MailerLite', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your MailerLite API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'MailerLite API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailerlite_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[78]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your MailerLite List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'MailerLite List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailerlite_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[79]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Mailigen', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="mailigen_status" class="inputtext addmoreapi_mailigen admincheckbox" '.$mailigen.' value="'.$mailigen_value.'" /> ' . __( 'Enable Mailigen', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mailigen API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Mailigen API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailigen_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[82]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mailigen List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Mailigen List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailigen_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[83]).'" /></div>
						<div class="text shortfield"><label class="text modal_survey_tooltip" title="' . __( 'Enable Double-Optin', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;vertical-align:baseline;"><input type="checkbox" name="mailigen_doubleoptin" class="inputtext mailigen_doubleoptin admincheckbox" '.$mailigen_doubleoptin.' value="'.$mailigen_doubleoptin_value.'" /> ' . __( 'Enable Double-Optin', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Mailjet', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="mailjet_status" class="inputtext addmoreapi_mailjet admincheckbox" '.$mailjet.' value="'.$mailjet_value.'" /> ' . __( 'Enable Mailjet', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mailjet API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Mailjet API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailjet_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[85]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mailjet Secret Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Mailjet Secret Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailjet_secretkey" placeholder="Secret Key" value="'.str_replace('"','\'',$options[86]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Mailjet List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Mailjet List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield mailjet_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[87]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable MailPoet', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="mailpoet_status" class="inputtext addmoreapi_mailpoet admincheckbox" '.$mailpoet.' value="'.$mailpoet_value.'" /> ' . __( 'Enable MailPoet', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your MailPoet List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="mailpoetlists" title="' . __( 'Click here to get your MailPoet Lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'MailPoet List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield mailpoet_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[89]).'" /></div>
					</div>
					<div class="mailpoetlists_container autocont"></div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Emma', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="emma_status" class="inputtext addmoreapi_emma admincheckbox" '.$emma.' value="'.$emma_value.'" /> ' . __( 'Enable Emma', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Emma Account ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Emma Account ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield emma_accountid" placeholder="Account ID" value="'.str_replace('"','\'',$options[91]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Emma Public Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Emma Public Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield emma_publickey" placeholder="Public Key" value="'.str_replace('"','\'',$options[92]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Emma Private Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Emma Private Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield emma_privatekey" placeholder="Private Key" value="'.str_replace('"','\'',$options[93]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable MyMail', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="mymail_status" class="inputtext addmoreapi_mymail admincheckbox" '.$mymail.' value="'.$mymail_value.'" /> ' . __( 'Enable MyMail', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your MyMail List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="mymaillists" title="' . __( 'Click here to get your MyMail Lists.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'MyMail List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield mymail_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[95]).'" /></div>
					</div>
					<div class="mymaillists_container autocont"></div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable ONTRAPORT', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="ontraport_status" class="inputtext addmoreapi_ontraport admincheckbox" '.$ontraport.' value="'.$ontraport_value.'" /> ' . __( 'Enable ONTRAPORT', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your ONTRAPORT APP ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'ONTRAPORT APP ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield ontraport_appid" placeholder="APP ID" value="'.str_replace('"','\'',$options[97]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your ONTRAPORT Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'ONTRAPORT Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield ontraport_key" placeholder="Key" value="'.str_replace('"','\'',$options[98]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your ONTRAPORT Tag ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'ONTRAPORT Tag ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield ontraport_tagid" placeholder="Tag ID" value="'.str_replace('"','\'',$options[99]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your ONTRAPORT Sequence ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'ONTRAPORT Sequence ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield ontraport_sequenceid" placeholder="Sequence ID" value="'.str_replace('"','\'',$options[100]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Pinpointe', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="pinpointe_status" class="inputtext addmoreapi_pinpointe admincheckbox" '.$pinpointe.' value="'.$pinpointe_value.'" /> ' . __( 'Enable Pinpointe', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Pinpointe Username', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Pinpointe Username', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield pinpointe_username" placeholder="Username" value="'.str_replace('"','\'',$options[102]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Pinpointe User Token', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Pinpointe User Token', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield pinpointe_usertoken" placeholder="User Token" value="'.str_replace('"','\'',$options[103]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Pinpointe List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Pinpointe List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield pinpointe_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[104]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable SendinBlue', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="sendinblue_status" class="inputtext addmoreapi_sendinblue admincheckbox" '.$sendinblue.' value="'.$sendinblue_value.'" /> ' . __( 'Enable SendinBlue', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your SendinBlue Access Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'SendinBlue Access Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendinblue_accesskey" placeholder="Access Key" value="'.str_replace('"','\'',$options[106]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your SendinBlue List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'SendinBlue List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendinblue_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[107]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable SendReach', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="sendreach_status" class="inputtext addmoreapi_sendreach admincheckbox" '.$sendreach.' value="'.$sendreach_value.'" /> ' . __( 'Enable SendReach', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your SendReach Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'SendReach Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendreach_key" placeholder="Key" value="'.str_replace('"','\'',$options[109]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your SendReach Secret', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'SendReach Secret', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendreach_secret" placeholder="Secret" value="'.str_replace('"','\'',$options[110]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your SendReach User ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'SendReach User ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendreach_userid" placeholder="User ID" value="'.str_replace('"','\'',$options[111]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your SendReach List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'SendReach List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendreach_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[112]).'" /></div>
					</div>
				</div>
				<div class="dnone">
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable Sendy', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="sendy_status" class="inputtext addmoreapi_sendy admincheckbox" '.$sendy.' value="'.$sendy_value.'" /> ' . __( 'Enable Sendy', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Sendy Installation URL', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Sendy Installation URL', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendy_installationurl" placeholder="Installation URL" value="'.str_replace('"','\'',$options[114]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Sendy API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Sendy API Key', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendy_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[115]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your Sendy List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Sendy List ID', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield sendy_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[116]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable SimplyCast', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="simplycast_status" class="inputtext addmoreapi_simplycast admincheckbox" '.$simplycast.' value="'.$simplycast_value.'" /> ' . __( 'Enable SimplyCast', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield"><a class="modal_survey_tooltip" title="' . __( 'Click here to get your SimplyCast Public Key', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://app.simplycast.com/?q=account/info/api" target="_blank">' . __( 'SimplyCast Public Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield simplycast_publickey" placeholder="Public Key" value="'.str_replace('"','\'',$options[118]).'" /></div>
						<div class="text shortfield"><a class="modal_survey_tooltip" title="' . __( 'Click here to get your SimplyCast Secret Key', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://app.simplycast.com/?q=account/info/api" target="_blank">' . __( 'SimplyCast Secret Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield simplycast_secretkey" placeholder="Secret Key" value="'.str_replace('"','\'',$options[119]).'" /></div>
						<div class="text shortfield"><a class="modal_survey_tooltip" title="' . __( 'Click here to get your SimplyCast List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="https://app.simplycast.com/?q=crm/lists" target="_blank">' . __( 'SimplyCast List ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield simplycast_listid" placeholder="List ID" value="'.str_replace('"','\'',$options[120]).'" /></div>
					</div>
				</div>
				<div>
					<div>
						<label class="text modal_survey_tooltip" title="' . __( 'Enable YMLP', MODAL_SURVEY_TEXT_DOMAIN ) . '" style="width: 200px;"><input type="checkbox" name="ymlp_status" class="inputtext addmoreapi_ymlp admincheckbox" '.$ymlp.' value="'.$ymlp_value.'" /> ' . __( 'Enable YMLP', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>
					</div>
					<div class="aright">
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your YMLP Username', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'YMLP Username', MODAL_SURVEY_TEXT_DOMAIN ) . ': <input type="text" name="shortfield" class="inputtext shortfield ymlp_username" placeholder="Username" value="'.str_replace('"','\'',$options[122]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your YMLP API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a target="_blank" href="http://www.ymlp.com/app/api.php">' . __( 'YMLP API Key', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield ymlp_apikey" placeholder="API Key" value="'.str_replace('"','\'',$options[123]).'" /></div>
						<div class="text shortfield modal_survey_tooltip" title="' . __( 'Enter your YMLP Group ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"><a href="#" class="getapiinfo" data-apiid="ymlplists" title="' . __( 'Click here to get your YMLP Groups.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'YMLP Group ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>: <input type="text" name="shortfield" class="inputtext shortfield ymlp_groupid" placeholder="Group ID" value="'.str_replace('"','\'',$options[124]).'" /></div>
					</div>
					<div class="ymlplists_container autocont"></div>
					</div>
			</div>
			</div></div>
<div class="click-nav">
  <ul class="no-js">
		<li><div class="button button-primary">' . __( 'Export Survey', MODAL_SURVEY_TEXT_DOMAIN ) . '</div>
			<ul>
				<li><a class="exportlink" href="csv">CSV</a></li>
				<li><a class="exportlink" href="json">JSON</a></li>
				<li><a class="pdfexportlink" href="pdf">PDF</a></li>
				<li><a class="exportlink" href="xml">XML</a></li>
				<li><a class="exportlink" href="xls">XLS</a></li>
				<li><a class="exportlink" href="txt">TXT</a></li>
			</ul>
		</li>
	</ul>
</div>
<div class="shortcode_section"><a href="#" class="help-dialog button button-primary" data-helpid="help3">' . __( 'Results', MODAL_SURVEY_TEXT_DOMAIN ) . ' </a><a href="#" class="help-dialog button button-primary" data-helpid="help1">' . __( 'Embed Mode', MODAL_SURVEY_TEXT_DOMAIN ) . ' </a><a href="#" class="help-dialog button button-primary" data-helpid="help2">' . __( 'Modal Mode', MODAL_SURVEY_TEXT_DOMAIN ) . ' </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<div style="clear:both;"></div>
			<div class="dnone"><a class="nquestion add_question button button-primary button-small">' . __( 'New Question', MODAL_SURVEY_TEXT_DOMAIN ) . '</a></div>
			<div style="clear:both;"></div>
			<input type="hidden" name="survey_id" value="" />
		<div id="new_questions">');
		foreach( $questions as $key=>$qv ) {
			if ( $key > 0 ) {
				$rem_q = '<a class="add_question"><img class="remove_question modal_survey_tooltip" title="' . __( 'Remove Question', MODAL_SURVEY_TEXT_DOMAIN ) . '" id="remove_question_' . $sv->id . '_' . ( $key + 1 ) . '" src="' . plugins_url( '/assets/img/delete.png' , __FILE__ ) . '"></a><a class="dup_question"><img class="duplicate_question modal_survey_tooltip" title="' . __( 'Duplicate Question', MODAL_SURVEY_TEXT_DOMAIN ) . '" id="duplicate_question_' . $sv->id . '_' . ( $key + 1 ) . '" data-qid="' . ( $key + 1 ) . '" src="'.plugins_url( '/assets/img/list-duplicate.png', __FILE__ ).'"></a>';
			}
			else {
				$rem_q = '<a class="dup_question"><img class="duplicate_question modal_survey_tooltip" title="' . __( 'Duplicate Question', MODAL_SURVEY_TEXT_DOMAIN ) . '" id="duplicate_question_' . $sv->id . '_' . ( $key + 1 ) . '" data-qid="1" src="' . plugins_url( '/assets/img/list-duplicate.png', __FILE__ ) . '"></a>';
			}
			$allcount = $this->wpdb->get_var("SELECT SUM(count) as SUMMACOUNT FROM " . $this->wpdb->base_prefix . "modal_survey_answers WHERE `survey_id`='" . $sv->id . "' AND `question_id`='" . $qv->id . "'");
			$q_opts = unserialize( $qv->qoptions );
			for ( $x = 0; $x <= 1000; $x++ ) {
				if ( ! isset( $q_opts[ $x ] ) ) {
				$q_opts[ $x ] = "";
				}
			}
			if ( is_numeric( $q_opts[ 0 ] ) ) {
				$sq_choices = $q_opts[ 0 ];
			}
			else {
				$sq_choices = 1;
			}
			if ( is_numeric( $q_opts[ 1 ] ) ) {
				$sq_minchoices = $q_opts[ 1 ];
			}
			else {
				$sq_minchoices = 1;
			}
			if ( ! empty( $q_opts[ 2 ] ) ) {
			$quploader = '<div class="imageelement">
							<div class="uploaded_image">
								<div class="image_container">
									<img src="' . $q_opts[ 2 ] . '">
									<input class="upl_image upl-photo" name="upl_image[]" type="hidden" value="' . $q_opts[ 2 ] . '" />
									<input class="remove_answerimage_button button remove-button" type="button" value="' . __( 'Remove', MODAL_SURVEY_TEXT_DOMAIN ) . '" data-type="url" />
								</div>
							</div>
						</div>';
		} 
		else {
			$quploader = '<div class="imageelement">
							<div class="uploaded_image">
								<input class="answer-image-upload button add-button" type="button" value="' . __( 'Add Image', MODAL_SURVEY_TEXT_DOMAIN ) . '" />
							</div>
						</div>';	
		}
		$que_options = '<div class="modal_survey_tooltip optscontainer" title="' . __( 'Specify how many answers can be selectable by the users', MODAL_SURVEY_TEXT_DOMAIN ) . '"><span>' . __( 'Optional Answers:', MODAL_SURVEY_TEXT_DOMAIN ) . '</span> <input name="choices[]" size="3" maxlength="3" type="number" min="1" class="choices optsinput" value="' . $sq_choices . '"></div><div class="optscontainer"><span class="modal_survey_tooltip" title="' . __( 'Define how many answers needs to be selected by the user', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Required Answers:', MODAL_SURVEY_TEXT_DOMAIN ) . '</span> <input name="choices[]" size="3" type="number" min="1" maxlength="3" class="minchoices optsinput" value="' . $sq_minchoices . '"></div><div class="question_tooltip optscontainer"><span>' . __( 'Custom Tooltip', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><textarea maxlength="300" class="question_tooltip_text" placeholder="Enter the question tooltip here or leave it empty.">' . $q_opts[ 4 ] . '</textarea></div><div class="question_category_container optscontainer modal_survey_tooltip" title="' . __( 'Specify the category name to cumulate the score into categories.', MODAL_SURVEY_TEXT_DOMAIN ) . '"><span>' . __( 'Category', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><input class="question_category optsinput" placeholder="Enter the category name or leave it empty." value="' . $q_opts[ 5 ] . '"></div><div class="modal_survey_tooltip optscontainer" title="' . __( 'Set this question as a Rating Question, it will convert answers to stars', MODAL_SURVEY_TEXT_DOMAIN ) . '"><label><input type="checkbox" class="qopts_rating ms-checkbox" ' . ( $q_opts[ 3 ] ==1 ? 'checked' : '' ) . ' value="' . $q_opts[ 3 ] . '"><span>' . __( 'Set as Rating Question', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></label></div>';
		$left_que_options = '<div class="modal_survey_tooltip optscontainer" title="' . __( 'Set the width of the image in pixel or percentage. Eg.: 150px or 50%', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Image Width:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="text" size="10" maxlength="10" placeholder="' . __( '120px', MODAL_SURVEY_TEXT_DOMAIN ) . '" class="qopts_width optsinput" value="' . $q_opts[ 6 ] . '"></div><div class="modal_survey_tooltip optscontainer" title="' . __( 'Set the height of the image in pixel or percentage. Eg.: 150px or 50%', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Image Height:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="text" size="10" maxlength="10" placeholder="' . __( '120px', MODAL_SURVEY_TEXT_DOMAIN ) . '" class="qopts_height optsinput" value="' . $q_opts[ 7 ] . '"></div>';
		if ( $q_opts[ 8 ] == "3" ) {
			$imgpos_opts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value=""> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="1"> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="2"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="3" checked="checked"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';
		}
		elseif ( $q_opts[ 8 ] == "2" ) {
			$imgpos_opts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value=""> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="1"> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="2" checked="checked"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="3"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';
		}
		elseif ( $q_opts[ 8 ] == "1" ) {
			$imgpos_opts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value=""> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="1" checked> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="2"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="3"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';
		}
		else {
			$imgpos_opts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="" checked> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="1"> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="2"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '" class="img-align" value="3"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';			
		}
		
		$question_options = '<div class="qo-accordion qaa' . ( $key + 1 ) . ' qaa' . $qv->id . '_' . ( $key + 1 ) . '"><h4>' . __( 'Question Options', MODAL_SURVEY_TEXT_DOMAIN ) . '</h4><div><div class="left_qopts">' . $left_que_options . $quploader . ' <div class="img-pos-cont"><div class=" optscontainer">' . __( 'Image Position:', MODAL_SURVEY_TEXT_DOMAIN ) . '</div>' . $imgpos_opts . '</div></div><div class="right_qopts">' . $que_options . '</div></div></div>';
		$left_ans_options = "";
		print('<div class="group">
		<h3>' . ( $key + 1 ) . '. ' . __( 'question', MODAL_SURVEY_TEXT_DOMAIN ) . '<span class="question-subheader">' . str_replace( "[-]", "", strip_tags( $qv->question ) ) . '</span></h3>
		<div class="one_question" id="question_section'.($key+1).'">
		<div class="left_half">
		<div id="question_'.($key+1).'">
			<div><a href="#" class="question_options" data-qid="' . ( $key + 1 ) . '"><img class="modal_survey_tooltip" title="' . __( 'Question Options', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="' . plugins_url( "/assets/img/options.png", __FILE__ ) . '"></a><span class="question-area">' . __( 'Question', MODAL_SURVEY_TEXT_DOMAIN ) . ':&nbsp; <textarea name="question[]" id="question'.($key+1).'" style="width: 70%;" class="question_text" placeholder="' . __( 'Type your question here', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . $qv->question . '</textarea>' . $rem_q . '</span>' . $question_options . '</div>
			<span id="answers_' . $sv->id . '">');
		$answers = $this->wpdb->get_results( "SELECT * FROM " . $this->wpdb->base_prefix . "modal_survey_answers WHERE `survey_id`='" . $sv->id . "' AND `question_id`='" . $qv->id . "' ORDER BY autoid ASC");
			foreach( $answers as $key2=>$av ) {
				if ( ! isset( $av->uniqueid ) ) {
					$av->uniqueid = "";
				}
				$aoptions = unserialize( $av->aoptions );
				for ($x = 0; $x <= 1000; $x++) {
					if ( ! isset( $aoptions[ $x ] ) ) {
					$aoptions[ $x ] = "";
					}
				}
				$answer_class = "";$answer_type = "";$open_answer_details = "";
				$autoc_state = "";$autoc_state_value = "0";
				$ans_options = '<div class="modal_survey_tooltip optscontainer" title="' . __( 'Enter the score number for this answer', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Answer Score:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="number" size="10" maxlength="10" onkeypress="return numbersandminusonly(event)" placeholder="' . __( 'Number', MODAL_SURVEY_TEXT_DOMAIN ) . '" class="aopts_score optsinput" value="' . $aoptions[ 4 ] . '"></div><div class="answer_tooltip optscontainer"><span>' . __( 'Custom Tooltip', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><textarea maxlength="300" class="answer_tooltip_text" placeholder="' . __( 'Enter the answer tooltip here or leave it empty.', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . $aoptions[ 10 ] . '</textarea></div><div class="answer_redirection_container optscontainer modal_survey_tooltip" title="' . __( 'Specify the question number to redirect the survey in case the user choose this answer.', MODAL_SURVEY_TEXT_DOMAIN ) . '"><span>' . __( 'Redirection', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><input class="answer_redirection optsinput" placeholder="' . __( 'Enter the question number or leave it empty.', MODAL_SURVEY_TEXT_DOMAIN ) . '" onkeypress="return numbersandminusonly(event)" value="' . $aoptions[ 11 ] . '"></div><div class="answer_category_container optscontainer modal_survey_tooltip" title="' . __( 'Specify the category name to cumulate the score into categories.', MODAL_SURVEY_TEXT_DOMAIN ) . '"><span>' . __( 'Category', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><input class="answer_category optsinput" placeholder="' . __( 'Enter the category name or leave it empty.', MODAL_SURVEY_TEXT_DOMAIN ) . '" value="' . $aoptions[ 12 ] . '"></div><div class="modal_survey_tooltip optscontainer" title="' . __( 'Set this answer as correct answer', MODAL_SURVEY_TEXT_DOMAIN ) . '"><label><input type="checkbox" class="aopts_correct_answer ms-checkbox" ' . ( $aoptions[ 5 ] == 1 ? 'checked' : '' ) . ' value="' . $aoptions[ 5 ] . '"><span>' . __( 'Set as Correct', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></label><input type="hidden" class="aopts_status" value="' . $aoptions[ 8 ] . '"></div>';
				$left_ans_options = '<div class="modal_survey_tooltip optscontainer" title="' . __( 'Set the width of the image in pixel or percentage. Eg.: 150px or 50%', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Image Width:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="text" size="10" maxlength="10" placeholder="' . __( '120px', MODAL_SURVEY_TEXT_DOMAIN ) . '" class="aopts_width optsinput" value="' . $aoptions[ 6 ] . '"></div><div class="modal_survey_tooltip optscontainer" title="' . __( 'Set the height of the image in pixel or percentage. Eg.: 150px or 50%', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Image Height:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="text" size="10" maxlength="10" placeholder="' . __( '120px', MODAL_SURVEY_TEXT_DOMAIN ) . '" class="aopts_height optsinput" value="' . $aoptions[ 7 ] . '"></div><div class="modal_survey_tooltip optscontainer" title="' . __( 'Hide the answer text when you using image instead', MODAL_SURVEY_TEXT_DOMAIN ) . '"><label><input type="checkbox" class="aopts_hide_label ms-checkbox" ' . ( $aoptions[ 13 ] == 1 ? 'checked' : '' ) . ' value="' . $aoptions[ 13 ] . '"><span>' . __( 'Hide Label', MODAL_SURVEY_TEXT_DOMAIN ) . '</span></label></div>';
				if ( ! empty( $aoptions[ 3 ] ) ) {
					$uploader = '<div class="imageelement">
									<div class="uploaded_image">
										<div class="image_container">
											<img src="' . $aoptions[ 3 ] . '">
											<input class="upl_image upl-photo" name="upl_image[]" type="hidden" value="' . $aoptions[ 3 ] . '" />
											<input class="remove_answerimage_button button remove-button" type="button" value="' . __( 'Remove', MODAL_SURVEY_TEXT_DOMAIN ) . '" data-type="url" />
										</div>
									</div>
								</div>';
				} 
				else {
					$uploader = '<div class="imageelement">
									<div class="uploaded_image">
										<input class="answer-image-upload button add-button" type="button" value="' . __( 'Add Image', MODAL_SURVEY_TEXT_DOMAIN ) . '" />
									</div>
								</div>';	
 				}
				if ( isset( $aoptions[ 0 ] ) ) {
					if ( $aoptions[ 0 ] == "open" ) {
						$answer_class = " open_answer_style";
						$answer_type = ' data-answertype="open"';
						$autoc_state = "";
						$autoc_state_value = "0";							
						$convertt_state = "";
						$convertt_state_value = "0";
						if ( isset( $aoptions[ 2 ] ) ) {
							if ( $aoptions[ 2 ] == "1" ) {
								$autoc_state = "checked";
								$autoc_state_value = "1";
							}
						}
						if ( isset( $aoptions[ 9 ] ) ) {
							if ( $aoptions[ 9 ] == "1" ) {
								$convertt_state = "checked";
								$convertt_state_value = "1";
							}
						}
						$ans_options = '<div class="answer_tooltip optscontainer"><div class="modal_survey_tooltip optscontainer" title="' . __( 'Enter the score number for this answer', MODAL_SURVEY_TEXT_DOMAIN ) . '">' . __( 'Answer Score:', MODAL_SURVEY_TEXT_DOMAIN ) . ' <input type="number" size="10" maxlength="10" onkeypress="return numbersandminusonly(event)" placeholder="' . __( 'Number', MODAL_SURVEY_TEXT_DOMAIN ) . '" class="aopts_score optsinput" value="' . $aoptions[ 4 ] . '"></div><span>' . __( 'Custom Tooltip', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><textarea maxlength="300" class="answer_tooltip_text" placeholder="Enter the answer tooltip here or leave it empty.">' . $aoptions[ 10 ] . '</textarea></div><div class="answer_redirection_container optscontainer modal_survey_tooltip" title="' . __( 'Specify the question number to redirect the survey in case the user choose this answer.', MODAL_SURVEY_TEXT_DOMAIN ) . '"><span>' . __( 'Redirection', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><input class="answer_redirection optsinput" placeholder="' . __( 'Enter the question number or leave it empty.', MODAL_SURVEY_TEXT_DOMAIN ) . '" onkeypress="return numbersandminusonly(event)" value="' . $aoptions[ 11 ] . '"></div><div class="answer_category_container optscontainer modal_survey_tooltip" title="' . __( 'Specify the category name to cumulate the score into categories.', MODAL_SURVEY_TEXT_DOMAIN ) . '"><span>' . __( 'Category', MODAL_SURVEY_TEXT_DOMAIN ) . '</span><input class="answer_category optsinput" placeholder="' . __( 'Enter the category name or leave it empty.', MODAL_SURVEY_TEXT_DOMAIN ) . '" value="' . $aoptions[ 12 ] . '"></div><div class="optscontainer"><span class="autocomplete_container modal_survey_tooltip" title="' . __( 'AutoComplete', MODAL_SURVEY_TEXT_DOMAIN ) . '"><label><input type="checkbox" ' . $autoc_state . ' name="autocomplete' . $av->uniqueid . '" class="autocomplete' . $av->uniqueid . ' ms-checkbox" value="' . $autoc_state_value . '"> ' . __( 'AutoComplete', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></span><span class="converttextarea_container modal_survey_tooltip" title="' . __( 'Multiline', MODAL_SURVEY_TEXT_DOMAIN ) . '"><label><input type="checkbox" ' . $convertt_state . ' name="converttextarea' . $av->uniqueid . '" class="converttextarea' . $av->uniqueid . ' ms-checkbox" value="' . $convertt_state_value . '"> ' . __( 'Multiline', MODAL_SURVEY_TEXT_DOMAIN ) . '</label></span><input type="hidden" class="aopts_status" value="' . $aoptions[ 8 ] . '"></div>';
					}
					$answers_text = $this->wpdb->get_results( "SELECT answertext, count, id FROM " . $this->wpdb->base_prefix . "modal_survey_answers_text WHERE `survey_id`='" . $sv->id . "' AND `id`='" . $av->uniqueid . "' ORDER BY count DESC");
					if ( ! empty( $answers_text ) ) {
						$open_answer_details .= '<div class="open_answers_accordion open_answers_container' . $sv->id . '_' . ( $key2 + 1 ) . '"><h5>' . __( 'View Answers', MODAL_SURVEY_TEXT_DOMAIN ) . '</h5><div><table>';
					}
  					foreach( $answers_text as $at ) {
						$open_answer_details .= '<tr><td><div data-id="' . $sv->id . '_' . $at->id . '" class="delete_open_answer"><img class="modal_survey_tooltip" title="' . __( 'Remove Open Answer', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="' . plugins_url( '/assets/img/delete.png', __FILE__ ) . '"></div>' . $at->answertext . "</td><td>" . $at->count . '</td></tr>';
					}
					if ( ! empty( $answers_text ) ) {
						$open_answer_details .= '</table><div class="click-nav2">
  <ul class="no-js">
		<li><span class="button button-primary">' . __( 'Export Answers', MODAL_SURVEY_TEXT_DOMAIN ) . '</span>
			<ul>
				<li><a class="exportalink" data-sid="' . $sv->id . '" data-qid="' . $qv->id . '" data-auid="' . $av->uniqueid . '" href="txt">TXT</a></li>
			</ul>
		</li>
	</ul>
</div></div></div>';
					}
				}
				if ( $aoptions[ 14 ] == "3" ) {
					$imgpos_aopts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value=""> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="1"> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="2" checked="checked"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="3" checked="checked"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';
				}
				elseif ( $aoptions[ 14 ] == "2" ) {
					$imgpos_aopts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value=""> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="1"> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="2" checked="checked"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="3"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';
				}
				elseif ( $aoptions[ 14 ] == "1" ) {
					$imgpos_aopts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value=""> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="1" checked> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="2"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="3"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';
				}
				else {
					$imgpos_aopts = '<label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="" checked> ' . __( 'Default', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="1"> ' . __( 'Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="2"> ' . __( 'Bottom', MODAL_SURVEY_TEXT_DOMAIN ) . '</label><label><input type="radio" name="img-align' . ( $key + 1 ) . '_' . ( $key2 + 1 ) . '" class="img-align" value="3"> ' . __( 'Text on Top', MODAL_SURVEY_TEXT_DOMAIN ) . '</label>';			
				}
				$answer_options = '<div class="ao-accordion oaa' . ( $key2 + 1 ) . ' oaa' . $sv->id . '_' . ( $key2 + 1 ) . '"><h4>' . __( 'Answer Options', MODAL_SURVEY_TEXT_DOMAIN ) . '</h4><div><div class="left_aopts">' . $left_ans_options . $uploader . '<div class="img-pos-cont"><div class="optscontainer">' . __( 'Image Position:', MODAL_SURVEY_TEXT_DOMAIN ) . '</div>' . $imgpos_aopts . '</div></div><div class="right_aopts">' . $ans_options . '</div>' . $open_answer_details .'</div></div>';
				if ($allcount>0) $percentage = round(($av->count/$allcount)*100,2);
				else $percentage = 0;
				if ( $aoptions[ 8 ] == "1" ) {
					$answer_status = '<a href="#" class="answer_status answer_status' . ($key2+1) . '" data-aid="' . ($key2+1) . '"><img class="modal_survey_tooltip" title="' . __( 'Inactive Answer - click to change', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="'.plugins_url( "/assets/img/inactive.png" , __FILE__ ).'"></a>';
				}
				else {
					$answer_status = '<a href="#" class="answer_status answer_status' . ($key2+1) . '" data-aid="' . ($key2+1) . '"><img class="modal_survey_tooltip" title="' . __( 'Active Answer - click to change', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="'.plugins_url( "/assets/img/active.png" , __FILE__ ).'"></a>';
				}
				$whoansweredthis = admin_url( 'admin.php?page=modal_survey_participants&filter=on&sid=' . $sv->id . '&qid=' . ( $key + 1 ) . '&aid=' . ( $key2 + 1 ) );
				if ( $key2 == 0 ) {
					print('<div class="default_answer"><a href="#" class="answer_options" data-aid="' . ($key2+1) . '"><img class="modal_survey_tooltip" title="' . __( 'Answer Options', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="'.plugins_url( "/assets/img/options.png" , __FILE__ ).'"></a>' . $answer_status . '<a class="modal_survey_tooltip whoansweredthis" title="' . __( 'Who answered this?', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="' . $whoansweredthis . '"><span class="ans_nmo">' . ( $key2 + 1 ) . '.</span> ' . __( 'answer:', MODAL_SURVEY_TEXT_DOMAIN ) . '</a> <input type="text" name="answer[]" class="answer' . $answer_class . '" data-unique="'.$av->uniqueid.'" id="answer1" ' . $answer_type . ' style="width: 40%;" value="' . htmlspecialchars( $av->answer ) . '" placeholder="' . __( 'no', MODAL_SURVEY_TEXT_DOMAIN ) . '" /><span id="answer_count1" class="answer_count">'.$av->count.' - '.$percentage.'%</span><a class="add_answer"><img class="modal_survey_tooltip" title="' . __( 'Add New Answer', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="'.plugins_url( "/assets/img/add.png" , __FILE__ ).'"></a>' . $answer_options . '</div>');
				}
				else {
					print('</span><div class="added_answers" id="answer_element_'.$sv->id.'_'.($key2+1).'"><a href="#" class="answer_options" data-aid="' . ($key2+1) . '"><img class="modal_survey_tooltip" title="' . __( 'Answer Options', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="' . plugins_url( "/assets/img/options.png", __FILE__ ) . '"></a>' . $answer_status . '<a class="modal_survey_tooltip whoansweredthis" title="' . __( 'Who answered this?', MODAL_SURVEY_TEXT_DOMAIN ) . '" href="' . $whoansweredthis . '"><span class="ans_nmo">' . ( $key2 + 1 ) . '.</span> ' . __( 'answer:', MODAL_SURVEY_TEXT_DOMAIN ) . '</a> <input type="text" name="answer[]" data-unique="'.$av->uniqueid.'" class="answer' . $answer_class . '" id="answer'.($key2+1).'" ' . $answer_type . ' style="width: 40%;" value="' . htmlspecialchars( $av->answer ) . '" placeholder="' . __( 'no', MODAL_SURVEY_TEXT_DOMAIN ) . '" /><span id="answer_count'.($key2+1).'" class="answer_count">'.$av->count.' - '.$percentage.'%</span><a class="remove_answer" id="remove_answers_'.$sv->id.'_'.($key2+1).'"><img class="modal_survey_tooltip" title="' . __( 'Remove Answer', MODAL_SURVEY_TEXT_DOMAIN ) . '" src="'.plugins_url( "/assets/img/delete.png" , __FILE__ ).'"></a>' . $answer_options . '</div>');
				}
			}
			print('
		</div>
		</div>
		<div class="right_half" id="chart'.($key+1).'">
		<canvas id="modal_survey_pro_graph_'.$sv->id.'_'.($key+1).'" class="canvas_graph" height="250" width="250"></canvas>
		</div>
		</div>
		</div>');
		}
		print('</div>
		<div style="clear:both;"></div>
		<br><div class="dnone"><a class="nquestion add_question button button-primary button-small">' . __( 'New Question', MODAL_SURVEY_TEXT_DOMAIN ) . '</a></div>
		<div style="clear:both;"></div>
		<div class="dnone">
			<br><span><input type="submit" name="delete_survey" class="delete_survey button" value="' . __( 'DELETE', MODAL_SURVEY_TEXT_DOMAIN ) . '"></span><span><input type="submit" name="save_survey" class="save_survey button" value="' . __( 'UPDATE', MODAL_SURVEY_TEXT_DOMAIN ) . '"></span><span><input type="submit" name="reset_survey" class="reset_survey button modal_survey_tooltip" title="' . __( 'Resetting the survey means all users can vote again.', MODAL_SURVEY_TEXT_DOMAIN ) . '" value="' . __( 'RESET', MODAL_SURVEY_TEXT_DOMAIN ) . '"></span><span class="survey_error_span"></span>
		</div>
	</div>');
				}
				?>
			</div>
		</div>
		<div id="saved-survey-controls">
			<a class="floating-save-button" href="#"><img src="<?php echo plugins_url( '/assets/img/save-icon.png' , __FILE__ ); ?>"><span><?php _e( 'SAVE SURVEY', MODAL_SURVEY_TEXT_DOMAIN ); ?></span></a>
			<a class="floating-new-button" href="#"><img src="<?php echo plugins_url( '/assets/img/new-question-icon.png' , __FILE__ ); ?>"><span><?php _e( 'NEW QUESTION', MODAL_SURVEY_TEXT_DOMAIN ); ?></span></a>
			<a class="floating-play-button" href="#"><img src="<?php echo plugins_url( '/assets/img/play-icon.png' , __FILE__ ); ?>"><span><?php _e( 'PLAY SURVEY', MODAL_SURVEY_TEXT_DOMAIN ); ?></span></a>
			<a class="floating-reset-button" href="#"><img src="<?php echo plugins_url( '/assets/img/reset-icon.png' , __FILE__ ); ?>"><span><?php _e( 'RESET SURVEY', MODAL_SURVEY_TEXT_DOMAIN ); ?></span></a>
			<a class="floating-delete-button" href="#"><img src="<?php echo plugins_url( '/assets/img/trash-icon.png' , __FILE__ ); ?>"><span><?php _e( 'DELETE SURVEY', MODAL_SURVEY_TEXT_DOMAIN ); ?></span></a>
		</div>
<?php }
else
{
				$surveys = $this->wpdb->get_results("SELECT mss.id, DATE_FORMAT(mss.created,'%Y-%m-%d') as created, DATE_FORMAT( STR_TO_DATE( mss.updated, '%Y-%m-%d %H:%i:%s'), '%Y-%m-%d' ) as updated, mss.owner, mss.global as glb, mss.name, IF((`expiry_time`>'".current_time( 'mysql' )."' OR `expiry_time`='0000-00-00 00:00:00'), 'false', 'true') as expired,SUM(msa.count) as sumcount FROM ".$this->wpdb->base_prefix."modal_survey_surveys mss LEFT JOIN ".$this->wpdb->base_prefix."modal_survey_answers msa on mss.id = msa.survey_id GROUP BY mss.id ORDER BY mss.autoid DESC");
				print('<table class="modal-survey-list-table modal-survey-list-table-saved-surveys">
					<thead>
						<tr>
							<th>' . __( 'ID', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Name', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Owner', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Created', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Updated', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Total Votes', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Global', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Expired', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
							<th>' . __( 'Actions', MODAL_SURVEY_TEXT_DOMAIN ) . '</th>
						</tr>
					</thead><tbody>');
				foreach($surveys as $sv)
				{
					if ( ! $sv->owner ) $owner = get_current_user_id();
					else $owner = $sv->owner;
					$owner_user = get_user_by( 'id', $owner );
					print('<tr id="'.$sv->id.'">
					<td><a href="admin.php?page=modal_survey_savedforms&modal_survey_id='.$sv->id.'">'.$sv->id.'</a></td>
					<td><a href="admin.php?page=modal_survey_savedforms&modal_survey_id='.$sv->id.'">'.$sv->name.'</a></td>
					<td>'.$owner_user->display_name.'</td>
					<td>'.$sv->created.'</td>
					<td>'.$sv->updated.'</td>
					<td>'.$sv->sumcount.'</td><td>'.($sv->glb == 1 ? "Yes" : "No").'</td>
					<td>'.($sv->expired == 'false' ? "No" : "Yes").'</td>
					<td>
						<a href="Javascript: void(0);" class="modal_survey_tooltip duplicate_survey" title="' . __( 'Duplicate', MODAL_SURVEY_TEXT_DOMAIN ) . '" data-sid="'.$sv->id.'" data-sname="'.$sv->name.'"><img src="'.plugins_url( '/assets/img/list-duplicate.png' , __FILE__ ).'"></a>
						<a href="admin.php?page=modal_survey_savedforms&modal_survey_id='.$sv->id.'" class="modal_survey_tooltip" title="' . __( 'Edit', MODAL_SURVEY_TEXT_DOMAIN ) . '"><img src="'.plugins_url( '/assets/img/list-edit.png' , __FILE__ ).'"></a>
						<a href="Javascript: void(0);" class="modal_survey_tooltip reset_survey" title="' . __( 'Reset', MODAL_SURVEY_TEXT_DOMAIN ) . '" data-sid="'.$sv->id.'"><img src="'.plugins_url( '/assets/img/list-reset.png' , __FILE__ ).'"></a>
						<a href="Javascript: void(0);" class="modal_survey_tooltip delete_survey" data-sid="'.$sv->id.'" title="' . __( 'Delete', MODAL_SURVEY_TEXT_DOMAIN ) . '"><img src="'.plugins_url( '/assets/img/list-remove.png' , __FILE__ ).'"></a></td></tr>');
				}
				print('</tbody></table>');
}
?>
<div id="dialog-confirm" title="<?php _e( 'Delete Survey', MODAL_SURVEY_TEXT_DOMAIN );?>?">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e( 'These items will be permanently deleted and cannot be recovered. Are you sure?', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>
<div id="dialog-confirm2" title="<?php _e( 'Reset Survey Answers', MODAL_SURVEY_TEXT_DOMAIN );?>?">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e( 'The answer counts will be permanently deleted and cannot be recovered. Are you sure to reset?', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>
<div id="dialog-confirm3" title="<?php _e( 'Export Charts', MODAL_SURVEY_TEXT_DOMAIN );?>?">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e( 'Would you like to export charts to the PDF?', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>

<div id="dialog-confirm4" title="<?php _e( 'Duplicate Survey', MODAL_SURVEY_TEXT_DOMAIN );?>">
  <p class="validateTips"><?php _e( 'Enter the name of the new survey.', MODAL_SURVEY_TEXT_DOMAIN );?></p>
  <form>
    <fieldset>
      <input type="text" name="dsurvey_name" id="dsurvey_name" value="" class="text ui-widget-content ui-corner-all">
	  <p><input type="checkbox" name="keep_votes" id="keep_votes" value="0"> <label for="keep_votes"><?php _e( 'Copy Votes', MODAL_SURVEY_TEXT_DOMAIN );?></label></p>
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
  <span class="duplicate-notice"> <?php _e( 'This survey name is already exist!', MODAL_SURVEY_TEXT_DOMAIN );?></span>
</div>
<div id="dialog-confirm10" title="<?php _e( 'Delete Condition', MODAL_SURVEY_TEXT_DOMAIN );?>?">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e( 'The condition will be permanently deleted and cannot be recovered. Are you sure to remove?', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>
<div id="dialog-help1" class="help-dialogs" title="<?php _e( 'Embed Mode', MODAL_SURVEY_TEXT_DOMAIN );?>">
	<p><?php _e( 'If you would like to Embed the Survey on any page / post, please use the following shortcode: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[modalsurvey id="' . $sv->id . '" style="flat"]</p>' ); ?>
	<p><?php _e( 'Initialize the Survey in Safe Mode in case it does not displayed: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[modalsurvey id="' . $sv->id . '" style="flat" init="true"]</p>' ); ?>
	<p><?php _e( 'Please do not forget to disable the Global Survey checkbox in the General Behavior to avoid duplicated display. ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>
<div id="dialog-help2" class="help-dialogs" title="<?php _e( 'Modal Mode', MODAL_SURVEY_TEXT_DOMAIN );?>">
	<p><?php _e( 'If you would like to display the Survey on a specific page / post, please include the following shortcode: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[modalsurvey id="' . $sv->id . '"]</p>' ); ?>
	<p><?php _e( 'Initialize the Survey in Safe Mode in case it does not displayed: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[modalsurvey id="' . $sv->id . '" init="true"]</p>' ); ?>
	<p><?php _e( 'Please do not forget to disable the Global Survey checkbox in the General Behavior to avoid duplicated display. ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>
<div id="dialog-help3" class="help-dialogs" title="<?php _e( 'Display the Results', MODAL_SURVEY_TEXT_DOMAIN );?>">
	<p><?php _e( 'Please add the following shortcode to display the results with Progress Bar on a specified page or post: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[survey_answers id="' . $sv->id . '"]</p>' ); ?>
	<p><?php _e( 'Display the results with Bar Chart in safe mode: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[survey_answers id="' . $sv->id . '" style="barchart" init="true"]</p>' ); ?>
	<p><?php _e( 'Display the results with Bar Chart based on the scores: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
	<?php print( '<p class="code">[survey_answers id="' . $sv->id . '" data="score" style="barchart" init="true"]</p>' ); ?>
	<p><?php _e( 'For the full list of available chart and bars, please click here: ', MODAL_SURVEY_TEXT_DOMAIN );?><a href="http://modalsurvey.pantherius.com/documentation/#line11">Shortcodes</a></p>
</div>
<div id="dialog-help4" class="help-dialogs" title="<?php _e( 'Add New Answer', MODAL_SURVEY_TEXT_DOMAIN );?>">
	<p><?php _e( 'Select the type of the answer below: ', MODAL_SURVEY_TEXT_DOMAIN );?></p>
</div>
<div id="dialog-message">
	<p class="icon"><img src="<?php print( plugins_url( '/assets/img/info-icon.png', __FILE__ ) ); ?>"></p>
	<p class="content"></p>
</div>
</div>