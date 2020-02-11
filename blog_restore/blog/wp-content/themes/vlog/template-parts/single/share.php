<?php $share_items = vlog_get_social_share(); ?>

<?php if ( !empty( $share_items ) ) : ?>

	<div class="vlog-share-single">
		<?php foreach ( $share_items as $item ): ?>
			<?php echo $item; ?>
		<?php endforeach; ?>
	</div>

<?php endif; ?>