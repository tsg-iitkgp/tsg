<article <?php post_class('vlog-lay-g vlog-post col-lg-3 col-md-3 col-sm-3 col-xs-6'); ?>>
	
	<?php if( $fimg = vlog_get_featured_image('vlog-lay-g') ) : ?>
	    <div class="entry-image">
		    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
		       	<?php echo $fimg; ?>
		        <?php if( $labels = vlog_labels('g', 'x-small') ) : ?>
                        <?php echo $labels; ?>
                <?php endif; ?>
		    </a>
	    </div>
	<?php endif; ?>

	<div class="entry-header">

	    <?php if( vlog_get_option( 'lay_g_cat' ) ) : ?>
	        <span class="entry-category"><?php echo vlog_get_category(); ?></span>
	    <?php endif; ?>

	    <?php the_title( sprintf( '<h2 class="entry-title h6"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

	</div>
	    
	<?php if( $meta = vlog_get_meta_data( 'g' ) ) : ?>
	    <div class="entry-meta"><?php echo $meta; ?></div>
	<?php endif; ?>


</article>