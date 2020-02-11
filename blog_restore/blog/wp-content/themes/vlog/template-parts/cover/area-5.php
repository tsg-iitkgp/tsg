<div class="vlog-featured-5">

<div class="vlog-fa-5-wrapper">

	<?php if($fa->have_posts()): ?>

		<?php $i = 0; while( $fa->have_posts()): $fa->the_post(); $i++; ?>

    <div class="fa-item">
      <a class="fa-item-image" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
         <?php $img_size = $i == 1 ? 'vlog-cover-medium' : 'vlog-cover-small'; ?>
         <?php $format_label_size = $i == 1 ? 'large' : 'x-small'; ?>
      	 <?php echo vlog_get_featured_image( $img_size , false, false, true ); ?>
      </a>
      <?php if( $labels = vlog_labels('fa5', $format_label_size) ) : ?>
            <?php echo $labels; ?>
      <?php endif; ?>

      <div class="fa-inner">


         <?php if( vlog_get_option( 'lay_fa5_cat' ) ) : ?>
                  <span class="entry-category"><?php echo vlog_get_category(); ?></span>
         <?php endif; ?>

    		<?php the_title( sprintf( '<h2 class="entry-title h5"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            
            <?php if( $meta = vlog_get_meta_data( 'fa5' ) ) : ?>
				      <div class="entry-meta"><?php echo $meta; ?></div>
	  		   <?php endif; ?>

      </div>

  	</div>
		
		<?php endwhile; ?>

	<?php endif; ?>


</div>

</div>