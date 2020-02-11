<div id="screen_preloader" style="position: absolute;width: 100%;height: 1000px;z-index: 9999;text-align: center;background: #fff;padding-top: 200px;"><h3>Modal Survey for WordPress</h3><img src="<?php print(plugins_url( '/assets/img/screen_preloader.gif' , __FILE__ ));?>"><h5><?php _e( 'LOADING', MODAL_SURVEY_TEXT_DOMAIN );?><br><br><?php _e( 'Please wait...', MODAL_SURVEY_TEXT_DOMAIN );?></h5></div>
<div class="wrap pantherius-jquery-ui wrap-padding" style="visibility:hidden">
<br />
<div class="title-border">
	<h3><?php _e( 'Create New Survey', MODAL_SURVEY_TEXT_DOMAIN );?></h3>
	<div class="help_link"><a target="_blank" href="http://modalsurvey.pantherius.com/documentation/#line2"><?php _e( 'Documentation', MODAL_SURVEY_TEXT_DOMAIN );?></a></div>
</div>
<?php
	global $wpdb;
	if ( isset( $_REQUEST[ 'modal-survey-import-nonce' ] ) || ( isset( $_REQUEST[ 'import_id' ] ) ) ) {
		if ( isset( $_REQUEST[ 'import_id' ] ) ) {
			$import_id = $_REQUEST[ 'import_id' ];
			switch ( $import_id ) {
				case 'simple':
					$survey_name = "Simple Survey Sample";
					break;
				case 'simple-quiz':
					$survey_name = "Simple Quiz Sample";
					break;
				case 'simple-rating':
					$survey_name = "Simple Rating Sample";
					break;
				case 'redirection':
					$survey_name = "Redirection Sample";
					break;
				case 'open-text-answer':
					$survey_name = "Open Text Answer Sample";
					break;
				case 'customer-satisfaction':
					$survey_name = "Customer Satisfaction Sample";
					break;
				case 'personality-test':
					$survey_name = "Personality Test Sample";
					break;
				case 'trivia':
					$survey_name = "Trivia Quiz Sample";
					break;
				case 'product':
					$survey_name = "Product Recommendation Sample";
					break;
			}
			if ( file_exists( plugin_dir_path( __FILE__ ) . 'import/' . $import_id . '.json' ) ) {
				$json_content = json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . 'import/' . $import_id . '.json' ) );
				$survey_id = $this->mshashCode( $survey_name );
				$checksurvey = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) AS COUNT FROM ".$wpdb->base_prefix."modal_survey_surveys WHERE `id` = %d", $survey_id ) );
				if ( isset( $checksurvey[ 'COUNT' ] ) ) {
					if ( $checksurvey[ 'COUNT' ] > 0 ) {
						$ierror = __( 'Survey name is already exists. Import failed.', MODAL_SURVEY_TEXT_DOMAIN );
					}
				}
				if ( $json_content == "" || ! isset( $json_content->questions ) ) {
					$ierror = __( 'File empty or not valid JSON file. Import failed.', MODAL_SURVEY_TEXT_DOMAIN );
				}
				if ( ! isset( $ierror ) ) {
					$wpdb->insert( $wpdb->base_prefix."modal_survey_surveys", array( 
					'id' => $survey_id, 
					'name' => $survey_name, 
					'options' => $json_content->options, 
					'start_time' => $json_content->start_time,
					'expiry_time'=> $json_content->expiry_time,
					'created'=> date("Y-m-d H:i:s"),
					'updated'=> date("Y-m-d H:i:s"),
					'owner'=> get_current_user_id(),
					'global'=> $json_content->global
					) );
					$c = 0;
					foreach ( $json_content->questions as $jcq ) {
								$wpdb->insert( $wpdb->base_prefix."modal_survey_questions", array( 
									'id' => ($c+1), 
									'survey_id' => $survey_id, 
									'question' => $jcq->name,
									'qoptions' => $jcq->qoptions
									) );
									$qid = $wpdb->insert_id;
								foreach ( $jcq as $keya => $jca ) {
					if ( isset( $jca->answer ) ) {
						$uid = (floor(rand(1000000,9999999) * 1000000 + microtime()));
						$aopts = unserialize( $jca->aoptions );
						$aopts[1] = $uid;
								$wpdb->insert( $wpdb->base_prefix."modal_survey_answers", array( 
									'survey_id' => $survey_id, 
									'question_id' => ($c+1),
									'answer' => $jca->answer,
									'count' => 0,
									'autoid' => $keya,
									'aoptions' => serialize( $aopts ),
									'uniqueid' => $uid
									) );					
								}
							}
						$c++;
					}
					print( '<div class="updated"><p>' );
					_e( 'Survey successfully imported, redirecting to the survey edit screen in 5 seconds.', MODAL_SURVEY_TEXT_DOMAIN );
					print( ' <a href="' . admin_url( "admin.php?page=modal_survey_savedforms&modal_survey_id=$survey_id" ) . '">' . __( 'Edit', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
					print( '</p></div>' );
					print( '<script type="text/JavaScript">setTimeout(function(){ window.location.href = "' . admin_url("admin.php?page=modal_survey_savedforms&modal_survey_id=$survey_id" ) . '"}, 5000 );</script>' );
				}
				else {
					print( '<div class="error"><p>' );
					print( $ierror );
					print( '</p></div>' );					
				}
			}
			else {
				print( '<div class="error"><p>' );
				_e( 'Missing example import file, please check the import folder in the plugin templates directory.', MODAL_SURVEY_TEXT_DOMAIN );
				print( '<br>' . plugin_dir_path( __FILE__ ) . 'import/' . $import_id . '.json' );
				print( '</p></div>' );
			}
		}
	}
	$samples_array = array( "Simple Survey Sample", "Simple Quiz Sample", "Simple Rating Sample", "Redirection Sample", "Open Text Answer Sample", "Customer Satisfaction Sample", "Personality Test Sample", "Trivia Quiz Sample", "Product Recommendation Sample" );
	$samples = join( "','", $samples_array );
	$surveyids = $wpdb->get_results( "SELECT id, name FROM " . $wpdb->base_prefix . "modal_survey_surveys WHERE name in ( '" . $samples . "' ) " );
	$ssids = array();
	foreach( $surveyids as $sids ) {
		$ssids[ $sids->id ] = $sids->name;
	}
