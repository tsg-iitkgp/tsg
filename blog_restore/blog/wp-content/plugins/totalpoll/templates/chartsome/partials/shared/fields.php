<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

$fields = $this->poll->fields()->all();

if ( ! empty( $fields ) ):

	?>
	<div data-tp-fields class="totalpoll-fields">
		<?php foreach ( $fields as $field ): ?>
			<div class="totalpoll-field-wrapper">
				<?php echo $field->render(); ?>
				<?php foreach ( $field->errors() as $error ): ?>
					<div class="totalpoll-field-error">
						<?php echo $error; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
endif;
?>