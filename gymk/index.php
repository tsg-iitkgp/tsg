<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

<div id="content">

	<div class="header row valign-wrapper">
		<div class="col s12 m3 center">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/static/images/IIT_Kharagpur_Logo.svg" alt="IIT Icon" class="responsive-img">
		</div>
		<div class="col m9 s12">
			<h1>Blog</h1>
			<h2>Technology Students' Gymkhana</h2>
		</div>
	</div>
	

	<div class="blog-recent row">
		<!-- <h1 class="col m8 offset-m2">Blog</h1> -->
		<div class="col m9 s12 offset-m1 card z-depth-0">
			<div class="card-content">
				<ul class="collection">
				
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>

						<li class="collection-item">
							<a class="post-title" href="<?php the_permalink() ?>"><span class="card-title"><?php the_title(); ?></span></a>
							<small><?php the_time(__('F jS, Y', 'kubrick')) ?> <!-- by <?php the_author() ?> --></small>
							<?php the_excerpt(); ?>
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

</div>

<?php get_footer(); ?>
