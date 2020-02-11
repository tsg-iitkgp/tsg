<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="browse-download-wrapper" data-tp-toggleables>
	<h2 class="totalpoll-h2"><?php _e( 'Browse', TP_TD ); ?></h2>

	<div class="totalpoll-tabs-container totalpoll-tabs-vertical">
		<div class="totalpoll-tabs" data-tp-tabs>
			<?php
			$tabs = apply_filters( 'totalpoll/filters/admin/editor/tabs/browser',
				array(
					array(
						'slug' => 'statistics',
						'name' => __( 'Statistics', TP_TD ),
						'icon' => 'dashicons dashicons-chart-pie',
					),
					array(
						'slug' => 'logs',
						'name' => __( 'Logs', TP_TD ),
						'icon' => 'dashicons dashicons-tag',
					),
					array(
						'slug' => 'submissions',
						'name' => __( 'Submissions', TP_TD ),
						'icon' => 'dashicons dashicons-feedback',
					),
				),
				$this->poll
			);

			foreach ( $tabs as $tab_index => $tab ):
				?>
				<a href="#" class="<?php echo $tab_index === 0 ? 'active' : ''; ?>" data-tp-tab="browse-<?php echo esc_attr( $tab['slug'] ); ?>"><span class="<?php echo esc_attr( $tab['icon'] ); ?>"></span><?php echo $tab['name']; ?></a>
				<?php
			endforeach;
			?>
		</div>
		<div class="totalpoll-tabs-content" data-tp-tabs-content>
			<?php
			foreach ( $tabs as $tab ):
				if ( file_exists( TP_PATH . "includes/admin/editor/browse/{$tab['slug']}.php" ) ):
					include "browse/{$tab['slug']}.php";
				else:
					do_action( "totalpoll/actions/admin/editor/tabs/content/browser/{$tab['slug']}", $this->poll );
				endif;
			endforeach;
			?>
		</div>
	</div>
</div>