<?php

/* This is global array of translation strings used for internal theme translation */

global $vlog_translate;

$vlog_translate = array(
	'no_videos_message' => array( 'text' => esc_html__( 'No videos yet!', 'vlog' ), 'desc' => 'No videos yet!' ),
	'watch_later_message' => array( 'text' => esc_html__( 'Click on "Watch later" to put videos here', 'vlog' ), 'desc' => 'Click on "Watch later" to put videos here' ),
	'no_comments' => array( 'text' => esc_html__( 'Add comment', 'vlog' ), 'desc' => 'Comment meta data (if zero comments)' ),
	'one_comment' => array( 'text' => esc_html__( '1 comment', 'vlog' ), 'desc' => 'Comment meta data (if 1 comment)' ),
	'multiple_comments' => array( 'text' => esc_html__( '% comments', 'vlog' ), 'desc' => 'Comment meta data (if more than 1 comments)' ),
	'views' => array( 'text' => esc_html__( 'views', 'vlog' ), 'desc' => 'Used in post meta data (number of views)' ),
	'min_read' => array( 'text' => esc_html__( 'min read', 'vlog' ), 'desc' => 'Used in post meta data (reading time)' ),
	'watch_later' => array( 'text' => esc_html__( 'Watch Later', 'vlog' ), 'desc' => 'Watch later action label' ),
	'watch_later_remove' => array( 'text' => esc_html__( 'Remove', 'vlog' ), 'desc' => 'Remove from watch later action label' ),
	'cinema_mode' => array( 'text' => esc_html__( 'Cinema Mode', 'vlog' ), 'desc' => 'Cinema mode action label' ),
	'label_video' => array( 'text' => esc_html__( 'Video', 'vlog' ), 'desc' => 'Video post format label' ),
	'label_audio' => array( 'text' => esc_html__( 'Audio', 'vlog' ), 'desc' => 'Audio post format label' ),
	'label_gallery' => array( 'text' => esc_html__( 'Gallery', 'vlog' ), 'desc' => 'Gallery post format label' ),
	'label_image' => array( 'text' => esc_html__( 'Image', 'vlog' ), 'desc' => 'Image post format label' ),
	'read_more' => array( 'text' => esc_html__( 'Read More', 'vlog' ), 'desc' => 'Label for read more link' ),
	'category' => array('text' => esc_html__('Category - ', 'vlog'), 'desc' => 'Category title prefix'),
	'serie' => array('text' => esc_html__('Playlist - ', 'vlog'), 'desc' => 'Serie (playlist) title prefix'),
	'tag' => array('text' => esc_html__('Tag - ', 'vlog'), 'desc' => 'Tag title prefix'),
	'author' => array('text' => esc_html__('Author - ', 'vlog'), 'desc' => 'Author title prefix'),
	'archive' => array('text' => esc_html__('Archive - ', 'vlog'), 'desc' => 'Archive title prefix'),
	'search_placeholder' => array('text' => esc_html__('Type here to search...', 'vlog'), 'desc' => 'Search placeholder text'),
	'search_results_for' => array('text' => esc_html__('Search Results For - ', 'vlog'), 'desc' => 'Title for search results template'),
	'newer_entries' => array('text' => esc_html__('Newer Entries', 'vlog'), 'desc' => 'Pagination (prev/next) link text'),
	'older_entries' => array('text' => esc_html__('Older Entries', 'vlog'), 'desc' => 'Pagination (prev/next) link text'),
	'previous_posts' => array('text' => esc_html__('Previous', 'vlog'), 'desc' => 'Pagination (numeric) link text'),
	'next_posts' => array('text' => esc_html__('Next', 'vlog'), 'desc' => 'Pagination (numeric) link text'),
	'load_more' => array('text' => esc_html__('Load More', 'vlog'), 'desc' => 'Pagination (load more) link text'),
	'related' => array('text' => esc_html__('You may also like', 'vlog'), 'desc' => 'Related posts area title'),
	'view_all' => array('text' => esc_html__('View all posts', 'vlog'), 'desc' => 'View all posts link text in author box'),
	'share_facebook' => array('text' => esc_html__('Facebook', 'vlog'), 'desc' => 'Facebook button label'),
	'share_twitter' => array('text' => esc_html__('Twitter', 'vlog'), 'desc' => 'Twitter button label'),
	'share_reddit' => array('text' => esc_html__('Reddit', 'vlog'), 'desc' => 'Reddit button label'),
	'share_gplus' => array('text' => esc_html__('Google+', 'vlog'), 'desc' => 'Google+ button label'),
	'share_pinterest' => array('text' => esc_html__('Pinterest', 'vlog'), 'desc' => 'Pinterest button label'),
	'share_email' => array('text' => esc_html__('Email', 'vlog'), 'desc' => 'Email button label'),
	'share_stumbleupon' => array('text' => esc_html__('StumbleUpon', 'vlog'), 'desc' => 'StumbleUpon button label'),
	'share_linkedin' => array('text' => esc_html__('LinkedIN', 'vlog'), 'desc' => 'LinkedIN button label'),
	'share_whatsapp' => array('text' => esc_html__('WhatsApp', 'vlog'), 'desc' => 'WhatsApp button label'),
	'prev_post' => array('text' => esc_html__('Previous', 'vlog'), 'desc' => 'Previous post label'),
	'next_post' => array('text' => esc_html__('Next', 'vlog'), 'desc' => 'Next post label'),
	'page_of' => array('text' => esc_html__('Page %s of %s', 'vlog'), 'desc' => 'Paginated/multi-page post navigation label'),
	'comment_submit' => array('text' => esc_html__('Submit Comment', 'vlog'), 'desc' => 'Comment form submit button label'),
	'comment_reply' => array('text' => esc_html__('Reply', 'vlog'), 'desc' => 'Comment reply label'),
	'404_title' => array('text' => esc_html__('404 error: Page not found', 'vlog'), 'desc' => '404 page title'),
	'404_text' => array('text' => esc_html__('The page that you are looking for does not exist on this website. You may have accidentally mistype the page address, or followed an expired link. Anyway, we will help you get back on track. Why not try to search for the page you were looking for:', 'vlog'), 'desc' => '404 page text'),
	'content_none' => array('text' => esc_html__('Sorry, there are no posts found on this page. Feel free to contact website administrator regarding this issue.', 'vlog'), 'desc' => 'Message when there are no posts on archive pages. i.e Empty Category'),
	'content_none_search' => array('text' => esc_html__('No results found. Please try again with a different keyword.', 'vlog'), 'desc' => 'Message when there are no search results.') 
);

?>