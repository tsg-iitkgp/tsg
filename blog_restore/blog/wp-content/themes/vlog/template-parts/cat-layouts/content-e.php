<article <?php post_class('vlog-lay-e vlog-cat col-lg-4 col-sm-4 col-md-4 col-xs-12'); ?>>
	
	<?php if( $fimg = vlog_get_featured_image('vlog-lay-e') ) : ?>
    <div class="entry-image">
	    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" title="<?php echo esc_attr( $cat->name ); ?>">
	       	<?php echo $fimg; ?>
	       	<?php if($module['display_icon']): ?>
	        <span class="vlog-format-action small"><i class="fa fa-play"></i></span>
	        <?php endif; ?>
	    </a>
    </div>
	<?php endif; ?>

	<div class="entry-header">
	    <h2 class="entry-title h5"><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html($cat->name); ?></a></h2>
	</div>
	    
	<?php if($module['display_count']): ?>
	       <div class="entry-meta"><span class="meta-item"><span class="vlog-count"><?php echo esc_html( $cat->count ); ?></span><?php echo esc_html($module['count_label']); ?></span></div>
	<?php endif; ?>
    
</article>