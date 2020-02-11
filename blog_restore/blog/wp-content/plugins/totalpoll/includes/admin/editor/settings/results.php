<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-results" data-tp-tab-content="results">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/results/before', $results, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][results][require_vote][enabled]" <?php checked( empty( $limitations['results']['require_vote']['enabled'] ), false ); ?>>
				<?php _e( 'Require vote', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Results are visible only for voters.', TP_TD ); ?>">?</span>
			</label>
		</div>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][results][order][enabled]" data-tp-toggle="results-order-advanced" <?php checked( empty( $results['order']['enabled'] ), false ); ?>>
				<?php _e( 'Order results', TP_TD ); ?>
			</label>

			<p class="totalpoll-feature-tip"><?php _e( 'Heads up! Enabling this along with pagination can cause performance issues.', TP_TD ); ?></p>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/results/order-results', $results, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $results['order']['enabled'] ) ? '' : 'active'; ?>" id="results-order-advanced" data-tp-toggleable="results-order-advanced">

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Order by', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The results will be ordered by the following criteria.', TP_TD ); ?>">?</span>
			</label>
			<select name="totalpoll[settings][results][order][by]" id="" class="settings-field-select widefat">
				<option value="votes" <?php selected( $results['order']['by'], 'votes' ); ?>>
					<?php _e( 'Votes', TP_TD ); ?>
				</option>
				<option value="label" <?php selected( $results['order']['by'], 'label' ); ?>>
					<?php _e( 'Label', TP_TD ); ?>
				</option>
				<option value="date" <?php selected( $results['order']['by'], 'date' ); ?>>
					<?php _e( 'Date', TP_TD ); ?>
				</option>
				<option value="random" <?php selected( $results['order']['by'], 'random' ); ?>>
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
				<input type="radio" name="totalpoll[settings][results][order][direction]" value="asc" <?php checked( $results['order']['direction'], 'asc' ); ?>>
				<?php _e( 'Ascending', TP_TD ); ?>
			</label>
			&nbsp;
			<label>
				<input type="radio" name="totalpoll[settings][results][order][direction]" value="desc" <?php checked( $results['order']['direction'], 'desc' ); ?>>
				<?php _e( 'Descending', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/results/order-results-advanced', $results, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][results][hide][enabled]" data-tp-toggle="results-hide-advanced" <?php checked( empty( $results['hide']['enabled'] ), false ); ?>>
				<?php _e( 'Hide results', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/results/hide-results', $results, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $results['hide']['enabled'] ) ? '' : 'active'; ?>" id="results-order-advanced" data-tp-toggleable="results-hide-advanced">

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Until reaching', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The results will be hidden until the following criteria are reached.', TP_TD ); ?>">?</span>
			</label>

			<label>
				<input type="checkbox" name="totalpoll[settings][results][hide][until][quota]" value="quota" <?php checked( isset( $results['hide']['until']['quota'] ), true ); ?>>
				<?php _e( 'Quota', TP_TD ); ?>
			</label>
			&nbsp;
			<label>
				<input type="checkbox" name="totalpoll[settings][results][hide][until][end_date]" value="end_date" <?php checked( isset( $results['hide']['until']['end_date'] ), true ); ?>>
				<?php _e( 'End date', TP_TD ); ?>
			</label>
		</div>

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Results content', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The following content will be shown instead of results.', TP_TD ); ?>">?</span>
			</label>
			<?php wp_editor( empty( $results['hide']['content'] ) ? '' : $results['hide']['content'], 'hideResultsContent', array( 'textarea_name' => 'totalpoll[settings][results][hide][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/results/hide-results-advanced', $results, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Results fragments', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Which fragments (votes, percentage or both) will be visible to the visitor.', TP_TD ); ?>">?</span>
			</label>
			<label>
				<input type="checkbox" name="totalpoll[settings][results][format][votes]" value="votes" <?php checked( isset( $results['format']['votes'] ), true ); ?>>
				<?php _e( 'Votes', TP_TD ); ?>
			</label>
			&nbsp;
			<label>
				<input type="checkbox" name="totalpoll[settings][results][format][percentages]" value="percentages" <?php checked( isset( $results['format']['percentages'] ), true ); ?>>
				<?php _e( 'Percentages', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/results/fragments', $results, $this->poll ); ?>

	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/results/after', $results, $this->poll ); ?>

</div>