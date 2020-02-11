<div class="vlog-link-pages">
		<?php global $page, $numpages; ?>

		<span class="vlog-link-pages-label h5"><?php printf( __vlog( 'page_of' ), $page, $numpages ); ?></span>
		
		<?php if ( $page == 1 ) : ?>
			<?php echo _wp_link_page( $numpages ).'<i class="fa fa-chevron-left"></i></a>'; ?>
		<?php endif; ?>

		<?php wp_link_pages( array( 'before' => '', 'after' => '', 'next_or_number' => 'next', 'nextpagelink'     => '<i class="fa fa-chevron-right"></i>',
		'previouspagelink' => '<i class="fa fa-chevron-left"></i>' ) ); ?>

		<?php if ( $page == $numpages ) : ?>
			<?php echo _wp_link_page( 1 ).'<i class="fa fa-chevron-right"></i></a>'; ?>
		<?php endif; ?>

</div>