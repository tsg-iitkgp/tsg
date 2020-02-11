<?php

/* Font styles */

$main_font = vlog_get_font_option( 'main_font' );
$h_font = vlog_get_font_option( 'h_font' );
$nav_font = vlog_get_font_option( 'nav_font' );


/* Top header styles */

$color_header_top_bg = esc_attr( vlog_get_option( 'color_header_top_bg' ) );
$color_header_top_txt = esc_attr( vlog_get_option( 'color_header_top_txt' ) );
$color_header_top_acc = esc_attr( vlog_get_option( 'color_header_top_acc' ) );


/* Middle header styles */

$color_header_bg = esc_attr( vlog_get_option( 'color_header_main_bg' ) );
$color_header_txt = esc_attr( vlog_get_option( 'color_header_main_txt' ) );
$color_header_acc = esc_attr( vlog_get_option( 'color_header_main_acc' ) );
$header_height = esc_attr( vlog_get_option( 'header_height' ) );


/* Bottom header styles */

$color_header_bottom_bg = esc_attr( vlog_get_option( 'color_header_bottom_bg' ) );
$color_header_bottom_txt = esc_attr( vlog_get_option( 'color_header_bottom_txt' ) );
$color_header_bottom_acc = esc_attr( vlog_get_option( 'color_header_bottom_acc' ) );


/* Sticky header styles */

$sticky_colors_from = vlog_get_option('header_sticky_colors');

$color_header_sticky_bg = esc_attr( vlog_get_option( 'color_header_'.$sticky_colors_from.'_bg' ) );
$color_header_sticky_txt = esc_attr( vlog_get_option( 'color_header_'.$sticky_colors_from.'_txt' ) );
$color_header_sticky_acc = esc_attr( vlog_get_option( 'color_header_'.$sticky_colors_from.'_acc' ) );


/* General styles */

$content_layout = vlog_get_option( 'content_layout' );
$body_background = vlog_get_bg_option( 'body_background' );
$color_content_bg = esc_attr( vlog_get_option( 'color_content_bg' ) );
$color_content_title = esc_attr( vlog_get_option( 'color_content_title' ) );
$color_content_txt = esc_attr( vlog_get_option( 'color_content_txt' ) );
$color_content_acc = esc_attr( vlog_get_option( 'color_content_acc' ) );
$color_content_meta = esc_attr( vlog_get_option( 'color_content_meta' ) );


/* Highlight styles */

$color_highlight_bg = esc_attr( vlog_get_option( 'color_highlight_bg' ) );
$color_highlight_txt = esc_attr( vlog_get_option( 'color_highlight_txt' ) );


/* Footer styles */

$color_footer_bg = esc_attr( vlog_get_option( 'color_footer_bg' ) );
$color_footer_txt = esc_attr( vlog_get_option( 'color_footer_txt' ) );
$color_footer_acc = esc_attr( vlog_get_option( 'color_footer_acc' ) );

/* Cover  styles */
$cover_h = esc_attr( vlog_get_option( 'cover_h' ) );

?>



body,
#cancel-comment-reply-link,
.vlog-wl-action .vlog-button {
  <?php if( $content_layout == 'boxed') : ?>
		<?php echo $body_background; ?>
  <?php endif; ?>
  color: <?php echo $color_content_txt; ?>;
  font-family: <?php echo $main_font['font-family']; ?>;
  font-weight: <?php echo $main_font['font-weight']; ?>;
  <?php if ( isset( $main_font['font-style'] ) && !empty( $main_font['font-style'] ) ):?>
  	font-style: <?php echo $main_font['font-style']; ?>;
  <?php endif; ?>
}

.vlog-body-box{
	background-color:  <?php echo $color_content_bg; ?>;
}

/* Typography styles */

h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6,
blockquote,
.vlog-comments .url,
.comment-author b,
.vlog-site-header .site-title a,
.entry-content thead td,
#bbpress-forums .bbp-forum-title, #bbpress-forums .bbp-topic-permalink {
  font-family: <?php echo $h_font['font-family']; ?>;
  font-weight: <?php echo $h_font['font-weight']; ?>;
  <?php if ( isset( $h_font['font-style'] ) && !empty( $h_font['font-style'] ) ):?>
  font-style: <?php echo $h_font['font-style']; ?>;
  <?php endif; ?>
}

