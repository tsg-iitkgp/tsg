<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-containable-handle" data-tp-containable-handle="<?php echo $choice_id; ?>">
	<?php echo $choice_type_label; ?>: <span class="totalpoll-containable-handle-title" data-tp-containable-preview="<?php echo $choice_id; ?>"></span>
	<button class="button button-small totalpoll-containable-remove" type="button" data-tp-containable-remove="<?php echo $choice_id; ?>"><?php _e( 'Remove', TP_TD ); ?></button>
	<label class="totalpoll-containable-votes">
		<?php _e( 'Votes', TP_TD ); ?>
		<input
			type="text"
			placeholder="<?php _e( 'Votes', TP_TD ); ?>"
			name="totalpoll[choices][<?php echo $choice_index; ?>][votes]"
			data-rename="totalpoll[choices][{{new-index}}][votes]"
			value="<?php echo $choice['votes']; ?>"
			data-tp-containable-votes="<?php echo $choice_id; ?>"
		>
	</label>

	<div class="totalpoll-containable-visibility button button-small" title="<?php esc_attr_e( 'Choice visibility', TP_TD ); ?>">
		<label>
			<input
				class="totalpoll-containable-visibility-visible"
				type="radio"
				name="totalpoll[choices][<?php echo $choice_index; ?>][content][visible]"
				data-rename="totalpoll[choices][{{new-index}}][content][visible]"
				<?php checked( $choice_visible, true ); ?>
				value="1"
			>
			<span class="dashicons dashicons-hidden"></span>
		</label>
		<label>
			<input
				class="totalpoll-containable-visibility-hidden"
				type="radio"
				name="totalpoll[choices][<?php echo $choice_index; ?>][content][visible]"
				data-rename="totalpoll[choices][{{new-index}}][content][visible]"
				value="0"
				<?php checked( $choice_visible, false ); ?>
			>
			<span class="dashicons dashicons-visibility"></span>
		</label>
	</div>
	<?php if ( ! $choice_direct ): ?>
		<a class="totalpoll-containable-direct-link button button-small"
		   target="_blank"
		   onclick="prompt('', this.href);return false;"
		   data-rename="<?php
		   echo add_query_arg(
			   array(
				   'totalpoll' => array(
					   'action'  => 'vote',
					   'view'    => 'vote',
					   'page'    => 1,
					   'label'   => isset( $choice['content']['label'] ) ? $choice['content']['label'] : '',
					   'choices' => array(
						   '{{new-index}}',
					   ),
				   ),
			   ),
			   get_permalink()
		   );

		   ?>"
		   data-rename-attribute="href"
		   href="#"
		   title="<?php esc_attr_e( 'Direct vote link', TP_TD ); ?>">
			<span class="dashicons dashicons-format-links"></span>
		</a>
	<?php endif; ?>


	<?php do_action( 'totalpoll/actions/admin/editor/choice/handle', $choice_index, $choice_id, $choice_type, $choice ); ?>
</div>