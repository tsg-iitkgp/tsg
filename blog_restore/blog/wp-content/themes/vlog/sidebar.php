<?php global $vlog_sidebar_opts; ?>

<?php if( $vlog_sidebar_opts['use_sidebar'] != 'none') : ?>

	<div class="vlog-sidebar <?php echo esc_attr('vlog-sidebar-'. $vlog_sidebar_opts['use_sidebar']); ?>">

		<?php if ( is_active_sidebar( $vlog_sidebar_opts['sidebar'] ) ) : ?>
				<?php dynamic_sidebar( $vlog_sidebar_opts['sidebar'] ); ?>
		<?php endif; ?>

		<?php if ( is_active_sidebar( $vlog_sidebar_opts['sticky_sidebar'] ) ) : ?>

				<div class="vlog-sticky">

					<?php dynamic_sidebar( $vlog_sidebar_opts['sticky_sidebar'] ); ?>

				</div>
		<?php endif; ?>

	</div>

<?php endif; ?>