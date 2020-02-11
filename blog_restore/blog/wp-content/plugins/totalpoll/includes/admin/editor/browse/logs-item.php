<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<tr>
	<td scope="col" class="manage-column column-status active" data-tp-toggleable="log-column-status">
		<?php echo $item['status'] == true ? __( 'Accepted', TP_TD ) : __( 'Denied', TP_TD ); ?>
	</td>

	<td scope="col" class="manage-column column-time active" data-tp-toggleable="log-column-time">
		<?php echo date( 'Y-m-d H:i e', $item['time'] ); ?>
	</td>

	<td scope="col" class="manage-column column-ip active" data-tp-toggleable="log-column-ip">
		<?php echo esc_html( $item['ip'] ); ?>
	</td>

	<td scope="col" class="manage-column column-browser active" data-tp-toggleable="log-column-browser" title="<?php echo esc_attr( $item['useragent'] ); ?>">
		<?php
		$ua = TotalPoll::instance( 'helpers' )->parse_useragent( $item['useragent'] );
		echo esc_html( "{$ua['platform']} {$ua['browser']} {$ua['version']}" );
		?>
	</td>

	<td scope="col" class="manage-column column-choices" data-tp-toggleable="log-column-choices">
		<?php
		echo ( empty( $item['choices'] ) === true ) ? __( 'N/A', TP_TD ) : nl2br( esc_html( implode( PHP_EOL, (array) $item['choices'] ) ) );
		?>
	</td>

	<?php do_action( 'totalpoll/actions/admin/editor/logs/table-body-cells', $item, $this->poll ); ?>

	<?php foreach ( ( isset( $raw_fields ) ? $raw_fields : $this->poll->fields()->raw_fields() ) as $field ): ?>
		<td scope="col" class="manage-column column-custom-fields" data-tp-toggleable="log-column-custom-fields-<?php echo esc_attr( $field['name'] ); ?>">
			<?php

			if ( ! empty( $item['fields'][ $field['name'] ] ) ):
				echo esc_html( implode(', ', (array) $item['fields'][ $field['name'] ]) );
			else:
				_e( 'N/A', TP_TD );
			endif;

			?>
		</td>
	<?php endforeach; ?>

	<td scope="col" class="manage-column column-details" data-tp-toggleable="log-column-details">
		<?php
		echo nl2br( esc_html( implode( PHP_EOL, (array) $item['details'] ) ) );
		?>
	</td>
</tr>