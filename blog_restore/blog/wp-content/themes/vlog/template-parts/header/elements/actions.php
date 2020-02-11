<?php $actions = array_keys( array_filter( vlog_get_option( 'header_actions' ) ) );  ?>
<?php if ( !empty( $actions ) ): ?>
	<div class="vlog-actions-menu">
	<ul class="vlog-menu">
		<?php foreach ( $actions as $element ): ?>
			<?php get_template_part( 'template-parts/header/elements/' . $element ); ?>
		<?php endforeach; ?>
	</ul>
	</div>
<?php endif; ?>