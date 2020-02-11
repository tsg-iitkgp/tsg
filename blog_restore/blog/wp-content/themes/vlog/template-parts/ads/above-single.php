<?php if( $ad = vlog_get_option('ad_above_single') ): ?>
	<div class="vlog-ad vlog-ad-above-single"><?php echo do_shortcode( $ad ); ?></div>
<?php endif; ?>