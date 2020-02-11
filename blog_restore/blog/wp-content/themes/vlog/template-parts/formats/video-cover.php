
<?php if( (!vlog_get_option('open_videos_inplay') && is_single()) || (!is_single()) ):?>
	<a class="vlog-cover" href="javascript: void(0);" data-action="video" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
			 <?php echo vlog_get_featured_image('vlog-cover-full', false, false, true ); ?>
			 <?php echo vlog_post_format_action( 'large' ); ?>
	</a>
<?php endif; ?>

<?php if ( $video = hybrid_media_grabber( array( 'type' => 'video', 'split_media' => true ) ) ): ?>
		<div class="vlog-format-content"><?php if(vlog_get_option('open_videos_inplay') && is_single() ) { echo '<div class="vlog-popup-wrapper">'.$video.'</div>'; } ?></div>
<?php endif; ?>