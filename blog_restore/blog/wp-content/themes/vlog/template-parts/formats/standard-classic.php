<?php if( vlog_get_option('single_fimg')  && !vlog_is_paginated_post()): ?>
	<?php if( $fimg =  vlog_get_featured_image('vlog-lay-a', false, true, true ) ) : ?>
		<div class="entry-image vlog-single-entry-image">
			<?php echo $fimg; ?>
			<?php if( vlog_get_option( 'single_fimg_cap' ) && $caption = get_post( get_post_thumbnail_id())->post_excerpt) : ?>
				<figure class="wp-caption-text"><?php echo $caption;  ?></figure>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>