.vlog-site-header a,
.dl-menuwrapper li a{
  font-family: <?php echo $nav_font['font-family']; ?>;
  font-weight: <?php echo $nav_font['font-weight']; ?>;
  <?php if ( isset( $nav_font['font-style'] ) && !empty( $nav_font['font-style'] ) ):?>
  font-style: <?php echo $nav_font['font-style']; ?>;
  <?php endif; ?>
}
/* Top Header */
.vlog-top-bar,
.vlog-top-bar .sub-menu{
    background-color: <?php echo $color_header_top_bg; ?>;
}
.vlog-top-bar ul li a,
.vlog-site-date{
    color: <?php echo $color_header_top_txt; ?>;
}
.vlog-top-bar ul li:hover > a{
    color: <?php echo $color_header_top_acc; ?>;
}

/* Main Header */

.vlog-header-middle{
  height: <?php echo $header_height; ?>px;
}
.vlog-site-header,
.vlog-header-shadow .vlog-header-wrapper,
.vlog-site-header .vlog-main-nav .sub-menu,
.vlog-actions-button .sub-menu,
.vlog-site-header .vlog-main-nav > li:hover > a,
.vlog-actions-button:hover > span,
.vlog-action-search.active.vlog-actions-button > span,
.vlog-actions-search input[type=text]:focus,
.vlog-responsive-header,
.dl-menuwrapper .dl-menu{
    background-color: <?php echo $color_header_bg; ?>;
}
<?php if( $header_bg = vlog_get_bg_option('background_header') ) : ?>
  .vlog-header-wrapper {
    <?php echo $header_bg; ?>
  }
<?php endif; ?>
.vlog-site-header,
.vlog-site-header .entry-title a,
.vlog-site-header a,
.dl-menuwrapper li a,
.vlog-site-header .vlog-search-form input[type=text],
.vlog-responsive-header,
.vlog-responsive-header a
.vlog-responsive-header .entry-title a,
.dl-menuwrapper button,
.vlog-remove-wl{
  color: <?php echo $color_header_txt; ?>;
}
.vlog-main-nav .sub-menu li:hover > a,
.vlog-main-nav > .current_page_item > a,
.vlog-main-nav .current-menu-item > a,
.vlog-main-nav li:not(.menu-item-has-children):hover > a,
.vlog-actions-button.vlog-action-search:hover span,
.dl-menuwrapper li a:focus,
.dl-menuwrapper li a:hover,
.dl-menuwrapper button:hover{
  color: <?php echo $color_header_acc; ?>;
}

.vlog-site-header .vlog-search-form input[type=text]::-webkit-input-placeholder {
   color: <?php echo $color_header_txt; ?>;
}

.vlog-site-header .vlog-search-form input[type=text]::-moz-placeholder {  /* Firefox 19+ */
    color: <?php echo $color_header_txt; ?>;
}

.vlog-site-header .vlog-search-form input[type=text]:-ms-input-placeholder {  
    color: <?php echo $color_header_txt; ?>;
}

/* Bottom Header */
.vlog-header-bottom{
  background: <?php echo $color_header_bottom_bg; ?>;  
}
.vlog-header-bottom,
.vlog-header-bottom .entry-title a,
.vlog-header-bottom a,
.vlog-header-bottom .vlog-search-form input[type=text]{
  color: <?php echo $color_header_bottom_txt; ?>;
}
.vlog-header-bottom .vlog-main-nav .sub-menu li:hover > a, 
.vlog-header-bottom .vlog-main-nav > .current_page_item > a, 
.vlog-header-bottom .vlog-main-nav .current-menu-item > a, 
.vlog-header-bottom .vlog-main-nav li:not(.menu-item-has-children):hover > a, 
.vlog-header-bottom .vlog-actions-button.vlog-action-search:hover span,
.vlog-header-bottom .entry-title a:hover,
.vlog-header-bottom .vlog-remove-wl:hover{
  color: <?php echo $color_header_bottom_acc; ?>;
}
.vlog-header-bottom .vlog-search-form input[type=text]::-webkit-input-placeholder {
   color: <?php echo $color_header_txt; ?>;
}

.vlog-header-bottom .vlog-search-form input[type=text]::-moz-placeholder {  /* Firefox 19+ */
    color: <?php echo $color_header_txt; ?>;
}

.vlog-header-bottom .vlog-search-form input[type=text]:-ms-input-placeholder {  
    color: <?php echo $color_header_txt; ?>;
}

