<article <?php post_class('vlog-lay-e vlog-post col-lg-4  col-sm-4 col-md-4  col-xs-12'); ?>>
	
	<?php if( $fimg = vlog_get_featured_image('vlog-lay-e') ) : ?>
    <div class="entry-image">
    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
       	<?php echo $fimg; ?>
       	<?php if( $labels = vlog_labels('e', 'small') ) : ?>
              <?php echo $labels; ?>
        <?php endif; ?>
    </a>
    </div>
	<?php endif; ?>

	<div class="entry-header">

	    <?php if( vlog_get_option( 'lay_e_cat' ) ) : ?>
	        <span class="entry-category"><?php echo vlog_get_category(); ?></span>
	    <?php endif; ?>

	    <?php the_title( sprintf( '<h2 class="entry-title h5"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

	</div>
	    
	<?php if( $meta = vlog_get_meta_data( 'e' ) ) : ?>
	    <div class="entry-meta"><?php echo $meta; ?></div>
	<?php endif; ?>


	<?php if( vlog_get_option('lay_e_excerpt') ) : ?>
	    <div class="entry-content">
	        <?php echo vlog_get_excerpt( 'e' ); ?>
	    </div>
	<?php endif; ?>
    

</article>