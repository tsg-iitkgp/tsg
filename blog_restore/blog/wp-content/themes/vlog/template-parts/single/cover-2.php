<div class="vlog-featured-2 vlog-featured vlog-single-cover">


			<div class="vlog-featured-item">
			
				<?php $format = vlog_get_post_format( true ); ?>
				
				<div class="vlog-cover-bg <?php echo esc_attr($format); ?>">
					<?php get_template_part( 'template-parts/formats/' . $format . '-cover' ); ?>
					<?php get_template_part( 'template-parts/single/prev-next-cover'); ?>
				</div>

				<?php if( ($format != 'video') || ( $format == 'video' && !vlog_get_option('open_videos_inplay') ) ) : ?>

					<div class="vlog-featured-info-2 container vlog-pe-n vlog-active-hover vlog-f-hide">						
		
						<div class="vlog-fa-item">
							<div class="entry-header vlog-pe-a">

				                
				                <?php if( vlog_get_option( 'single_cat' ) ) : ?>
	                				<span class="entry-category"><?php echo vlog_get_category(); ?></span>
	            				<?php endif; ?>


				                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							                
					            <?php if( $meta = vlog_get_meta_data( 'single' ) ) : ?>
		    						<div class="entry-meta"><?php echo $meta; ?></div>
						  		<?php endif; ?>

				             </div>	
			             </div>

			            
			             <?php if( $actions = vlog_get_meta_actions('single') ) : ?>
			             	<div class="vlog-fa-item">
							   <div class="entry-actions vlog-pe-a"><?php echo $actions; ?></div>
							</div>  
						 <?php endif; ?> 

					</div>

				<?php endif; ?>

				<?php get_template_part( 'template-parts/single/cover-inplay'); ?>

			</div>


</div>