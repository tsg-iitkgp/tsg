<?php if ( has_nav_menu( 'vlog_social_menu' ) ) : ?>
<li class="vlog-actions-button vlog-social-icons">
	<span>
		<i class="fv fv-social"></i>
	</span>
	<ul class="sub-menu">
	<li>
		<?php wp_nav_menu( array( 'theme_location' => 'vlog_social_menu', 'container'=> '', 'menu_class' => 'vlog-soc-menu vlog-in-popup', 'link_before' => '<span class="vlog-social-name">', 'link_after' => '</span>' ) ); ?>
	</li>
	</ul>
</li>
<?php endif; ?>