/* Sticky Header */

.vlog-sticky-header.vlog-site-header,
.vlog-sticky-header.vlog-site-header .vlog-main-nav .sub-menu,
.vlog-sticky-header .vlog-actions-button .sub-menu,
.vlog-sticky-header.vlog-site-header .vlog-main-nav > li:hover > a,
.vlog-sticky-header .vlog-actions-button:hover > span,
.vlog-sticky-header .vlog-action-search.active.vlog-actions-button > span,
.vlog-sticky-header .vlog-actions-search input[type=text]:focus{
    background-color: <?php echo $color_header_sticky_bg; ?>;
}
.vlog-sticky-header,
.vlog-sticky-header .entry-title a,
.vlog-sticky-header a,
.vlog-sticky-header .vlog-search-form input[type=text],
.vlog-sticky-header.vlog-site-header a{
  color: <?php echo $color_header_sticky_txt; ?>;
}
.vlog-sticky-header .vlog-main-nav .sub-menu li:hover > a,
.vlog-sticky-header .vlog-main-nav > .current_page_item > a,
.vlog-sticky-header .vlog-main-nav li:not(.menu-item-has-children):hover > a,
.vlog-sticky-header .vlog-actions-button.vlog-action-search:hover span,
.vlog-sticky-header.vlog-header-bottom .vlog-main-nav .current-menu-item > a,
.vlog-sticky-header.vlog-header-bottom .entry-title a:hover,
.vlog-sticky-header.vlog-header-bottom  .vlog-remove-wl:hover {
  color: <?php echo $color_header_sticky_acc; ?>;
}

.vlog-header-bottom .vlog-search-form input[type=text]::-webkit-input-placeholder {
   color: <?php echo $color_header_sticky_txt; ?>;
}

.vlog-header-bottom .vlog-search-form input[type=text]::-moz-placeholder {  /* Firefox 19+ */
    color: <?php echo $color_header_sticky_txt; ?>;
}

.vlog-header-bottom .vlog-search-form input[type=text]:-ms-input-placeholder {  
    color: <?php echo $color_header_sticky_txt; ?>;
}

/* General */

a{
  color: <?php echo $color_content_acc; ?>; 
}
.meta-item,
.meta-icon,
.meta-comments a,
.vlog-prev-next-nav .vlog-pn-ico,
.comment-metadata a,
.widget_calendar table caption,
.widget_archive li,
.widget_recent_comments li,
.rss-date,
.widget_rss cite,
.widget_tag_cloud a:after,
.widget_recent_entries li .post-date,
.meta-tags a:after,
.bbp-forums .bbp-forum-freshness a{
    color: <?php echo $color_content_meta; ?>; 
}

.vlog-pagination .dots:hover, 
.vlog-pagination a, 
.vlog-post .entry-category a:hover,
a.meta-icon:hover,
.meta-comments:hover,
.meta-comments:hover a,
.vlog-prev-next-nav a,
.widget_tag_cloud a,
.widget_calendar table tfoot tr td a,
.vlog-button-search,
.meta-tags a,
.vlog-all-link:hover,
.vlog-sl-item:hover,
.entry-content-single .meta-tags a:hover,
#bbpress-forums .bbp-forum-title, #bbpress-forums .bbp-topic-permalink{
    color: <?php echo $color_content_txt; ?>; 
}
.vlog-count,
.vlog-button,
.vlog-pagination .vlog-button,
.vlog-pagination .vlog-button:hover,
.vlog-watch-later-count,
a.page-numbers:hover,
.widget_calendar table tbody td a,
.vlog-load-more a,
.vlog-next a,
.vlog-prev a,
.vlog-pagination .next,
.vlog-pagination .prev,
.mks_author_link,
.mks_read_more a,
.vlog-wl-action .vlog-button,
body .mejs-controls .mejs-time-rail .mejs-time-current,
.vlog-link-pages a{
  background-color: <?php echo $color_content_acc; ?>;
}
.vlog-pagination .uil-ripple-css div:nth-of-type(1),
.vlog-pagination .uil-ripple-css div:nth-of-type(2),
blockquote{
  border-color: <?php echo $color_content_acc; ?>;
}

