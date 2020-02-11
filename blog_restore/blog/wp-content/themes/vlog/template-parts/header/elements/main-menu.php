<nav class="vlog-main-navigation">	
	<?php if ( has_nav_menu( 'vlog_main_menu' ) ) : ?>
			<?php wp_nav_menu( array( 'theme_location' => 'vlog_main_menu', 'container'=> '', 'menu_class' => 'vlog-main-nav vlog-menu', 'walker' => new vlog_Menu_Walker) ); ?>
	<?php else: ?>
		<?php if ( current_user_can( 'manage_options' ) ): ?>
			<ul class="vlog-main-nav">
				<li><a href="<?php echo esc_url(admin_url( 'nav-menus.php' )); ?>"><?php esc_html_e( 'Click here to add navigation menu', 'vlog' ); ?></a></li>
			</ul>
		<?php endif; ?>
	<?php endif; ?>
</nav>