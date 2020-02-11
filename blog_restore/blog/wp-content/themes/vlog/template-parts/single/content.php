<div class="entry-content-single">
	
	<?php if( strpos( vlog_get_option( 'single_share'), 'above' ) !== false ) : ?>
		<?php get_template_part( 'template-parts/single/share' ); ?>
	<?php endif; ?>

	<?php get_template_part('template-parts/ads/above-single'); ?>

	<?php if( vlog_get_option( 'single_headline' ) && has_excerpt() ): ?>
		<div class="entry-headline h4"><?php echo get_the_excerpt(); ?></div>
	<?php endif; ?>

	<?php the_content(); ?>

	<?php if( vlog_is_paginated_post() ) : ?>
		<?php get_template_part( 'template-parts/single/paginated-nav' ); ?>
	<?php endif; ?>

	<?php if( vlog_get_option('single_tags') && has_tag() ) : ?>
		<div class="meta-tags">
			<?php the_tags( false, ' ', false ); ?>
		</div>
	<?php endif; ?>

	<?php if( strpos( vlog_get_option( 'single_share'), 'below' ) !== false ) : ?>
		<?php get_template_part( 'template-parts/single/share' ); ?>
	<?php endif; ?>

</div>