<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content totalpoll-containables-container settings-custom-fields" data-tp-tab-content="custom-fields" data-tp-container>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/custom-fields/before', $fields, $this->poll ); ?>

	<ul class="totalpoll-containables-types">
		<li>
			<button class="button" type="button" data-tp-containables-insert value="custom-fields-text"><?php _e( 'Text', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="custom-fields-textarea"><?php _e( 'Text area', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="custom-fields-select"><?php _e( 'Select', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="custom-fields-checkbox"><?php _e( 'Checkbox', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="custom-fields-radio"><?php _e( 'Radio', TP_TD ); ?></button>
		</li>
		<?php do_action( 'totalpoll/actions/admin/editor/settings/custom-fields/types', $fields, $this->poll ); ?>
	</ul>

	<ul class="totalpoll-containables" data-tp-containables data-tp-sortable>
		<?php
		if ( ! empty( $fields ) ):
			foreach ( $fields as $custom_field_index => $custom_field ):

				$custom_field_id = str_replace( '.', '', microtime( true ) );
				$custom_field    = TotalPoll::instance( 'helpers' )->parse_args(
					$custom_field,
					array(
						'validations' => array(
							'regex'  => array(
								'against' => '',
								'message' => '%label% field is not valid.',
								'type'    => 'match',
							),
							'filter' => array(
								'list' => '',
							),
						),
						'template'    => '%label% %field%',
						'statistics'  => array(
							'enabled' => in_array( $custom_field['type'], array( 'text', 'textarea' ) ) ? false : true,
						),
					)
				);
				if ( file_exists( TP_PATH . "/includes/admin/editor/custom-fields/{$custom_field['type']}.php" ) ):
					include TP_PATH . "/includes/admin/editor/custom-fields/{$custom_field['type']}.php";
				else:
					do_action( "totalpoll/actions/admin/editor/custom-fields/{$custom_field['type']}", $custom_field_index, $custom_field_id, $custom_field, $fields, $this->poll );
				endif;
			endforeach;
		endif;
		?>
	</ul>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/custom-fields/after', $fields, $this->poll ); ?>

</div>