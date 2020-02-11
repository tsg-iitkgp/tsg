<li class="vlog-actions-button vlog-watch-later">
	
	<?php if( vlog_get_option('watch_later_ajax') ) : ?>
		<span>
			<i class="fv fv-watch-later"></i>
		</span>
	<?php else: ?>
		<?php vlog_load_watch_later(); ?>
	<?php endif;?>
</li>