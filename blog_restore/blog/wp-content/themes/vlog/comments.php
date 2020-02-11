<?php if ( post_password_required() ) { return; } ?>

<?php if ( comments_open() || get_comments_number() ) : ?>

	<div id="comments" class="vlog-comments">

		<?php
			ob_start();
			comments_number( __vlog( 'no_comments' ), __vlog( 'one_comment' ), __vlog( 'multiple_comments' ) );
			$comments_title = ob_get_contents();
			ob_end_clean();

			echo vlog_module_heading(
				array(
					'title' => '<h4>'.$comments_title.'</h4>',
					'actions' => get_comment_pages_count() > 1 && get_option( 'page_comments' ) ? paginate_comments_links( array( 'echo' => false ) ) : ''
				)
			);
	
			comment_form(
				array(
					'title_reply' => '',
					'label_submit' => __vlog( 'comment_submit' )
				)
			);
		?>

		<?php if ( have_comments() ) : ?>

			<ul class="comment-list">
			<?php $args = array(
				'avatar_size' => 60,
				'reply_text' => __vlog( 'comment_reply' )
			); ?>
				<?php wp_list_comments( $args ); ?>
			</ul>
		<?php endif; ?>

	</div>

<?php endif; ?>