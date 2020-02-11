<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div data-tp-choices class="totalpoll-choices">

	<?php
	if ( $this->current === 'results' ):
		?>
		<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="<?php echo esc_attr( $this->option( 'chart', 'default', 'type' ) ) ?>" data-tp-chart-data="<?php echo esc_attr( json_encode( $this->choices_chart() ) ); ?>"></div>
		<?php
	endif;

	$per_row = absint( $this->option( 'general', 'other', 'per-row' ) );
	$per_row = $per_row < 1 ? 1 : $per_row;

	foreach ( $this->poll->choices() as $choice_index => $choice ):
		?>
		<?php
		if ( $choice_index !== 0 && $choice_index % $per_row === 0 ):
			?>
			<div class="totalpoll-choice-separator"></div>
			<?php
		endif;
		?>
		<div data-tp-choice class="totalpoll-choice totalpoll-choice-<?php echo $choice['content']['type']; ?> <?php echo $choice['checked'] ? 'checked' : ''; ?> <?php echo ( $choice_index + 1 ) % $per_row === 0 ? ' last-in-row' : ''; ?>"itemprop="suggestedAnswer" itemscope itemtype="http://schema.org/Answer">
			<?php
			if ( $choice['content']['type'] === 'video' || $choice['content']['type'] === 'audio' ):
				include 'shared/embed.php';
			elseif ( $choice['content']['type'] === 'image' ):
				include 'shared/image.php';
			endif;
			?>

			<label class="totalpoll-choice-container">
				<?php
				if ( $this->current === 'vote' && $choice['content']['type'] !== 'other' ):
					include 'vote/checkbox.php';
				endif;
				?>
				<div class="totalpoll-choice-content">
					<?php
					if ( $choice['content']['type'] !== 'html' && $choice['content']['type'] !== 'other' ):
						include 'shared/label.php';
					elseif ( $choice['content']['type'] === 'html' ):
						echo do_shortcode( $choice['content']['html'] );
					elseif ( $this->current === 'vote' && $choice['content']['type'] === 'other' ):
						include 'vote/other.php';
					endif;
					if ( $this->current === 'results' ):
						include 'results/votes.php';
					endif;
					?>
				</div>
			</label>
		</div>
		<?php
	endforeach;
	?>
</div>