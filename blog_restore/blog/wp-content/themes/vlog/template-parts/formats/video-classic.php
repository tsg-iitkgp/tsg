<?php if ( $video = hybrid_media_grabber( array( 'type' => 'video', 'split_media' => true ) ) ): ?>
		<div class="vlog-featured-item">
		<div class="entry-media"><div class="vlog-format-content"><div class="vlog-popup-wrapper"><?php echo $video; ?></div></div></div>
		<?php if( $actions = vlog_get_meta_actions( 'single' ) ) : ?>
		<div class="vlog-highlight">
			<div class="entry-actions">
				<?php echo $actions; ?>
			</div>
		</div>
		
		<?php endif; ?>
		</div>
<?php endif; ?>