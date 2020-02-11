	<div id="screen_preloader" style="position: absolute;width: 100%;height: 1000px;z-index: 9999;text-align: center;background: #fff;padding-top: 200px;"><h3>Modal Survey for WordPress</h3><img src="<?php print(plugins_url( '/assets/img/screen_preloader.gif' , __FILE__ ));?>"><h5><?php _e( 'LOADING', MODAL_SURVEY_TEXT_DOMAIN );?><br><br><?php _e( 'Please wait...', MODAL_SURVEY_TEXT_DOMAIN );?></h5></div>
	<div class="wrap pantherius-jquery-ui wrap-padding" style="visibility:hidden">
	<br />
	<div class="title-border">
		<h3><?php _e( 'Update', MODAL_SURVEY_TEXT_DOMAIN );?></h3>
		<div class="help_link"><a target="_blank" href="http://modalsurvey.pantherius.com/documentation/#line7"><?php _e( 'Documentation', MODAL_SURVEY_TEXT_DOMAIN );?></a></div>
	</div>
	<?php 
	if ( isset( $_REQUEST[ 'ms_update_db' ] ) ) {
		if ( $this->update_modal_survey_db() ) {
			print( '<br>' . __( 'Database updated successfully', MODAL_SURVEY_TEXT_DOMAIN ) );
		}
		else {
			print( '<br>' . __( 'Database already updated', MODAL_SURVEY_TEXT_DOMAIN ) );		
		}
	}
		require_once(str_replace('templates','',sprintf("%s/modules/manual.update.php", dirname(__FILE__))));
		manual_plugin_updater::getInstance(
		'modal_survey/modal_survey.php',
		'modal_survey/modal_survey.php',
		array(),
		'modal_survey'
		);

		$ms_db_version = get_option( 'setting_db_modal_survey' );
		 print( '<p>Current Plugin Version: ' . MODAL_SURVEY_VERSION . '</p>' );
		 print( '<p>Plugin Database Version: ' . ( ! empty( $ms_db_version ) ? $ms_db_version : 'Unknown' ) );
		 print( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . admin_url( 'admin.php?page=modal_survey_update&ms_update_db=true' ) . '">Update DB</a>' );
		 print( '</p>' );
	?>
	</div>