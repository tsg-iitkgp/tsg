<?php

$title =  apply_filters('widget_title', $instance['title'] );

echo $before_widget;

if ( !empty($title) ) {
	echo $before_title . $title . $after_title;
}

?>

<?php $coauthors = get_coauthors(); ?>

<?php foreach ($coauthors as $author): ?>
		
	<?php $author_link = esc_url( get_author_posts_url( $author->ID, $author->user_nicename) ); ?>

	<div class="mks-co-authors-wrapper">
	
		<?php if($instance['display_avatar']) : ?>
			<?php
			 	if($instance['link_to_avatar']){
			 		$pre_avatar = '<a href="'. $author_link .'">';
			 		$post_avatar = '</a>';
			 	} else {
			 		$pre_avatar = '';
			 		$post_avatar = '';
			 	}
					echo $pre_avatar. get_avatar( $author->user_email, $instance['avatar_size'] ) . $post_avatar;
				?>
			<?php endif; ?>

		<?php if( $instance['display_name'] ) : ?>
		  <?php
		  	if($instance['link_to_name']){
			 		$pre_name = '<a href="'. $author_link .'">';
			 		$post_name = '</a>';
			 	} else {
			 		$pre_name = '';
			 		$post_name = '';
			 	}
				echo '<h3>' . $pre_name . $author->display_name . $post_name. '</h3>';
			?>
		<?php endif; ?>

		<?php if($instance['display_desc']) : ?>
			<?php echo wpautop( $this->trim_chars( $author->description, $instance['limit_chars'] ) ); ?>
		<?php endif; ?>
			
		<?php if( $instance['display_all_posts'] && $instance['link_text'] ) : ?>
			<div class="mks_autor_link_wrap"><a href="<?php echo $author_link; ?>" class="mks_author_link"><?php echo $instance['link_text']; ?></a></div>
		<?php endif; ?>

	</div>

<?php endforeach;
echo $after_widget;