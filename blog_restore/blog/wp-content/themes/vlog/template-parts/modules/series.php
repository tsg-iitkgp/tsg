<div class="vlog-module module-series col-lg-12 col-md-12 col-sm-12 <?php echo esc_attr( $module['css_class'] ); ?>" id="vlog-module-<?php echo esc_attr($s_ind.'-'.$m_ind); ?>" data-col="12">
 
     <?php echo vlog_get_module_heading( $module ); ?>

    <?php $mod_series = get_terms( array( 'taxonomy' => 'series', 'include' => implode(",", $module['series']) ) ); ?>

    <?php if( !is_wp_error($mod_series) ): ?>

        <?php 

            $new_mod_series = array();

            foreach( $mod_series as $cat){
                
                 if(!empty($module['series'])){
                    $new_mod_series[array_search( $cat->term_id, $module['series'])] = $cat;
                 } else {
                    $new_mod_series[$cat->term_id] = $cat;
                 }
            }
            
            ksort($new_mod_series);
        ?>


        <?php $slider_class = vlog_module_is_slider( $module ) && ( count($new_mod_series) > 1 )   ? 'vlog-slider' : ''; ?>

        <div class="row vlog-cats row-eq-height <?php echo esc_attr( $slider_class ); ?>">
            
            <?php if( !empty( $mod_series ) ): ?> 
                
                <?php $sort = vlog_get_option('serie_order_asc') ? 'ASC' : 'DESC'; ?>

                <?php foreach( $new_mod_series as $cat ): ?>
                    
                    <?php $cat_post = new WP_Query( 
                        array( 
                            'post_type' => 'post',
                            'order' => $sort,  
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'series',
                                    'field'    => 'term_id',
                                    'terms'    => $cat->term_id,
                                )
                                ),
                            'posts_per_page' => 1, 
                            'ignore_sticky_posts' => 1 
                            )
                        ); 

                        ?>
                    
                    <?php if( $cat_post->have_posts()): ?>
                        <?php while( $cat_post->have_posts()): $cat_post->the_post(); ?>
                            <?php $layout = vlog_get_module_layout( $module, 0 ); ?>
                            <?php include( locate_template('template-parts/cat-layouts/content-' . $layout . '.php') ); ?>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <?php wp_reset_postdata(); ?>
                
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

    <?php endif; ?>


</div>
