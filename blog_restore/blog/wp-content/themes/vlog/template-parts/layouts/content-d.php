<article <?php post_class('vlog-lay-d lay-horizontal vlog-post col-lg-6 col-sm-6 col-md-6 col-xs-12'); ?>>
    <div class="row">

        <div class="col-lg-6 col-sm-6 col-xs-6">
            <?php if( $fimg = vlog_get_featured_image('vlog-lay-d') ) : ?>
                <div class="entry-image">
                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                   	<?php echo $fimg; ?>
                   <?php if( $labels = vlog_labels('d', 'x-small') ) : ?>
                        <?php echo $labels; ?>
                    <?php endif; ?>
                </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-6 col-sm-6 col-xs-6 no-left-padding">
            
            <div class="entry-header">

                <?php if( vlog_get_option( 'lay_d_cat' ) ) : ?>
                    <span class="entry-category"><?php echo vlog_get_category(); ?></span>
                <?php endif; ?>

                <?php the_title( sprintf( '<h2 class="entry-title h5"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

                <?php if( $meta = vlog_get_meta_data( 'd' ) ) : ?>
                    <div class="entry-meta"><?php echo $meta; ?></div>
                <?php endif; ?>

            </div>

            <?php if( vlog_get_option('lay_d_excerpt') ) : ?>
                <div class="entry-content">
                    <?php echo vlog_get_excerpt( 'd' ); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</article>