.entry-content-single a,
#bbpress-forums .bbp-forum-title:hover, #bbpress-forums .bbp-topic-permalink:hover{
  color: <?php echo $color_content_acc; ?>;
}
.entry-content-single a:hover{
  color: <?php echo $color_content_txt; ?>;
}
.vlog-site-content,
.vlog-content .entry-content-single a.vlog-popup-img {
   background: <?php echo $color_content_bg; ?>;
}
.vlog-content .entry-content-single a.vlog-popup-img  {
  color: <?php echo $color_content_bg; ?>;
}
h1,h2,h3,h4,h5,h6,
.h1, .h2, .h3, .h4, .h5, .h6,
.entry-title a,
.vlog-comments .url,
.rsswidget:hover{
   color: <?php echo $color_content_title; ?>;
}
.widget ul li a{
  color: <?php echo $color_content_txt; ?>;
}
.widget ul li a:hover,
.entry-title a:hover,
.widget .vlog-search-form .vlog-button-search:hover,
.bypostauthor .comment-body .fn:before,
.vlog-comments .url:hover,
#cancel-comment-reply-link,
.widget_tag_cloud a:hover,
.meta-tags a:hover,
.vlog-remove-wl:hover{
  color: <?php echo $color_content_acc; ?>;
}
.entry-content p{
  color: <?php echo $color_content_txt; ?>;
}
.widget_calendar #today:after{
  background: <?php echo vlog_hex2rgba( $color_content_txt , 0.1); ?>
}
.vlog-button,
.vlog-button a,
.vlog-pagination .vlog-button,
.vlog-pagination .next,
.vlog-pagination .prev,
a.page-numbers:hover,
.widget_calendar table tbody td a,
.vlog-featured-info-2 .entry-title a,
.vlog-load-more a,
.vlog-next a,
.vlog-prev a,
.mks_author_link,
.mks_read_more a,
.vlog-wl-action .vlog-button,
.vlog-link-pages a,
.vlog-link-pages a:hover{
    color: #FFF;
}

#cancel-comment-reply-link, .comment-reply-link, .vlog-rm,
.vlog-mod-actions .vlog-all-link,
.vlog-slider-controls .owl-next, .vlog-slider-controls .owl-prev {
  color: <?php echo $color_content_acc; ?>; 
  border-color: <?php echo vlog_hex2rgba( $color_content_acc , 0.7); ?>
}
.vlog-mod-actions .vlog-all-link:hover,
.vlog-slider-controls .owl-next:hover, .vlog-slider-controls .owl-prev:hover {
  color: <?php echo $color_content_txt; ?>; 
  border-color: <?php echo vlog_hex2rgba( $color_content_txt , 0.7); ?>
}
.comment-reply-link:hover,
.vlog-rm:hover,
#cancel-comment-reply-link:hover{
  color: <?php echo $color_content_txt; ?>;
  border-color: <?php echo $color_content_txt; ?>;
}

/* Highlight */

.vlog-highlight .entry-category,
.vlog-highlight .entry-category a,
.vlog-highlight .meta-item a,
.vlog-highlight .meta-item span,
.vlog-highlight .meta-item,
.vlog-highlight  .widget_tag_cloud a:hover{
    color: <?php echo vlog_hex2rgba( $color_highlight_txt , 0.5); ?>;
}

.vlog-highlight {
    background: <?php echo $color_highlight_bg; ?>;
    color: <?php echo $color_highlight_txt; ?>;
    border: none;
}

.vlog-highlight .widget-title{
    border-bottom-color: <?php echo vlog_hex2rgba( $color_highlight_txt , 0.1); ?>;
}
.vlog-highlight .entry-title,
.vlog-highlight .entry-category a:hover,
.vlog-highlight .action-item,
.vlog-highlight .meta-item a:hover,
.vlog-highlight .widget-title span,
.vlog-highlight .entry-title a,
.widget.vlog-highlight ul li a,
.vlog-highlight.widget_calendar table tfoot tr td a,
.vlog-highlight .widget_tag_cloud a{
  color: <?php echo $color_highlight_txt; ?>;
}
.vlog-highlight .widget_calendar #today:after{
   background: <?php echo vlog_hex2rgba( $color_highlight_txt , 0.1); ?>;  
}
.widget.vlog-highlight  input[type=number], 
.widget.vlog-highlight input[type=text], 
.widget.vlog-highlight input[type=email], 
.widget.vlog-highlight input[type=url], 
.widget.vlog-highlight input[type=tel], 
.widget.vlog-highlight input[type=date], 
.widget.vlog-highlight input[type=password], 
.widget.vlog-highlight select, 
.widget.vlog-highlight textarea{
  background: #FFF;  
  color: #111;
  border-color: <?php echo vlog_hex2rgba( $color_highlight_txt , 0.1); ?>;
}
.vlog-highlight .vlog-button-search{
  color:#111;
}

