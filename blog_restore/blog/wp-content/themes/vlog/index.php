<?php get_header(); ?>

<?php $fa = vlog_get_featured_area(); ?>

<?php if( !empty( $fa ) ) : ?>
	<?php $fa_layout =  $fa['layout']; ?>
	<?php $fa = $fa['query']; ?>
    <?php include( locate_template('template-parts/cover/area-' . absint( $fa_layout ) . '.php') ); ?>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php get_template_part('template-parts/ads/below-header'); ?>

<?php global $vlog_sidebar_opts; ?>
<?php $section_class = $vlog_sidebar_opts['use_sidebar'] == 'none' ? 'vlog-no-sid' : '' ?>

<div class="vlog-section <?php echo esc_attr($section_class); ?>">
    <div class="container">
        
        <?php if( $vlog_sidebar_opts['use_sidebar'] == 'left' ): ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>

        <div class="vlog-content">
            <div class="row">
                <div class="vlog-module module-posts col-lg-12">
                	
                	<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
							<?php echo $breadcrumbs; ?>
					<?php endif; ?>

				    <?php echo vlog_get_archive_heading(); ?>

				    <div class="row vlog-posts row-eq-height vlog-posts">
				    	
				    	<?php if( have_posts() ): ?>
							
							<?php $ad_position = vlog_get_option('ad_between_posts') ? absint( vlog_get_option('ad_between_posts_position') - 1 ) : false ; ?>

							<?php $i = 0; while ( have_posts() ) : the_post(); ?>
							
								<?php get_template_part( 'template-parts/layouts/content',  vlog_get_current_post_layout( $i ) ); ?>
								<?php if( $i === $ad_position ) { get_template_part('template-parts/ads/between-posts'); } ?>
							
							<?php $i++; endwhile; ?>
						
						<?php else : ?>
							
							<?php get_template_part('template-parts/layouts/content-none' ); ?>
						
						<?php endif;?>

				    </div>

				    <?php if( $pagination = vlog_get_current_pagination() ) : ?>
						<?php get_template_part( 'template-parts/pagination/' . $pagination ); ?>
					<?php endif; ?>

				</div>
            </div>
        </div>

        <?php if( $vlog_sidebar_opts['use_sidebar'] == 'right' ): ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>