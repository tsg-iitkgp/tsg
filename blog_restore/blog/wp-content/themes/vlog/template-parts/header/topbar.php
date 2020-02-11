<div class="vlog-top-bar">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">

				<?php if( $top_l = vlog_get_option('header_top_l')): ?>
					<div class="vlog-slot-l">
						<?php get_template_part( 'template-parts/header/elements/'. $top_l ); ?>  
					</div>
				<?php endif; ?>

				<?php if( $top_c = vlog_get_option('header_top_c')): ?>
					<div class="vlog-slot-c">
						<?php get_template_part( 'template-parts/header/elements/'. $top_c ); ?> 
					</div>
				<?php endif; ?>

				<?php if( $top_r = vlog_get_option('header_top_r')): ?>
					<div class="vlog-slot-r">
						<?php get_template_part( 'template-parts/header/elements/'. $top_r ); ?> 
					</div>
				<?php endif; ?>
				
			</div>
		</div>
	</div>
</div>