<div class="vlog-module module-text col-lg-<?php echo esc_attr( $module['columns']);?> col-md-<?php echo esc_attr( $module['columns']);?> col-sm-<?php echo esc_attr( $module['columns']);?> col-xs-12 <?php echo esc_attr( $module['css_class']); ?>" id="vlog-module-<?php echo esc_attr($s_ind.'-'.$m_ind); ?>">
	<?php echo vlog_get_module_heading( $module ); ?>

	<?php if(!empty($module['content'])) :?>
		<div class="vlog-txt-module">
			<?php $module['content'] = !empty($module['autop']) ?  wpautop($module['content']) : $module['content']; ?>
			<?php echo do_shortcode( $module['content']); ?>
		</div>
	<?php endif; ?>

</div>