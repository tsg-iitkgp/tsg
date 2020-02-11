<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content active" data-tp-tab-content="browse-statistics">
	<div data-tp-statistics>
		<input type="hidden" data-tp-statistics-action value="totalpoll_browse_statistics">
		<?php
		$analyzed_percentage = TotalPoll::instance( 'admin/statistics' )->analyzed_percentage( $this->poll->id() );
		$statistics          = get_post_meta( $this->poll->id(), 'statistics', true );
		include 'statistics-cards.php';
		if ( empty( $statistics ) ):
			?>
			<div class="totalpoll-statistics-progress">
				<p><?php _e( 'Insufficient data to analyze.', TP_TD ); ?></p>
			</div>
			<?php
		elseif ( $analyzed_percentage == 100 ):

		else:
			?>
			<div class="totalpoll-statistics-progress">
				<div class="totalpoll-statistics-progress-bar" data-tp-statistics-progress-bar style="width: <?php echo $analyzed_percentage; ?>%;"></div>
			</div>
			<?php
		endif;

		?>
	</div>

	<div class="totalpoll-toolbar clearfix with-major-actions">
		<div class="alignright">
			<a href="<?php echo admin_url( "post.php?post={$this->poll->id()}&action=edit&print=1" ); ?>" target="_blank" class="button button-primary" type="button"><?php _e( 'Print', TP_TD ); ?></a>
			&nbsp;
			<a href="<?php echo admin_url( "post.php?post={$this->poll->id()}&action=edit&print=1" ); ?>" target="_blank" class="button" download="poll-<?php echo sanitize_title_with_dashes( $this->poll->question() ) . '-' . time(); ?>.html"><?php _e( 'Download', TP_TD ); ?></a>
			&nbsp;
			<?php _e( 'or', TP_TD ); ?>
			&nbsp;&nbsp;
			<button class="button" name="totalpoll[reset][statistics]" value="1"><?php _e( 'Reset', TP_TD ); ?></button>
		</div>
	</div>
</div>