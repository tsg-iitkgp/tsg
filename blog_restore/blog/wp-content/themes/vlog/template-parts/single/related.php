<?php $related = vlog_get_related_posts(); ?>

<?php if( $related->have_posts() ) : ?>

	<div id="vlog-related" class="vlog-related-wrapper">	
		<div class="row">
		    <div class="vlog-module module-posts col-lg-12">
		        
		        <?php echo vlog_module_heading(array('title' => '<h4>'.__vlog('related').'</h4>')); ?>

			    <div class="row vlog-posts row-eq-height">
			    	<?php while ( $related->have_posts() ) : $related->the_post(); ?>
			     		<?php get_template_part( 'template-parts/layouts/content-' . vlog_get_option( 'related_layout' ) ); ?>
			     	<?php endwhile; ?>
			    </div>

			</div>
		</div>
	</div>

<?php endif; ?>

<?php wp_reset_postdata(); ?>