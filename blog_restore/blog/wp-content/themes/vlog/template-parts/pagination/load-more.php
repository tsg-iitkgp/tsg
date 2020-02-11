<?php $more_link = get_next_posts_link( __vlog( 'load_more' ) ); ?>
<?php if ( !empty( $more_link ) ) : ?>
<nav class="vlog-pagination vlog-load-more">
		<?php echo $more_link; ?>
		<div class="vlog-loader">

		<div class="uil-ripple-css"><div></div><div></div></div>
				
		</div>
</nav>
<?php endif; ?>
