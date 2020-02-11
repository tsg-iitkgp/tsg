<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-settings-wrapper">
	<h2 class="totalpoll-h2"><?php $context == 'poll' ? _e( 'Settings', TP_TD ) : _e( 'Default settings', TP_TD ); ?></h2>

	<?php

	$helpers = Totalpoll::instance( 'helpers' );

	$limitations = $helpers->parse_args(
		$this->poll->settings( 'limitations' ),
		$this->defaults['limitations']
	);

	$results = $helpers->parse_args(
		$this->poll->settings( 'results' ),
		$this->defaults['results']
	);

	$choices = $helpers->parse_args(
		$this->poll->settings( 'choices' ),
		$this->defaults['choices']
	);

	$fields = $helpers->parse_args(
		array_filter( (array) $this->poll->settings( 'fields' ), 'is_array' ),
		$this->defaults['fields']
	);

	$design = $helpers->parse_args(
		$this->poll->settings( 'design' ),
		$this->defaults['design']
	);

	$design['preset'] = $helpers->parse_args(
		get_option( '_tp_options_template_defaults_' . $this->poll->settings( 'design', 'template', 'name' ), array() ),
		$design['preset']
	);

	$screens = $helpers->parse_args(
		$this->poll->settings( 'screens' ),
		$this->defaults['screens']
	);

	$logs = $helpers->parse_args(
		$this->poll->settings( 'logs' ),
		$this->defaults['logs']
	);

	$notifications = $helpers->parse_args(
		$this->poll->settings( 'notifications' ),
		$this->defaults['notifications']
	);

	?>

	<div class="totalpoll-tabs-container settings-container" data-tp-tabs>
		<div class="totalpoll-tabs settings-tabs">
			<?php
			$tabs = apply_filters( 'totalpoll/filters/admin/editor/tabs/settings',
				array(
					array(
						'slug' => 'limitations',
						'name' => __( 'Limitations', TP_TD ),
						'icon' => 'dashicons dashicons-admin-network',
					),
					array(
						'slug' => 'results',
						'name' => __( 'Results', TP_TD ),
						'icon' => 'dashicons dashicons-chart-bar',
					),
					array(
						'slug' => 'choices',
						'name' => __( 'Choices', TP_TD ),
						'icon' => 'dashicons dashicons-menu',
					),
					array(
						'slug' => 'custom-fields',
						'name' => __( 'Custom fields', TP_TD ),
						'icon' => 'dashicons dashicons-feedback',
					),
					array(
						'slug' => 'design',
						'name' => __( 'Design', TP_TD ),
						'icon' => 'dashicons dashicons-admin-appearance',
					),
					array(
						'slug' => 'screens',
						'name' => __( 'Screens', TP_TD ),
						'icon' => 'dashicons dashicons-welcome-view-site',
					),
					array(
						'slug' => 'logs',
						'name' => __( 'Logs', TP_TD ),
						'icon' => 'dashicons dashicons-tag',
					),
					array(
						'slug' => 'notifications',
						'name' => __( 'Notifications', TP_TD ),
						'icon' => 'dashicons dashicons-email-alt',
					),
				),
				$this->poll
			);

			foreach ( $tabs as $tab_index => $tab ):
				?>
				<a href="#" class="<?php echo $tab_index === 0 ? 'active' : ''; ?>" data-tp-tab="<?php echo esc_attr( $tab['slug'] ); ?>"><span class="<?php echo esc_attr( $tab['icon'] ); ?>"></span><?php echo $tab['name']; ?></a>
				<?php
			endforeach;
			?>
		</div>
		<div class="totalpoll-tabs-content settings-tabs-content" data-tp-toggleables>
			<?php
			foreach ( $tabs as $tab ):
				if ( file_exists( TP_PATH . "includes/admin/editor/settings/{$tab['slug']}.php" ) ):
					include "settings/{$tab['slug']}.php";
				else:
					do_action( "totalpoll/actions/admin/editor/tabs/content/settings/{$tab['slug']}", $this->poll );
				endif;
			endforeach;
			?>
		</div>
	</div>
</div>