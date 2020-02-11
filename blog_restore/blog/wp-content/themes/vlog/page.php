<?php get_header(); ?>

<?php get_template_part('template-parts/ads/below-header'); ?>

<?php global $vlog_sidebar_opts; ?>
<?php $section_class = $vlog_sidebar_opts['use_sidebar'] == 'none' ? 'vlog-single-no-sid' : '' ?>

<div class="vlog-section <?php echo esc_attr( $section_class ); ?>">

	<div class="container">

			<?php if( $vlog_sidebar_opts['use_sidebar'] == 'left' ): ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>

			<div class="vlog-content">

				<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
						<?php echo $breadcrumbs; ?>
				<?php endif; ?>
				
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
						
						<?php if( vlog_get_option('page_fimg') && ($fimg = vlog_get_featured_image('vlog-lay-a', false, true, true )) ): ?>
							<div class="entry-image vlog-entry-image-page">
	               				<?php echo $fimg; ?>
	               				<?php if( vlog_get_option( 'page_fimg_cap' ) && $caption = get_post( get_post_thumbnail_id())->post_excerpt) : ?>
									<figure class="wp-caption-text"><?php echo $caption;  ?></figure>
								<?php endif; ?>
				            </div>
			            <?php endif; ?>


						<?php the_title( '<h1 class="entry-title vlog-page-title">', '</h1>' ); ?>

						<div class="entry-content entry-content-single">
							<?php the_content(); ?>
						</div>

					</article>

					<?php if( vlog_get_option('page_comments') ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>

				<?php endwhile; ?>

			</div>

			<?php if( $vlog_sidebar_opts['use_sidebar'] == 'right' ): ?>
					<?php get_sidebar(); ?>
			<?php endif; ?>

	</div>

</div>

<?php get_footer(); ?>