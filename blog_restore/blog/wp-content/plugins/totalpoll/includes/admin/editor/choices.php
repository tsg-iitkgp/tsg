<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-containables-container" data-tp-container data-tp-choices>
	<h2 class="totalpoll-h2"><?php _e( 'Choices', TP_TD ); ?></h2>
	<?php
	$from_wpml     = ! empty( $GLOBALS['sitepress'] ) && $GLOBALS['sitepress']->get_element_trid( $this->poll->id(), 'post_poll' );
	$from_polylang = ! empty( $_REQUEST['from_post'] ) || ( ! empty( $GLOBALS['polylang'] ) && count( pll_get_post_translations( $this->poll->id() ) ) > 1 );

	if ( $from_wpml || $from_polylang ): ?>
		<p class="update-nag"><?php _e( 'TotalPoll will synchronize votes between translations automatically. Therefore, choices must be in the exact order across all translations.', TP_TD ); ?></p>
	<?php endif; ?>
	<ul class="totalpoll-containables-types clearfix">
		<li>
			<button class="button" type="button" data-tp-containables-insert value="choices-text"><?php _e( 'Text', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="choices-image"><?php _e( 'Image', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="choices-video"><?php _e( 'Video', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="choices-audio"><?php _e( 'Audio', TP_TD ); ?></button>
		</li>
		<li>
			<button class="button" type="button" data-tp-containables-insert value="choices-html"><?php _e( 'Rich', TP_TD ); ?></button>
		</li>
		<?php do_action( 'totalpoll/actions/admin/editor/choices/types', $this->poll ); ?>
		<li class="alignright">
			<button class="button" type="button" data-tp-insert-bulk><?php _e( 'Bulk insertion', TP_TD ); ?></button>
			&nbsp;
			<button class="button button-danger" type="button" data-tp-reset-votes><?php _e( 'Reset votes', TP_TD ); ?></button>
		</li>
	</ul>
	<div class="totalpoll-hide" data-tp-bulk-container>
		<textarea data-tp-insert-bulk-choices rows="10" class="widefat" placeholder="<?php esc_attr_e( "One choice per line.\nRed\nGreen\nBlue", TP_TD ); ?>"></textarea>
		<button data-tp-insert-bulk-import type="button" class="button button-large button-primary widefat"><?php _e( 'Import' ); ?></button>
	</div>

	<ul class="totalpoll-containables" data-tp-containables data-tp-sortable data-toggle-on-insert="true">
		<?php
		foreach ( $this->poll->choices( false, false ) as $choice_index => $choice ):
			$choice_id      = str_replace( '.', '', microtime( true ) );
			$choice_visible = isset( $choice['content']['visible'] ) ? (bool) $choice['content']['visible'] : false;
			$choice_direct  = $this->poll->settings( 'limitations', 'direct', 'enabled' );
			if ( file_exists( TP_PATH . "includes/admin/editor/choices/{$choice['content']['type']}.php" ) ):
				include "choices/{$choice['content']['type']}.php";
			else:
				do_action( "totalpoll/actions/admin/editor/choices/item/{$choice['content']['type']}", $choice_index, $choice_id, $choice_visible, $choice_direct, $choice, $this->poll );
			endif;

		endforeach;
		?><?php do_action( 'totalpoll/actions/admin/editor/choices/items', $this->poll ); ?>
	</ul>

</div>