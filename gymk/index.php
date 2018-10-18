<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>


				<div class="blog-recent row">
                    <h1 class="col m8 offset-m2">Blog</h1>
                    <div class="col m8 offset-m2 card z-depth-0">
                    	<div class="card-content">
                            <ul class="collection">
                                
                            
		                    <?php if (have_posts()) : ?>
		                    	<?php while (have_posts()) : the_post(); ?>
		                    		<li class="collection-item">
	                                    <span class="card-title"><?php the_title(); ?></span>
	                                    <small><?php the_time(__('F jS, Y', 'kubrick')) ?> <!-- by <?php the_author() ?> --></small>
	                                    <p><?php the_excerpt(); ?></p>
	                                    <a href="<?php the_permalink() ?>" class="purple-text btn-flat waves-effect">Read More <i class="material-icons right">keyboard_arrow_right</i> </a>
	                                </li>
								<?php endwhile; ?>
								<?php endif; ?>
							</ul>
                        </div>
                    
                        <div class="card-action">
                            <a href="#" class="btn purple waves-effect waves-light">Older Posts</a>
                        </div>
                    </div>
                </div>

<?php get_footer(); ?>
