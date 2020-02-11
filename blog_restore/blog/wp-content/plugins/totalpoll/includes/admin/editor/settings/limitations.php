<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-limitations active" data-tp-tab-content="limitations">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/before', $limitations, $this->poll ); ?>
	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][cookies][enabled]" <?php checked( empty( $limitations['cookies']['enabled'] ), false ); ?>>
				<?php _e( 'Block re-vote by Cookies', TP_TD ); ?>
			</label>

			&nbsp;&mdash;&nbsp;
			<a href="#" data-tp-toggle="limitations-cookies-advanced"><?php _e( 'Advanced', TP_TD ); ?></a>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/block-by-cookies', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced" data-tp-toggleable="limitations-cookies-advanced">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-cookies-timeout">
				<?php _e( 'Cookies timeout (minutes)', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( "The minimum required time to clear the cookies from the voter's browser.", TP_TD ); ?>">?</span>
			</label>
			<input id="totalpoll-settings-limitations-cookies-timeout" type="number" name="totalpoll[settings][limitations][cookies][timeout]" min="0" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $limitations['cookies']['timeout'] ) ); ?>">

			<p class="totalpoll-feature-tip"><?php _e( '0 means permanent.', TP_TD ); ?></p>
		</div>
		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/block-by-cookies-advanced', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][ip][enabled]" <?php checked( empty( $limitations['ip']['enabled'] ), false ); ?>>
				<?php _e( 'Block re-vote by IP', TP_TD ); ?>
			</label>

			&nbsp;&mdash;&nbsp;
			<a href="#" data-tp-toggle="limitations-ip-advanced"><?php _e( 'Advanced', TP_TD ); ?></a>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/block-by-ip', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced" data-tp-toggleable="limitations-ip-advanced">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-ip-timeout">
				<?php _e( 'IP timeout (minutes)', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The minimum required time to clear the IP from the database.', TP_TD ); ?>">?</span>
			</label>
			<input id="totalpoll-settings-limitations-ip-timeout" type="number" name="totalpoll[settings][limitations][ip][timeout]" min="0" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $limitations['ip']['timeout'] ) ); ?>">

			<p class="totalpoll-feature-tip"><?php _e( '0 means permanent.', TP_TD ); ?></p>
		</div>

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-votes-per-ip">
				<?php _e( 'Votes per ip', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( "The maximum votes that can be received from the same ip.", TP_TD ); ?>">?</span>
			</label>
			<input id="totalpoll-settings-limitations-votes-per-ip" type="number" name="totalpoll[settings][limitations][ip][votes_quota_per_ip]" min="1" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $limitations['ip']['votes_quota_per_ip'] ) ); ?>">
		</div>

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-ip-filter">
				<?php _e( 'Filter list', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Filter IPs by the following list.', TP_TD ); ?>">?</span>
			</label>
			<textarea id="totalpoll-settings-limitations-ip-filter" rows="6" name="totalpoll[settings][limitations][ip][filter]" class="settings-field-input widefat"><?php echo esc_textarea( $limitations['ip']['filter'] ); ?></textarea>

			<p class="totalpoll-feature-tip"><?php _e( 'IP Per line.', TP_TD ); ?></p>

			<p class="totalpoll-feature-tip"><?php _e( '"+" before IP means white-listed / "-" means black-listed / "*" means wildcard', TP_TD ); ?></p>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/block-by-ip-advanced', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][direct][enabled]" <?php checked( empty( $limitations['direct']['enabled'] ), false ); ?>>
				<?php _e( 'Block vote via direct link', TP_TD ); ?>

				<span class="totalpoll-feature-details" title="<?php esc_attr_e( "Whether visitors can vote directly by visiting a link. You can disable this feature in case you want to include vote links inside an email campaign for example.", TP_TD ); ?>">?</span>
			</label>

		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/block-direct-link-vote', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][membership][enabled]" data-tp-toggle="limitations-membership-advanced" <?php checked( empty( $limitations['membership']['enabled'] ), false ); ?>>
				<?php _e( 'Require membership', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/require-membership', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $limitations['membership']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="limitations-membership-advanced">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-membership-type">
				<?php _e( 'Membership type', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The membership type that can vote.', TP_TD ); ?>">?</span>
			</label>
			<select id="totalpoll-settings-limitations-membership-type" name="totalpoll[settings][limitations][membership][type][]" class="settings-field-input widefat" multiple>
				<?php foreach ( get_editable_roles() as $role => $details ): ?>
					<option value="<?php echo esc_attr( $role ); ?>" <?php selected( in_array( $role, $limitations['membership']['type'] ), true ); ?>><?php echo translate_user_role( $details['name'] ); ?></option>
				<?php endforeach; ?>
			</select>

			<p class="totalpoll-feature-tip"><?php _e( 'Hold Control/Command for multiple selection.', TP_TD ); ?></p>
		</div>

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][membership][once][enabled]" <?php checked( empty( $limitations['membership']['once']['enabled'] ), false ); ?>>
				<?php _e( 'Allow only one vote per member', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/require-membership-advanced', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][captcha][enabled]" data-tp-toggle="limitations-captcha-advanced" <?php checked( empty( $limitations['captcha']['enabled'] ), false ); ?>>
				<?php _e( 'Require reCaptcha', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/require-captcha', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $limitations['captcha']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="limitations-captcha-advanced">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-captcha-site-key">
				<?php _e( 'Site Key', TP_TD ); ?>
			</label>
			<input type="text" id="totalpoll-settings-limitations-captcha-site-key" name="totalpoll[settings][limitations][captcha][site_key]" class="settings-field-input widefat"
			       value="<?php echo empty( $limitations['captcha']['site_key'] ) ? '' : esc_attr( $limitations['captcha']['site_key'] ); ?>">
		</div>

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-limitations-captcha-site-secret">
				<?php _e( 'Secret Key', TP_TD ); ?>
			</label>
			<input type="text" id="totalpoll-settings-limitations-captcha-site-secret" name="totalpoll[settings][limitations][captcha][site_secret]" class="settings-field-input widefat"
			       value="<?php echo empty( $limitations['captcha']['site_secret'] ) ? '' : esc_attr( $limitations['captcha']['site_secret'] ); ?>">
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/require-captcha-advanced', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][quota][enabled]" data-tp-toggle="limitations-quota-advanced" <?php checked( empty( $limitations['quota']['enabled'] ), false ); ?>>
				<?php _e( 'Limit by quota', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/limited-by-quota', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $limitations['quota']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="limitations-quota-advanced">
		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Quota (votes)', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The maximum number of votes that can be received in this poll.', TP_TD ); ?>">?</span>
			</label>
			<input type="number" name="totalpoll[settings][limitations][quota][votes]" min="0" step="1" class="settings-field-input widefat" value="<?php echo esc_attr( absint( $limitations['quota']['votes'] ) ); ?>">
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/limited-by-quota-advanced', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][limitations][date][enabled]" data-tp-toggle="limitations-date-advanced" <?php checked( empty( $limitations['date']['enabled'] ), false ); ?>>
				<?php _e( 'Limit by date', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/limited-by-date', $limitations, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $limitations['date']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="limitations-date-advanced">
		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'Start date', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The date users will be able to vote.', TP_TD ); ?>">?</span>
			</label>
			<input type="text" data-tp-field-date name="totalpoll[settings][limitations][date][start]" min="0" step="1" class="settings-field-input widefat" value="<?php echo empty( $limitations['date']['start'] ) ? '' : esc_attr( date( 'm/d/Y H:i', (int) $limitations['date']['start'] ) ); ?>">
		</div>

		<div class="settings-field">
			<label class="settings-field-label">
				<?php _e( 'End date', TP_TD ); ?>
				<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'The date users won\'t be able to vote.', TP_TD ); ?>">?</span>
			</label>
			<input type="text" data-tp-field-date name="totalpoll[settings][limitations][date][end]" min="0" step="1" class="settings-field-input widefat" value="<?php echo empty( $limitations['date']['end'] ) ? '' : esc_attr( date( 'm/d/Y H:i', (int) $limitations['date']['end'] ) ); ?>">
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/limited-by-date-advanced', $limitations, $this->poll ); ?>

	</div>

	<?php if ( $context == 'poll' ): ?>
		<div class="settings-item" <?php echo $context == 'options' ? 'hidden' : ''; ?>>
			<input type="hidden" name="totalpoll[settings][limitations][unique_id]" value="<?php echo esc_attr( $limitations['unique_id'] ); ?>">

			<div class="settings-field">
				<label>
					<input type="checkbox" name="totalpoll[settings][limitations][unique_id]" value="<?php echo esc_attr( current_time( 'timestamp' ) ); ?>">
					<?php _e( 'Regenerate poll unique ID', TP_TD ); ?>
					&nbsp;&mdash;&nbsp;
					<?php _e( 'Current:', TP_TD ); ?> <?php echo $limitations['unique_id']; ?>
					<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Useful when you want to reset voting.', TP_TD ); ?>">?</span>
				</label>
			</div>

			<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/regenerate-unique-id', $limitations, $this->poll ); ?>

		</div>
	<?php endif; ?>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/limitations/after', $limitations, $this->poll ); ?>

</div>