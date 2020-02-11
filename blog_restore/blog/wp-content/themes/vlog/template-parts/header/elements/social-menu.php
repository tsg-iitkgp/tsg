<?php if ( has_nav_menu( 'vlog_social_menu' ) ) : ?>

		<?php wp_nav_menu( array( 'theme_location' => 'vlog_social_menu', 'container'=> '', 'menu_class' => 'vlog-soc-menu vlog-actions-social-list', 'link_before' => '<span class="vlog-social-name">',
'link_after' => '</span>', ) ); ?>

<?php endif; ?>