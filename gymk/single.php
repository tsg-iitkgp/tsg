<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
<h2 class="post-title"><?php the_title(); ?></h2>
<?php
if ( has_post_thumbnail() ) {
	the_post_thumbnail();
} 
?>

<div id="content" class="row" role="main">
	
	<div <?php post_class("col s12 m9 l8 offset-m1 offset-l1"); ?> id="post-<?php the_ID(); ?>">
		<!-- <h2><?php //the_title(); ?></h2> -->

		
		<div class="entry">
			
			<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'kubrick') . '</p>'); ?>
			
			<?php wp_link_pages(array('before' => '<p><strong>' . __('Pages:', 'kubrick') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			<?php the_tags( '<p>' . __('Tags:', 'kubrick') . ' ', ', ', '</p>'); ?>
			
		</div>
	</div>

	<!-- <hr class="col s12"> -->
	
	<?php $withcomments="1"; comments_template(); ?>

	<div class="navigation col s12">
		<div class="alignleft"><?php previous_post_link( '%link', '&laquo; %title' ) ?></div>
		<div class="alignright"><?php next_post_link( '%link', '%title &raquo;' ) ?></div>
	</div>
			
	
	
	<?php endwhile; else: ?>
		
	<p><?php _e('Sorry, no posts matched your criteria.', 'kubrick'); ?></p>
	
	<?php endif; ?>
	
	<div class="fixed-action-btn">
		<span class="btn-floating btn-large blue">
			<i class="large material-icons">share</i>
		</span>
		<ul>
			<li><a class="btn-floating green"><i class="material-icons">email</i></a></li>
			<li><a class="btn-floating pink"><i class="material-icons">insert_link</i></a></li>
			<li><a class="btn-floating blue lighten-1" href="http://twitter.com/intent/tweet?text=Read this blogpost on Technology Students Gymkhana&url=<?php esc_url(the_permalink()); ?>&hashtags=tsg,iitkgp"><i class="icon-twitter"></i></a></li>
			<li><a class="btn-floating facebook-color" target="popup" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php get_post_permalink(); ?>&amp;src=sdkpreparse','popup','width=600,height=600'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=<?php get_post_permalink(); ?>&amp;src=sdkpreparse"><i class="icon-facebook"></i></a></li>
		</ul>
	</div>

</div>

<?php get_footer(); ?>
