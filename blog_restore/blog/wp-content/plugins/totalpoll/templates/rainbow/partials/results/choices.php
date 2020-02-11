<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>

<?php $choices = $this->poll->choices(); ?>
<?php $colors = array ( '1e73be', 'dd3333', '81d742', '8224e3', 'dd9933', '7f8c8d', 'd35400', '1abc9c' ); ?>
<ul class="totalpoll-choices">
	<?php foreach ( $choices as $choice_index => $choice ): ?>
		<?php if ( isset( $choice['content']['label'] ) ): ?>
			<?php $color = $this->option('choices', 'colors', 'color-' . ($choice_index + 1)) ? $this->option('choices', 'colors', 'color-' . ($choice_index + 1)) : '#' . current($colors); ?>
			<li class="totalpoll-choice">

				<div class="totalpoll-choice-label">
				<span class="totalpoll-choice-label-text"
				      style="color: <?php echo $color; ?>;"><?php echo esc_attr( $choice['content']['label'] ); ?></span>
				</div><div class="totalpoll-choice-result">
					<div class="totalpoll-choice-progress"
					     style="width: <?php echo $choice['votes%']; ?>%;background: <?php echo $color; ?>;"></div>
				</div>

			<?php
				next( $colors );
				if ( ! current($colors) ) {
					reset( $colors );
				}
			?>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>