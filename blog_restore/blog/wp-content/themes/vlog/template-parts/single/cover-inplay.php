<div class="vlog-format-inplay vlog-bg">
	<div class="container">
		
		<?php if( vlog_get_option('open_videos_inplay') && vlog_get_post_format() == 'video' ) : ?>
			
			<div class="entry-header">

                 <?php if( vlog_get_option( 'single_cat' ) ) : ?>
    				<span class="entry-category"><?php echo vlog_get_category(); ?></span>
				<?php endif; ?>

            	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            
	          <?php if( $meta = vlog_get_meta_data( 'single' ) ) : ?>
				<div class="entry-meta"><?php echo $meta; ?></div>
			  <?php endif; ?>

         	</div>

         <?php if( $actions = vlog_get_meta_actions( 'single' ) ) : ?>
             <div class="entry-actions vlog-vcenter-actions">
             	<?php echo $actions; ?>
             </div>
         <?php endif; ?>

 	   <?php endif; ?>


	</div>
</div>