<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>

<?php $choices = $this->poll->choices(); ?>
<ul class="totalpoll-choices">
	<?php foreach ( $choices as $choice ): ?>
		<?php if ( isset( $choice['content']['label'] ) ): ?>
			<li class="totalpoll-choice">
				<label>
					<?php if( $choice['content']['type'] !== 'other' ): ?>
					<div class="totalpoll-choice-checkbox-container">
						<?php echo $this->choice_input( $choice )->attribute( 'class', 'totalpoll-choice-checkbox' ); ?>
					</div>
					
					
					<div class="totalpoll-choice-label">
						<span class="totalpoll-choice-label-text"><?php echo esc_attr( $choice['content']['label'] ); ?></span>
					</div>
					<?php else: ?>
						<span><input type="text" name="totalpoll[choices][other][label]" placeholder="<?php esc_attr_e( 'Other', TP_TD ); ?>"></span>
					<?php endif; ?>
				</label>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
