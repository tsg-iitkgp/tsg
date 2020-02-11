<div class="totalpoll-settings-wrapper">
	<h2 class="totalpoll-h2"><?php _e( 'Options', TP_TD ); ?></h2>

	<div class="totalpoll-tabs-container settings-container" data-tp-tabs>
		<div class="totalpoll-tabs settings-tabs">
			<?php
			$tabs = apply_filters( 'totalpoll/filters/admin/options/tabs',
				array(
					array(
						'slug' => 'general',
						'name' => __( 'General', TP_TD ),
						'icon' => 'dashicons dashicons-admin-generic',
					),
					array(
						'slug' => 'expressions',
						'name' => __( 'Expressions', TP_TD ),
						'icon' => 'dashicons dashicons-admin-site',
					),
					array(
						'slug' => 'sharing',
						'name' => __( 'Sharing', TP_TD ),
						'icon' => 'dashicons dashicons-share',
					),
					array(
						'slug' => 'advanced',
						'name' => __( 'Advanced', TP_TD ),
						'icon' => 'dashicons dashicons-admin-settings',
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
				if ( file_exists( TP_PATH . "includes/admin/pages/options/{$tab['slug']}.php" ) ):
					include TP_PATH . "includes/admin/pages/options/{$tab['slug']}.php";
				else:
					do_action( "totalpoll/actions/admin/settings/tabs/content/{$tab['slug']}", $this->poll );
				endif;
			endforeach;
			?>
		</div>
	</div>
</div>
