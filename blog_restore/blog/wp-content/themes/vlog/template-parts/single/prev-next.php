<?php if( strpos( vlog_get_option( 'single_prev_next'), 'below' ) !== false ) : ?>

	<?php 
		$prev_next = vlog_get_prev_next_posts();
	?>

	<?php if( !empty($prev_next['prev']) || !empty( $prev_next['next']) ) : ?>

		<nav class="vlog-prev-next-nav">

				<?php if ( !empty($prev_next['prev']) ): ?>

					<div class="vlog-prev-link">
						<a href="<?php echo esc_url( get_permalink( $prev_next['prev'] ) );?>">
							<span class="vlog-pn-ico"><i class="fa fa fa-chevron-left"></i><span><?php echo __vlog( 'prev_post' ); ?></span></span>
							<span class="vlog-pn-link"><?php echo get_the_title( $prev_next['prev'] );?></span>
						</a>

					</div>

				<?php endif; ?>

				<?php if ( !empty( $prev_next['next']) ): ?>

					<div class="vlog-next-link">
						<a href="<?php echo esc_url( get_permalink( $prev_next['next'] ) ); ?>">
							<span class="vlog-pn-ico"><span><?php echo __vlog( 'next_post' ); ?></span><i class="fa fa fa-chevron-right"></i></span>
							<span class="vlog-pn-link"><?php echo get_the_title( $prev_next['next'] );?></span>
						</a>
					</div>

				<?php endif; ?>

		</nav>

	<?php endif; ?>

<?php endif; ?>