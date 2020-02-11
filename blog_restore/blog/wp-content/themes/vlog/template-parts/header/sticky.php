	<?php $shadow_class = vlog_get_option('header_shadow') ? 'vlog-header-shadow' : ''; ?>

<div id="vlog-sticky-header" class="vlog-sticky-header vlog-site-header <?php echo esc_attr( $shadow_class ); ?> vlog-header-bottom hidden-xs hidden-sm">
	
		<div class="container">
				<div class="vlog-slot-l">
					<?php get_template_part('template-parts/header/elements/logo'); ?>
				</div>
				<div class="vlog-slot-c">
					<?php get_template_part('template-parts/header/elements/main-menu'); ?>     
				</div> 	
				<div class="vlog-slot-r">
					<?php get_template_part('template-parts/header/elements/actions'); ?>
				</div>
		</div>

</div>