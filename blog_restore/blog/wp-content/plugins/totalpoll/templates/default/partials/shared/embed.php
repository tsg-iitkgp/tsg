<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-choice-embed" data-tp-embed itemtype="http://schema.org/MediaObject">
	<?php if ( empty( $choice['content']['thumbnail']['url'] ) === false ): ?>
		<a href="<?php echo esc_attr( $choice['content'][ $choice['content']['type'] ]['url'] ); ?>" class="totalpoll-choice-embed-image totalpoll-choice-embed-image-<?php echo $choice['content']['type']; ?>">
			<img src="<?php echo esc_attr( $choice['content']['thumbnail']['url'] ); ?>" itemprop="contentUrl">
		</a>
	<?php endif; ?>
	<div class="totalpoll-choice-embed-code totalpoll-choice-embed-code-<?php echo $choice['content']['type']; ?>">
		<?php echo $this->embed( $choice['content'][ $choice['content']['type'] ]['url'], $choice['content']['type'] ); ?>
	</div>
</div>