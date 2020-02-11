<div id="screen_preloader" style="position: absolute;width: 100%;height: 1000px;z-index: 9999;text-align: center;background: #fff;padding-top: 200px;"><h3>Modal Survey for WordPress</h3><img src="<?php print(plugins_url( '/assets/img/screen_preloader.gif' , __FILE__ ));?>"><h5><?php _e( 'LOADING', MODAL_SURVEY_TEXT_DOMAIN );?><br><br><?php _e( 'Please wait...', MODAL_SURVEY_TEXT_DOMAIN );?></h5></div>
<div class="wrap pantherius-jquery-ui wrap-padding" style="visibility:hidden">
<br />
<div class="title-border">
	<h3><?php _e( 'Help', MODAL_SURVEY_TEXT_DOMAIN );?></h3>
</div>
	<form method="post" class="ee-form" action="options.php">
		<p>
			<?php _e( 'To see the full documentation, please click on the following link:', MODAL_SURVEY_TEXT_DOMAIN );?> <a target="_blank" href="http://modalsurvey.pantherius.com/documentation"><?php _e( 'Documentation', MODAL_SURVEY_TEXT_DOMAIN );?></a>
		</p>
		<p>    
		<?php print(file_get_contents("http://static.pantherius.com/plugin_directory.html")); ?>
		</p>
	</form>
</div>