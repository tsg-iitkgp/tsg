<a class="vlog-cover" href="javascript: void(0);" data-action="audio" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
		 <?php echo vlog_get_featured_image('vlog-cover-full', false, false, true ); ?>
		 <?php echo vlog_post_format_action( 'large' ); ?>
</a>

<?php if ( $audio = hybrid_media_grabber( array( 'type' => 'audio', 'split_media' => true ) ) ): ?>
		<div class="vlog-format-content"></div>
<?php endif; ?>