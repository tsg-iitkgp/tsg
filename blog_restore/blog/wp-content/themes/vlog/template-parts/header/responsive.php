<div id="vlog-responsive-header" class="vlog-responsive-header hidden-lg hidden-md">
	<div class="container">
		<?php $logo = vlog_get_option('logo_mini') ? 'logo-mini' : 'logo'; ?>
		<?php get_template_part('template-parts/header/elements/'. $logo ); ?>
		
		<ul>
			<?php $actions = array_keys( array_filter( vlog_get_option( 'header_actions' ) ) );  ?>
			<?php if ( !empty( $actions ) ): ?>
					<?php foreach ( $actions as $element ): ?>
						<?php if(in_array($element, array('search-drop', 'watch-later')) ) { get_template_part( 'template-parts/header/elements/' . $element ); } ?>
					<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>

	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger"><i class="fa fa-bars"></i></button>	

		<?php if ( has_nav_menu( 'vlog_main_menu' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'vlog_main_menu', 'container'=> '', 'menu_class' => 'vlog-mob-nav dl-menu', 'walker' => new vlog_Menu_Walker ) ); ?>
		<?php else: ?>
			<?php if ( current_user_can( 'manage_options' ) ): ?>
				<ul class="vlog-main-nav vlog-no-responsive-nav">
					<li><a href="<?php echo esc_url(admin_url( 'nav-menus.php' )); ?>"><?php esc_html_e( 'Click here to add navigation menu', 'vlog' ); ?></a></li>
				</ul>
			<?php endif; ?>
		<?php endif; ?>

		
	</div>

</div>
