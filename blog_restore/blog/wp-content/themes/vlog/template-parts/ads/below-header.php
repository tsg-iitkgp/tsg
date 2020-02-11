<?php if( $ad = vlog_get_option('ad_below_header') ): ?>
	<div class="vlog-ad"><?php echo do_shortcode( $ad ); ?></div>
<?php endif; ?>