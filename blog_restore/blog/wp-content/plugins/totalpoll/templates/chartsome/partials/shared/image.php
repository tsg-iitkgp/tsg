<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-choice-image" itemscope itemtype="http://schema.org/ImageObject">
	<a href="<?php echo esc_attr( $choice['content']['image']['url'] ); ?>" target="_blank">
		<img src="<?php echo esc_attr( empty( $choice['content']['thumbnail']['url'] ) ? $choice['content']['image']['url'] : $choice['content']['thumbnail']['url'] ); ?>" itemprop="contentUrl">
	</a>
</div>