<?php if( $ad = vlog_get_option('ad_below_single') ): ?>
	<div class="vlog-ad vlog-ad-below-single"><?php echo do_shortcode( $ad ); ?></div>
<?php endif; ?>