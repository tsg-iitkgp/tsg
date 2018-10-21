<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'kubrick'); ?></p> 
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
<h3 id="comments" class="col s12">Comments</h3>

<div class="col offset-m1">
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	
	<ol class="commentlist">
		<?php wp_list_comments();?>
	</ol>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<?php else : // this is displayed if there are no comments so far ?>
		
		<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
		
		<?php else : // comments are closed ?>
			<!-- If comments are closed. -->
			<p class="nocomments"><?php _e('Comments are closed.', 'kubrick'); ?></p>
			
			<?php endif; ?>
			<?php endif; ?>
			
			
			<?php if ( comments_open() ) : ?>
			
			<div id="respond" class="row">
				
				<h3><?php comment_form_title( __('Leave a Reply', 'kubrick'), __('Leave a Reply for %s' , 'kubrick') ); ?></h3>
				
				<div id="cancel-comment-reply"> 
					<small><?php cancel_comment_reply_link() ?></small>
				</div> 
				
				<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
				<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'kubrick'), wp_login_url( get_permalink() )); ?></p>
				<?php else : ?>
				
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
				
						<?php if ( is_user_logged_in() ) : ?>
						
						<div class="input-field col s12"><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', 'kubrick'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'kubrick'); ?>"><?php _e('Log out &raquo;', 'kubrick'); ?></a></div>
						
						<?php else : ?>
						
						<div class="input-field col s12 m6"><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
						<label for="author"><?php _e('Name', 'kubrick'); ?> <?php if ($req) _e("", "kubrick"); ?></label></div>
						
						<div class="input-field col s12 m6"><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
						<label for="email"><?php _e('Mail (will not be published)', 'kubrick'); ?> <?php if ($req) _e("", "kubrick"); ?></label></div>
						
						<?php endif; ?>
						
						<!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>', 'kubrick'), allowed_tags()); ?></small></p>-->
						
						<div class="input-field col s12">
							<textarea name="comment" id="comment" cols="58" rows="10" class="materialize-textarea" tabindex="4"></textarea>
							<label for="comment">Your Thoughts</label>
						</div>
						
						<div class="input-field col s12"><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'kubrick'); ?>" />
							<?php comment_id_fields(); ?> 
						</div>
					<?php do_action('comment_form', $post->ID); ?>
					
				</form>
				
				<?php endif; // If registration required and not logged in ?>
			</div>
			
			<?php endif; // if you delete this the sky will fall on your head ?>
			
</div>