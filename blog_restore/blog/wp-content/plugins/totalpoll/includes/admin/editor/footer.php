<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="custom-fields-tpls">
	<?php
	$custom_field       = array(
		'validations' => array(
			'regex'  => array(
				'against' => '',
				'type'    => 'match',
				'message' => '%label% field is not valid.',
			),
			'filter' => array(
				'list' => '',
			),
		),
		'template'    => '%label% %field%',
		'statistics'  => array(
			'enabled' => false,
		),
	);
	$custom_field_index = '{{index}}';
	$custom_field_id    = '{{id}}';
	?>
	<?php
	foreach ( array( 'text', 'select', 'textarea', 'checkbox', 'radio' ) as $type ):
		$custom_field['statistics']['enabled'] = ! in_array( $type, array( 'text', 'textarea' ) );
		?>
		<script type="text/template" data-tp-containable-template="custom-fields-<?php echo $type; ?>">
			<?php include "custom-fields/{$type}.php"; ?>
		</script>
		<?php
	endforeach;
	?>
	<?php do_action( 'totalpoll/actions/admin/editor/custom-fields-templates', $this->poll ); ?>
</div>

<div class="totalpoll-containables-tpls">
	<?php
	$choice           = array(
		'votes'   => 0,
		'content' => array(
			'date'    => '',
			'visible' => true,
			'label'   => '',
		),

	);
	$choice_visible   = true;
	$choice_index     = '{{index}}';
	$choice_id        = '{{id}}';
	$choice_css_class = 'active';
	$choice_direct    = $this->poll->settings( 'limitations', 'direct', 'enabled' );
	?>
	<?php foreach ( array( 'text', 'image', 'video', 'audio', 'html' ) as $type ): ?>
		<script type="text/template" data-tp-containable-template="choices-<?php echo $type; ?>">
			<?php include "choices/{$type}.php"; ?>
		</script>
	<?php endforeach; ?>
	<?php do_action( 'totalpoll/actions/admin/editor/containable-templates', $choice_index, $choice_id, $choice_visible, $choice_direct, $choice, $this->poll ); ?>
</div>

</div></div>