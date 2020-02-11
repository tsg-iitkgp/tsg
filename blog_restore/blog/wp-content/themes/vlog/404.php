<?php get_header(); ?>

<div class="vlog-section vlog-single-no-sid">

	<div class="container">

			<div class="vlog-content vlog-single-content">
						
				<div class="entry-image text-center">
       				<img src="<?php echo get_template_directory_uri() . '/assets/img/404.png'; ?>" />
	            </div>

				<h1 class="entry-title h1"><?php echo __vlog( '404_title'); ?></h1>

				<div class="entry-content">

					<p><?php echo __vlog( '404_text'); ?></p>
					<?php get_search_form(); ?>
				</div>

			</div>

	</div>

</div>

<?php get_footer(); ?>