/* Gray areas */

.vlog-bg-box,
.author .vlog-mod-desc,
.vlog-bg{
    background: <?php echo vlog_hex2rgba( $color_content_txt , 0.05); ?>;  
}

.vlog-pagination .current{
    background: <?php echo vlog_hex2rgba( $color_content_txt , 0.1); ?>;
}

/* Footer */
.vlog-site-footer{
    background: <?php echo $color_footer_bg; ?>;
    color: <?php echo $color_footer_txt; ?>;
}
.vlog-site-footer .widget-title,
.vlog-site-footer .widget_calendar table tbody td a,
.vlog-site-footer .widget_calendar table tfoot tr td a,
.vlog-site-footer .widget.mks_author_widget h3,
.vlog-site-footer  .mks_author_link,
.vlog-site-footer .vlog-button:hover,
.vlog-site-footer .meta-item a:hover,
.vlog-site-footer .entry-category a:hover {
  color: <?php echo $color_footer_txt; ?>;
}
.vlog-site-footer a,
.vlog-site-footer ul li a,
.vlog-site-footer .widget_calendar table tbody td a:hover,
.vlog-site-footer .widget_calendar table tfoot tr td a:hover{
  color: <?php echo vlog_hex2rgba( $color_footer_txt , 0.8); ?>;
}
.vlog-site-footer .meta-item a,
.vlog-site-footer .meta-item .meta-icon,
.vlog-site-footer .widget_recent_entries li .post-date,
.vlog-site-footer .meta-item{
  color: <?php echo vlog_hex2rgba( $color_footer_txt , 0.5); ?>;
}
.vlog-site-footer .meta-comments:hover,
.vlog-site-footer ul li a:hover,
.vlog-site-footer a:hover{
  color: <?php echo $color_footer_acc; ?>;
}
.vlog-site-footer .widget .vlog-count,
.vlog-site-footer .widget_calendar table tbody td a,
.vlog-site-footer a.mks_author_link,
.vlog-site-footer a.mks_author_link:hover,
.vlog-site-footer .widget_calendar table tbody td a:hover{
  color: <?php echo $color_footer_bg; ?>;
  background: <?php echo $color_footer_acc; ?>;
}
.vlog-site-footer .widget .vlog-search-form input[type=text],
.vlog-site-footer select{
   background: #FFF;
   color: #111;
   border:<?php echo $color_footer_txt; ?>;
}
.vlog-site-footer .widget .vlog-search-form .vlog-button-search{
  color: #111;
}
.vlog-site-footer .vlog-mod-actions .vlog-all-link, .vlog-site-footer .vlog-slider-controls .owl-next, .vlog-site-footer .vlog-slider-controls .owl-prev{
   color: <?php echo vlog_hex2rgba( $color_footer_txt , 0.8); ?>;
   border-color:  <?php echo vlog_hex2rgba( $color_footer_txt , 0.8); ?>;
}
.vlog-site-footer .vlog-mod-actions .vlog-all-link:hover, .vlog-site-footer .vlog-slider-controls .owl-next:hover, .vlog-site-footer .vlog-slider-controls .owl-prev:hover{
   color: <?php echo vlog_hex2rgba( $color_footer_txt , 1); ?>;
   border-color:  <?php echo vlog_hex2rgba( $color_footer_txt , 1); ?>;
}
.entry-content-single ul > li:before,
.vlog-comments .comment-content ul > li:before{
  color: <?php echo $color_content_acc; ?>;
}


