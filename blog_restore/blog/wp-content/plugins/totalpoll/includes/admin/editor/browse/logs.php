<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content" data-tp-tab-content="browse-logs" data-tp-paginate>
	<?php
	$logs       = TotalPoll::instance( 'meta-pageable' )->paginate( 'logs', $this->poll->id() );
	$logs_count = TotalPoll::instance( 'meta-pageable' )->count( 'logs', $this->poll->id() );
	$raw_fields = $this->poll->fields()->raw_fields();
	?>
	<input type="hidden" data-tp-paginate-count value="<?php echo $logs_count; ?>">
	<input type="hidden" data-tp-paginate-action value="totalpoll_browse_logs">

	<div class="totalpoll-inline-labels">
		<label>
			<input type="checkbox" data-tp-toggle="log-column-status" checked="checked">
			<?php _e( 'Status', TP_TD ); ?>
		</label>
		<label>
			<input type="checkbox" data-tp-toggle="log-column-time" checked="checked">
			<?php _e( 'Time', TP_TD ); ?>
		</label>
		<label>
			<input type="checkbox" data-tp-toggle="log-column-ip" checked="checked">
			<?php _e( 'IP', TP_TD ); ?>
		</label>
		<label>
			<input type="checkbox" data-tp-toggle="log-column-browser" checked="checked">
			<?php _e( 'Browser', TP_TD ); ?>
		</label>
		<label>
			<input type="checkbox" data-tp-toggle="log-column-choices">
			<?php _e( 'Choices', TP_TD ); ?>
		</label>

		<label>
			<input type="checkbox" data-tp-toggle="log-column-details">
			<?php _e( 'Details', TP_TD ); ?>
		</label>

		<?php do_action( 'totalpoll/actions/admin/editor/logs/labels', $this->poll ); ?>

		<?php foreach ( $raw_fields as $field ):
			if ( ! isset( $field['name'] ) ):
				continue;
			endif;
			?>
			<label>
				<input type="checkbox" data-tp-toggle="log-column-custom-fields-<?php echo esc_attr( $field['name'] ); ?>">
				<?php echo esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] ); ?>
			</label>
		<?php endforeach; ?>
	</div>

	<table class="wp-list-table widefat fixed striped users">
		<thead>
		<tr>
			<th scope="col" class="manage-column column-status active" data-tp-toggleable="log-column-status" width="80"><?php _e( 'Status', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-time active" data-tp-toggleable="log-column-time" width="160"><?php _e( 'Time', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-ip active" data-tp-toggleable="log-column-ip" width="80"><?php _e( 'IP', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-browser active" data-tp-toggleable="log-column-browser" width="200"><?php _e( 'Browser', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-choices" data-tp-toggleable="log-column-choices"><?php _e( 'Choices', TP_TD ); ?></th>
			<?php do_action( 'totalpoll/actions/admin/editor/logs/table-header-cells', $this->poll ); ?>
			<?php foreach ( $raw_fields as $field ): ?>
				<th scope="col" class="manage-column column-custom-fields" data-tp-toggleable="log-column-custom-fields-<?php echo esc_attr( $field['name'] ); ?>">
					<?php echo esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] ); ?>
				</th>
			<?php endforeach; ?>
			<th scope="col" class="manage-column column-details" data-tp-toggleable="log-column-details"><?php _e( 'Details', TP_TD ); ?></th>
		</tr>
		</thead>

		<tbody data-tp-paginate-body>

		<?php

		if ( empty( $logs ) === true ):
			?>
			<tr style="background: white;">
				<td><?php _e( 'There are no logs.', TP_TD ); ?></td>
			</tr>
			<?php
		else:
			foreach ( $logs as $item ):
				include 'logs-item.php';
			endforeach;
		endif;
		?>
		</tbody>

		<tfoot>
		<tr>
			<th scope="col" class="manage-column column-status active" data-tp-toggleable="log-column-status" width="80"><?php _e( 'Status', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-time active" data-tp-toggleable="log-column-time" width="160"><?php _e( 'Time', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-ip active" data-tp-toggleable="log-column-ip" width="80"><?php _e( 'IP', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-browser active" data-tp-toggleable="log-column-browser" width="200"><?php _e( 'Browser', TP_TD ); ?></th>
			<th scope="col" class="manage-column column-choices" data-tp-toggleable="log-column-choices"><?php _e( 'Choices', TP_TD ); ?></th>
			<?php do_action( 'totalpoll/actions/admin/editor/logs/table-header-cells', $this->poll ); ?>
			<?php foreach ( $raw_fields as $field ): ?>
				<th scope="col" class="manage-column column-custom-fields" data-tp-toggleable="log-column-custom-fields-<?php echo esc_attr( $field['name'] ); ?>">
					<?php echo esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] ); ?>
				</th>
			<?php endforeach; ?>
			<th scope="col" class="manage-column column-details" data-tp-toggleable="log-column-details"><?php _e( 'Details', TP_TD ); ?></th>
		</tr>
		</tfoot>

	</table>

	<div class="totalpoll-toolbar clearfix">
		<div class="alignleft">
			<button class="button" data-tp-paginate-button data-tp-paginate-first disabled value="1"><?php _e( 'First', TP_TD ); ?></button>
			&nbsp;
			<button class="button button-primary" data-tp-paginate-button data-tp-paginate-previous disabled value="1"><?php _e( 'Previous', TP_TD ); ?></button>
		</div>
		<div class="alignright">
			<button class="button button-primary" data-tp-paginate-button data-tp-paginate-next <?php disabled( $logs_count < 10, true ); ?> value="2"><?php _e( 'Next', TP_TD ); ?></button>
			&nbsp;
			<button class="button" data-tp-paginate-button data-tp-paginate-last <?php disabled( $logs_count < 10, true ); ?> value="<?php echo ceil( $logs_count / 10 ); ?>"><?php _e( 'Last', TP_TD ); ?></button>
		</div>
	</div>

	<div class="totalpoll-toolbar clearfix with-major-actions">
		<div class="alignleft">
			<?php printf( _n( 'One log', '%s logs', $logs_count, TP_TD ), number_format( $logs_count ) ); ?>
		</div>
		<div class="alignright">
			<?php _e( 'Download', TP_TD ); ?>
			&nbsp;&nbsp;
			<button class="button button-primary" type="submit" name="totalpoll[download][logs]" value="csv" formtarget="_blank"><?php _e( 'CSV', TP_TD ); ?></button>
			&nbsp;
			<button class="button button-primary" type="submit" name="totalpoll[download][logs]" value="html" formtarget="_blank"><?php _e( 'HTML', TP_TD ); ?></button>
			&nbsp;&nbsp;
			<?php _e( 'or', TP_TD ); ?>
			&nbsp;&nbsp;
			<button class="button" name="totalpoll[reset][logs]" value="1"><?php _e( 'Reset', TP_TD ); ?></button>
		</div>
	</div>

</div>