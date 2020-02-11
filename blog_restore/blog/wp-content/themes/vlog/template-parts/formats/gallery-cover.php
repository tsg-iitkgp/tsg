<a class="vlog-cover" href="javascript: void(0);" data-action="gallery">
		 <?php echo vlog_get_featured_image('vlog-cover-full', false, false, true ); ?>
		 <?php echo vlog_post_format_action( 'large' ); ?>
</a>

<?php if ( $gallery = hybrid_media_grabber( array( 'type' => 'gallery', 'split_media' => true ) ) ): ?>
		<div class="vlog-format-content" style="display:none;"><?php echo $gallery; ?></div>
<?php endif; ?>