<?php $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
<div class="entry-image vlog-single-entry-image">
	<a class="vlog-cover" href="javascript: void(0);" data-action="image" data-image="<?php echo esc_url( $full_img[0] ); ?>">
			 <?php echo vlog_get_featured_image('vlog-lay-a', false, false, true ); ?>
			 <?php echo vlog_post_format_action( 'large' ); ?>
	</a>
	<?php if( vlog_get_option( 'single_fimg_cap' ) && $caption = get_post( get_post_thumbnail_id())->post_excerpt) : ?>
			<figure class="wp-caption-text"><?php echo $caption;  ?></figure>
	<?php endif; ?>
</div>