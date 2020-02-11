<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-choices" data-tp-tab-content="choices">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/before', $choices, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-selection-minimum">
				<?php _e( 'Minimum selection', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The minimum number of choices to be sent.', TP_TD ); ?>">?</span>
			</label>
			<input id="totalpoll-settings-limitations-selection-minimum" type="number" name="totalpoll[settings][limitations][selection][minimum]" min="1" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $limitations['selection']['minimum'] ) ); ?>">
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/selection-minimum', $choices, $this->poll ); ?>

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-selection-maximum">
				<?php _e( 'Maximum selection', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The maximum number of choices to be sent.', TP_TD ); ?>">?</span>
			</label>
			<input id="totalpoll-settings-limitations-selection-maximum" type="number" name="totalpoll[settings][limitations][selection][maximum]" min="0" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $limitations['selection']['maximum'] ) ); ?>">

			<p class="totalpoll-feature-tip"><?php _e( '0 means unlimited.', TP_TD ); ?></p>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/selection-maximum', $choices, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-choices-pagination-per-page">
				<?php _e( 'Choices per page', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Choices per page', TP_TD ); ?>">?</span>
			</label>
			<input id="totalpoll-settings-choices-pagination-per-page" type="number" name="totalpoll[settings][choices][pagination][per_page]" min="0" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $choices['pagination']['per_page'] ) ); ?>">

			<p class="totalpoll-feature-tip"><?php _e( '0 means unlimited.', TP_TD ); ?></p>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/pagination-per-page', $choices, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][choices][order][enabled]" data-tp-toggle="choices-order-advanced" <?php checked( empty( $choices['order']['enabled'] ), false ); ?>>
				<?php _e( 'Order choices', TP_TD ); ?>
			</label>

			<p class="totalpoll-feature-tip"><?php _e( 'Heads up! Enabling this along with pagination can cause performance issues.', TP_TD ); ?></p>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/order-choices', $choices, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $choices['order']['enabled'] ) ? '' : 'active'; ?>" id="choices-order-advanced" data-tp-toggleable="choices-order-advanced">

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Order by', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The choices will be ordered by the following criteria.', TP_TD ); ?>">?</span>
			</label>
			<select name="totalpoll[settings][choices][order][by]" id="" class="settings-field-select widefat">
				<option value="votes" <?php selected( $choices['order']['by'], 'votes' ); ?>>
					<?php _e( 'Votes', TP_TD ); ?>
				</option>
				<option value="label" <?php selected( $choices['order']['by'], 'label' ); ?>>
					<?php _e( 'Label', TP_TD ); ?>
				</option>
				<option value="date" <?php selected( $choices['order']['by'], 'date' ); ?>>
					<?php _e( 'Date', TP_TD ); ?>
				</option>
				<option value="random" <?php selected( $choices['order']['by'], 'random' ); ?>>
					<?php _e( 'Random', TP_TD ); ?>
				</option>
			</select>
		</div>

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Order direction', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The order direction (ascending or descending).', TP_TD ); ?>">?</span>
			</label>

			<label>
				<input type="radio" name="totalpoll[settings][choices][order][direction]" value="asc" <?php checked( $choices['order']['direction'], 'asc' ); ?>>
				<?php _e( 'Ascending', TP_TD ); ?>
			</label>
			&nbsp;
			<label>
				<input type="radio" name="totalpoll[settings][choices][order][direction]" value="desc" <?php checked( $choices['order']['direction'], 'desc' ); ?>>
				<?php _e( 'Descending', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/order-choices-advanced', $choices, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][choices][other][enabled]" data-tp-toggle="choices-other-advanced" <?php checked( empty( $choices['other']['enabled'] ), false ); ?>>
				<?php _e( 'Allow user submissions', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/allow-user-submissions', $choices, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $choices['other']['enabled'] ) ? '' : 'active'; ?>" id="choices-other-advanced" data-tp-toggleable="choices-other-advanced">
		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][choices][other][moderation]" <?php checked( empty( $choices['other']['moderation'] ), false ); ?>>
				<?php _e( 'Hide new submissions for moderation', TP_TD ); ?>
			</label>
		</div>
		<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/allow-user-submissions-advanced', $choices, $this->poll ); ?>
	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/choices/after', $choices, $this->poll ); ?>

</div>