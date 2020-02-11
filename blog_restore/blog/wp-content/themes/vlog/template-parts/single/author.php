<div id="vlog-author" class="vlog-author-box vlog-bg-box">

	<div class="vlog-author row">

		<div class="col-lg-2 col-md-3 col-sm-2 col-xs-12">
			<?php echo get_avatar( get_the_author_meta('ID'), 140, false, false, array('class' => 'vlog-rounded-photo') ); ?>
		</div>
		
		<div class="col-lg-10 col-md-9 col-sm-10 col-xs-12">
			
			<?php echo vlog_module_heading(array('title' => '<h4 class="h5 author-title">'.get_the_author_meta('display_name').'</h4>')); ?>
			<?php echo wpautop(get_the_author_meta('description')); ?>
			<?php echo vlog_get_author_links( get_the_author_meta('ID') ); ?>
		</div>

	</div>

</div>