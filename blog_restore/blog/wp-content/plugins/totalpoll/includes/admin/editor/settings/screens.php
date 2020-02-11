<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-screens" data-tp-tab-content="screens">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/before', $screens, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][before_vote][enabled]" data-tp-toggle="screens-before-vote-advanced" <?php checked( empty( $screens['before_vote']['enabled'] ), false ); ?>>
				<?php _e( 'Before voting', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/before-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['before_vote']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-before-vote-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['before_vote']['content'] ) ? '' : $screens['before_vote']['content'], 'beforeVoteScreen', array( 'textarea_name' => 'totalpoll[settings][screens][before_vote][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/before-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][after_vote][enabled]" data-tp-toggle="screens-after-vote-advanced" <?php checked( empty( $screens['after_vote']['enabled'] ), false ); ?>>
				<?php _e( 'After voting', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['after_vote']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-after-vote-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['after_vote']['content'] ) ? '' : $screens['after_vote']['content'], 'afterVoteScreen', array( 'textarea_name' => 'totalpoll[settings][screens][after_vote][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after', $screens, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][above_vote][enabled]" data-tp-toggle="screens-above-vote-advanced" <?php checked( empty( $screens['above_vote']['enabled'] ), false ); ?>>
				<?php _e( 'Above vote', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/above-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['above_vote']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-above-vote-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['above_vote']['content'] ) ? '' : $screens['above_vote']['content'], 'aboveVoteScreen', array( 'textarea_name' => 'totalpoll[settings][screens][above_vote][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/above-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][below_vote][enabled]" data-tp-toggle="screens-below-vote-advanced" <?php checked( empty( $screens['below_vote']['enabled'] ), false ); ?>>
				<?php _e( 'Below vote', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/below-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['below_vote']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-below-vote-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['below_vote']['content'] ) ? '' : $screens['below_vote']['content'], 'belowVoteScreen', array( 'textarea_name' => 'totalpoll[settings][screens][below_vote][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/below-voting-advanced', $screens, $this->poll ); ?>

	</div>


	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][above_results][enabled]" data-tp-toggle="screens-above-results-advanced" <?php checked( empty( $screens['above_results']['enabled'] ), false ); ?>>
				<?php _e( 'Above results', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/above-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['above_results']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-above-results-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['above_results']['content'] ) ? '' : $screens['above_results']['content'], 'aboveResultsScreen', array( 'textarea_name' => 'totalpoll[settings][screens][above_results][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/above-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][below_results][enabled]" data-tp-toggle="screens-below-results-advanced" <?php checked( empty( $screens['below_results']['enabled'] ), false ); ?>>
				<?php _e( 'Below results', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/below-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['below_results']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-below-results-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['below_results']['content'] ) ? '' : $screens['below_results']['content'], 'belowResultsScreen', array( 'textarea_name' => 'totalpoll[settings][screens][below_results][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/below-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after', $screens, $this->poll ); ?>

</div>