input[type=number], input[type=text], input[type=email], input[type=url], input[type=tel], input[type=date], input[type=password], select, textarea,
.widget,
.vlog-comments,
.comment-list,
.comment .comment-respond,
.widget .vlog-search-form input[type=text],
.vlog-content .vlog-prev-next-nav,
.vlog-wl-action,
.vlog-mod-desc .vlog-search-form,
.entry-content table,
.entry-content td, .entry-content th,
.entry-content-single table,
.entry-content-single td, .entry-content-single th,
.vlog-comments table,
.vlog-comments td, .vlog-comments th{
  border-color: <?php echo vlog_hex2rgba( $color_content_txt , 0.1); ?>;
}
input[type=number]:focus, input[type=text]:focus, input[type=email]:focus, input[type=url]:focus, input[type=tel]:focus, input[type=date]:focus, input[type=password]:focus, select:focus, textarea:focus{
  border-color: <?php echo vlog_hex2rgba( $color_content_txt , 0.3); ?>;  
}
input[type=number], input[type=text], input[type=email], input[type=url], input[type=tel], input[type=date], input[type=password], select, textarea{
  background-color: <?php echo vlog_hex2rgba( $color_content_txt , 0.03); ?>;  
}
.vlog-button,
input[type="submit"],
.wpcf7-submit,
input[type="button"]{
  background-color: <?php echo $color_content_acc; ?>;
}
.vlog-comments .comment-content{
  color: <?php echo vlog_hex2rgba( $color_content_txt , 0.8); ?>;  
}

li.bypostauthor > .comment-body,
.vlog-rm {
  border-color: <?php echo $color_content_acc; ?>;
}
.vlog-ripple-circle{
  stroke: <?php echo $color_content_acc; ?>;
}

.vlog-cover-bg,
.vlog-featured-2 .vlog-featured-item,
.vlog-featured-3 .vlog-featured-item,
.vlog-featured-4 .owl-item,
.vlog-featured-5{
    height: <?php echo $cover_h; ?>px;
}
.vlog-fa-5-wrapper{
  height: <?php echo $cover_h - 72; ?>px;
}
.vlog-fa-5-wrapper .fa-item{
  height: <?php echo ( $cover_h - 144 ) / 2; ?>px;
}

/* WooCommerce classes */
<?php if ( vlog_is_woocommerce_active() ) { ?>
.woocommerce ul.products li.product .button,
.woocommerce ul.products li.product .added_to_cart,
body.woocommerce .button,
body.woocommerce-page .button,
.woocommerce .widget_shopping_cart_content .buttons .button,
.woocommerce div.product div.summary .single_add_to_cart_button,
.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce-page #payment #place_order,
.woocommerce #review_form #respond .form-submit input{
  color:#FFF;
  background-color: <?php echo $color_content_acc; ?>;    
}
.woocommerce div.product .woocommerce-tabs ul.tabs li.active a{
  border-bottom: 3px solid <?php echo $color_content_acc; ?>;
}
.product-categories li,
.product-categories .children li {
  color:<?php echo $color_content_meta; ?>;
}
.product-categories .children li {
  border-top: 1px solid <?php echo vlog_hex2rgba( $color_content_txt, 0.1); ?>; 
}
.product-categories li{
   border-bottom: 1px solid <?php echo vlog_hex2rgba( $color_content_txt, 0.1); ?>; 
}
.woocommerce .woocommerce-breadcrumb a{
  color: <?php echo $color_content_acc; ?>;
}
.woocommerce .woocommerce-breadcrumb a:hover{
  color: <?php echo $color_content_txt; ?>;
}
<?php } ?>

div.bbp-submit-wrapper button, #bbpress-forums #bbp-your-profile fieldset.submit button{
  color:#FFF;
  background-color: <?php echo $color_content_acc; ?>;    
}
.vlog-breadcrumbs a:hover{
  color: <?php echo $color_content_txt; ?>;
}
.vlog-breadcrumbs{
   border-bottom: 1px solid <?php echo vlog_hex2rgba( $color_content_txt, 0.1); ?>;
}
.vlog-special-tag-label{
  background-color: <?php echo vlog_hex2rgba( $color_content_acc, 0.5); ?>;  
}
.vlog-special-tag-label{
  background-color: <?php echo vlog_hex2rgba( $color_content_acc, 0.5); ?>;  
}
.entry-image:hover .vlog-special-tag-label{
  background-color: <?php echo vlog_hex2rgba( $color_content_acc, 0.8); ?>;    
}
<?php

/* Apply uppercase options */
$uppercase = vlog_get_option( 'uppercase' );
if ( !empty( $uppercase ) ) {
  foreach ( $uppercase as $text_class => $val ) {
    if ( $val ){
      echo '.'.$text_class.'{text-transform: uppercase;}';
    } else {
      echo '.'.$text_class.'{text-transform: none;}';
    }
  }
}

?>