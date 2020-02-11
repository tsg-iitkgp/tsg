<?php $slider_class = isset($fa->post_count) && $fa->post_count > 1 ? 'vlog-featured-slider' : ''; ?>
<div class="vlog-featured-3 <?php echo esc_attr($slider_class); ?>">

	<?php if($fa->have_posts()): ?>

		<?php while( $fa->have_posts()): $fa->the_post(); ?>

			<div class="vlog-featured-item">
			
				<div class="vlog-cover-bg">
						<a class="vlog-cover" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
						 <?php echo vlog_get_featured_image('vlog-cover-full', false, false, true ); ?>
						</a>
				</div>
			
				<div class="vlog-featured-info-3 vlog-active-hover vlog-f-hide">	

				<div class="vlog-vertical-center">						
					
					<div class="entry-header">

		                 <?php if( $labels = vlog_labels('fa3', 'large') ) : ?>
                        	<?php echo $labels; ?>
                    	 <?php endif; ?>


		                <?php the_title( sprintf( '<h2 class="entry-title h1"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					                
			            <?php if( $meta = vlog_get_meta_data( 'fa3' ) ) : ?>
    						<div class="entry-meta"><?php echo $meta; ?></div>
				  		<?php endif; ?>

		             </div>	

				</div>


				</div>


			</div>

		<?php endwhile; ?>

	<?php endif; ?>

</div>