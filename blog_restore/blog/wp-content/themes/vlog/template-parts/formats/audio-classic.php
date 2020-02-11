
<?php $fimg =  vlog_get_featured_image('vlog-lay-a', false, true, true ); ?>

<?php if($fimg): ?>
<div class="entry-image vlog-single-entry-image">
	
	<?php echo $fimg; ?>

<?php endif; ?>

<?php if ( $audio = hybrid_media_grabber( array( 'type' => 'audio', 'split_media' => true ) ) ): ?>
		<div class="entry-media"><?php echo $audio; ?></div>
<?php endif; ?>

<?php if($fimg): ?>
</div>
<?php endif; ?>
