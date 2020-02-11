<article <?php post_class('vlog-lay-f lay-horizontal vlog-post col-lg-4 col-sm-4 col-md-4 col-xs-12'); ?>>
    <div class="row">

        <div class="col-lg-5 col-md-5 col-xs-5">
            <?php if( $fimg = vlog_get_featured_image('vlog-lay-f') ) : ?>
                <div class="entry-image">
                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                   	<?php echo $fimg; ?>
                </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-7 col-md-7 col-xs-7 no-left-padding">
            
            <div class="entry-header">

                <?php if( vlog_get_option( 'lay_f_cat' ) ) : ?>
                    <span class="entry-category"><?php echo vlog_get_category(); ?></span>
                <?php endif; ?>

                <?php the_title( sprintf( '<h2 class="entry-title h6"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

                <?php if( $meta = vlog_get_meta_data( 'f' ) ) : ?>
                    <div class="entry-meta"><?php echo $meta; ?></div>
                <?php endif; ?>

            </div>


        </div>
    </div>
</article>