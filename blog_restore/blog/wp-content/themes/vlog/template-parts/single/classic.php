<div class="entry-header">

     <?php if( vlog_get_option( 'single_cat' ) ) : ?>
		<span class="entry-category"><?php echo vlog_get_category(); ?></span>
	<?php endif; ?>

    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    
  <?php if( $meta = vlog_get_meta_data( 'single' ) ) : ?>
	<div class="entry-meta"><?php echo $meta; ?></div>
  <?php endif; ?>

</div>

<?php get_template_part( 'template-parts/formats/' . vlog_get_post_format( true ) . '-classic' ); ?>