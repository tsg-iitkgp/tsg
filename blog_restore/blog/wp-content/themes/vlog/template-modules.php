<?php
/**
 * Template Name: Modules
 */
?>
<?php get_header(); ?>

<?php $vlog_meta = vlog_get_page_meta( get_the_ID() ); ?>

<?php if( isset( $vlog_meta['fa'] ) && $vlog_meta['fa']['layout'] != 'none' ) : ?>

    <?php $fa = vlog_get_featured_area_query( $vlog_meta['fa'] ); ?>
    <?php include( locate_template('template-parts/cover/area-' . absint( $vlog_meta['fa']['layout'] ) . '.php') ); ?>
    <?php wp_reset_postdata(); ?>

<?php endif; ?>

<?php get_template_part('template-parts/ads/below-header'); ?>

<?php
    
    global $vlog_sidebar_opts;
    $sections = $vlog_meta['sections'];
?>

<?php if ( !empty( $sections ) ) : ?>

    <?php 
        
        //Check if pagination is set and do required tweaks
        if( $vlog_meta['pag'] != 'none' ){
            
            $pagination = $vlog_meta['pag'];
            vlog_set_paginated_module_index( $sections );
            $paged = vlog_module_template_is_paged();
            
            if( $paged ){
                $sections = vlog_parse_paged_module_template( $sections );
                vlog_set_paginated_module_index( $sections, $paged );
            }
        }

    ?>

    <?php foreach ( $sections as $s_ind => $section ) : ?>
        
        <?php 
            $vlog_sidebar_opts = $section;
            $section_class = $section['use_sidebar'] == 'none' ? 'vlog-no-sid ' : '';
            $section_class .= $section['bg'];
            $section_class .= isset( $section['css_class'] ) ? ' '.$section['css_class'] : '';
        ?>
        
        <div class="vlog-section <?php echo esc_attr( $section_class ); ?>">

            <div class="container">
                
                <?php if( $vlog_sidebar_opts['use_sidebar'] == 'left' ): ?>
                    <?php get_template_part('sidebar'); ?>
                <?php endif; ?>


                <div class="vlog-content">

                    <div class="row row-eq-height">

                        <?php if(!empty($section['modules'])): ?>

                            <?php foreach( $section['modules'] as $m_ind => $module ): $module = vlog_parse_args( $module, vlog_get_module_defaults( $module['type'] ) ); ?>
                                    
                                   <?php include( locate_template('template-parts/modules/'.$module['type'].'.php') ); ?>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div>

                </div>


                <?php if( $vlog_sidebar_opts['use_sidebar'] == 'right' ): ?>
                    <?php get_template_part('sidebar'); ?>
                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="vlog-section">

        <div class="container">

            <div class="vlog-content">

                <?php

                    $args = array(
                        'title' => '<h4>'. esc_html__( 'Oooops!', 'vlog' ).'</h2>',
                        'desc' =>  wp_kses( sprintf( __( 'You don\'t have any sections and modules yet. Hurry up and <a href="%s">create your first module</a>.', 'vlog' ), admin_url( 'post.php?post='.get_the_ID().'&action=edit#vlog_modules' ) ), wp_kses_allowed_html( 'post' ))
                    );

                    echo vlog_module_heading( $args );
                ?>

            </div>

        </div>

    </div>

<?php endif; ?>

<?php get_footer(); ?>