?>
	<div id="modal_survey_settings">
		<input type="text" id="survey_name" value="" size="50" placeholder="<?php _e( 'Type the survey name here', MODAL_SURVEY_TEXT_DOMAIN );?>" /><span id="button-container"><a id="add_new_survey" class="button button-secondary button-small"><?php _e( 'New Survey', MODAL_SURVEY_TEXT_DOMAIN );?></a></span><span id="error_log"></span>
	</div>
	<div class="demo-template demo-bg1">
		<div class="demo-title">Simple Survey</div>
		<div class="demo-desc"><?php _e( 'Demonstrating the basic features through a basic survey with two questions.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Simple Survey Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=simple" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg2">
		<div class="demo-title">Simple Quiz</div>
		<div class="demo-desc"><?php _e( 'Select the correct images for 5 questions. The number of correct answers displayed at the end.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Simple Quiz Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=simple-quiz" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg3">
		<div class="demo-title">Simple Rating</div>
		<div class="demo-desc"><?php _e( 'Example of how to create rating questions with stars.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Simple Rating Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=simple-rating" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg4">
		<div class="demo-title">Redirection</div>
		<div class="demo-desc"><?php _e( 'Score based one question survey with browser redirection condition at the end, based on the final score.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Redirection Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=redirection" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg5">
		<div class="demo-title">Open Text Answer</div>
		<div class="demo-desc"><?php _e( 'Customer Satisfaction survey with 3 questions allowing custom text answers in each questions.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Open Text Answer Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=open-text-answer" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg6">
		<div class="demo-title">Customer Satisfaction</div>
		<div class="demo-desc"><?php _e( 'Review style Customer Satisfaction survey with star ratings and individual chart at the end.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Customer Satisfaction Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=customer-satisfaction" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg7">
		<div class="demo-title">Personality Test</div>
		<div class="demo-desc"><?php _e( 'Provides 9 questions with categories and scores assigned. Display specific text and individual chart at the end of the test.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Personality Test Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=personality-test" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg8">
		<div class="demo-title">Trivia Quiz</div>
		<div class="demo-desc"><?php _e( 'Answers with images assigned to categories to find out: Which Ancient God Are You? Optionally sharing the result on Facebook.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Trivia Quiz Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=trivia" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
	<div class="demo-template demo-bg9">
		<div class="demo-title">Product Recommendation</div>
		<div class="demo-desc"><?php _e( 'Choose the best shoes that fits for you in 3 questions with multiple categories assigned.', MODAL_SURVEY_TEXT_DOMAIN );?></div>
		<div class="demo-install">
		<?php
			if ( in_array( 'Product Recommendation Sample', $ssids ) ) {
				print( '<a class="button button-secondary button-large" disabled="true" href="JavaScript: void()">' . __( 'INSTALLED', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
			else {
				print( '<a class="button button-secondary button-large" href="' . admin_url( "admin.php?page=modal_survey&import_id=product" ) . '">' . __( 'INSTALL SAMPLE', MODAL_SURVEY_TEXT_DOMAIN ) . '</a>' );
			}
		?>
		</div>
	</div>
</div>