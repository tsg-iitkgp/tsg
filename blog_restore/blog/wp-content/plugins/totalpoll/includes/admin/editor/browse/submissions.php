<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content" data-tp-tab-content="browse-submissions" data-tp-paginate>
	<?php
	$submissions_count = TotalPoll::instance( 'meta-pageable' )->count( 'submissions', $this->poll->id() );
	$raw_fields        = $this->poll->fields()->raw_fields();
	?>
	<input type="hidden" data-tp-paginate-count value="<?php echo $submissions_count; ?>">
	<input type="hidden" data-tp-paginate-action value="totalpoll_browse_submissions">

	<div class="totalpoll-inline-labels">
		<?php if ( empty( $raw_fields ) === false ): ?>
			<label>
				<input type="checkbox" data-tp-toggle="submission-column-date" checked>
				<?php _e( 'Date', TP_TD ); ?>
			</label>
		<?php endif; ?>
		<?php foreach ( $raw_fields as $index => $field ): ?>
			<label>
				<input type="checkbox" data-tp-toggle="submission-column-<?php echo esc_attr( $field['name'] ); ?>" <?php checked( $index < 3, true ); ?>>
				<?php echo esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] ); ?>
			</label>
		<?php endforeach; ?>
	</div>

	<table class="wp-list-table widefat fixed striped users">
		<thead>
		<tr>
			<th scope="col" class="manage-column column active" data-tp-toggleable="submission-column-date">
				<?php _e( 'Date', TP_TD ); ?>
			</th>
			<?php foreach ( $raw_fields as $index => $field ): ?>
				<th scope="col" class="manage-column column <?php echo $index < 3 ? 'active' : ''; ?>" data-tp-toggleable="submission-column-<?php echo esc_attr( $field['name'] ); ?>">
					<?php echo esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] ); ?>
				</th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody data-tp-paginate-body>
		<?php
		if ( empty( $raw_fields ) === true ):
			?>
			<tr style="background: white;">
				<td scope="col">
					<?php
					if ( $submissions_count > 0 ):
						_e( 'There are stored submissions, but custom fields must be present to browse them.', TP_TD );
					else:
						_e( 'There are no submissions.', TP_TD );
					endif;
					?>
				</td>
			</tr>
			<?php
		else:
			if ( $submissions_count < 1 ):
				?>
				<tr style="background: white;">
					<td scope="col"><?php _e( 'There are no submissions.', TP_TD ); ?></td>
				</tr>
				<?php
			else:
				$submissions = TotalPoll::instance( 'meta-pageable' )->paginate( 'submissions', $this->poll->id() );
				foreach ( $submissions as $item ):
					include 'submissions-item.php';
				endforeach;
			endif;
		endif;
		?>

		</tbody>

		<tfoot>
		<tr>
			<th scope="col" class="manage-column column active" data-tp-toggleable="submission-column-date">
				<?php _e( 'Date', TP_TD ); ?>
			</th>
			<?php foreach ( $raw_fields as $index => $field ): ?>
				<th scope="col" class="manage-column column <?php echo $index < 3 ? 'active' : ''; ?>" data-tp-toggleable="submission-column-<?php echo esc_attr( $field['name'] ); ?>">
					<?php echo esc_html( empty( $field['label']['content'] ) ? $field['name'] : $field['label']['content'] ); ?>
				</th>
			<?php endforeach; ?>
		</tr>
		</tfoot>

	</table>
	<div class="totalpoll-toolbar clearfix">
		<?php if ( empty( $raw_fields ) === false ): ?>
			<div class="alignleft">
				<button class="button" data-tp-paginate-button data-tp-paginate-first disabled value="1"><?php _e( 'First', TP_TD ); ?></button>
				&nbsp;
				<button class="button button-primary" data-tp-paginate-button data-tp-paginate-previous disabled value="1"><?php _e( 'Previous', TP_TD ); ?></button>
			</div>
			<div class="alignright">
				<button class="button button-primary" data-tp-paginate-button data-tp-paginate-next <?php disabled( $submissions_count < 10, true ); ?> value="2"><?php _e( 'Next', TP_TD ); ?></button>
				&nbsp;
				<button class="button" data-tp-paginate-button data-tp-paginate-last <?php disabled( $submissions_count < 10, true ); ?> value="<?php echo ceil( $submissions_count / 10 ); ?>"><?php _e( 'Last', TP_TD ); ?></button>
			</div>
		<?php endif; ?>
	</div>

	<div class="totalpoll-toolbar clearfix with-major-actions">
		<div class="alignleft">
			<?php printf( _n( 'One submission', '%s submissions', $submissions_count, TP_TD ), number_format( $submissions_count ) ); ?>
		</div>
		<div class="alignright">
			<?php _e( 'Download', TP_TD ); ?>
			&nbsp;&nbsp;
			<button class="button button-primary" type="submit" name="totalpoll[download][submissions]" value="csv" formtarget="_blank"><?php _e( 'CSV', TP_TD ); ?></button>
			&nbsp;
			<button class="button button-primary" type="submit" name="totalpoll[download][submissions]" value="html" formtarget="_blank"><?php _e( 'HTML', TP_TD ); ?></button>
			&nbsp;&nbsp;
			<?php _e( 'or', TP_TD ); ?>
			&nbsp;&nbsp;
			<button class="button" name="totalpoll[reset][submissions]" value="1"><?php _e( 'Reset', TP_TD ); ?></button>
		</div>
	</div>

</div>