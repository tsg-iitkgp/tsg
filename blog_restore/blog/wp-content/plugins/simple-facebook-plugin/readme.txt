=== Simple Facebook Plugin ===

Contributors: topdevs, fornyhucker
Tags: social, facebook, fb, fb like, like box, likebox, page plugin, widget, shortcode, responsive, template tag, sidebar, fb page plugin
Requires at least: 2.8
Tested up to: 4.7.3
Stable tag: 1.5
License: GPLv2 or later

Lets you easily embed and promote any Facebook Page on your website. Your visitors can like, share, see Page events or even sand a message without having to leave your site.

== Description ==

= Description =

Simple Facebook Plugin enables Facebook Page admins to promote their Pages and embed a simple feed of content from a Page into any WordPress blog. The Facebook **Page Plugin** enables users to:

* See how many users already like this Page, and which of their friends like it too
* Read recent posts from the Page
* Like the Page with one click, without needing to visit the Page
* See Page Events
* Send messages to your Page\*

\**To enable messaging on your Facebook page go to your Page Settings. In the row Messages check Allow people to contact my Page privately by showing the Message button*

You can easily integrate Like Box using WordPress Widgets and Shortcodes. Visit [Plugin Page](http://topdevs.net/simple-facebook-plugin/ "See 'Simple Facebook Plugin' Page") for more info and examples.

= Our Premium Plugins =

* [Smart Grid Gallery](http://topdevs.net/smart-grid-gallery/ "See plugin demo") - Responsive WordPress Gallery
* [Smart Product Viewer](http://topdevs.net/smart-product-viewer/ "See plugin demo") - 360º Animation Plugin
* [Smart Before After Viewer](http://topdevs.net/before-after-viewer/ "See plugin demo") - Image Comparison Plugin
* [Smart Photo Gallery](http://topdevs.net/smart-photo-gallery/ "See plugin demo") - Responsive WordPress Gallery

Visit our [CodeCanyon Portfolio](http://codecanyon.net/user/topdevs/portfolio?ref=topdevs "Our Plugins on CodeCanyon") to see plugins reviews and prices.


== Installation ==
**Installation**

1. Upload `simple-facebook-plugin` directory to your `/wp-content/plugins` directory
1. Activate plugin in WordPress admin

**Customization**

1. In WordPress dashboard, go to **Appearance > Widgets**. 
1. Drag and Drop **SFP - Facebook Page Plugin** into your sidebar.
1. Click triangle near **SFP - Facebook Page Plugin** header.
1. Enter your Facebook Page URL (not your profile URL).
1. Choose width, height and other options you like.

**or**

Use `[sfp-page-plugin]` shortcode inside your post or page. This shortcode support all default parametrs:


* url - any Fan Page URL (not your personal page!)
* width - number (min 280, max 500)
* height - number
* hide_cover - *true* or *false*
* show_facepile - *true* or *false*
* small_header - *true* or *false*
* timeline - *true* or *false*
* events - *true* or *false*
* messages - *true* or *false*
* locale - valid language code (e.g. *en_US* or *es_MX*) see [.xml file](http://www.facebook.com/translations/FacebookLocales.xml "Facebook locales XML") with all Facebook locales


If you want Page Plugin *320 pixels width* and *showing posts* you need to use it next way:

`[sfp-page-plugin width=320 show_posts=true url=http://www.facebook.com/yourPageName]`

**or**

Use `sfp_page_plugin()` template tag in your theme files.

`<?php if ( function_exists("sfp_page_plugin") ) {
	$args = array(
		'url'			=> 'http://www.facebook.com/topdevs.net',
		'width'		=> '300',
		'hide_cover'=> true,
		'locale'		=> 'en_US'
	);
	sfp_page_plugin( $args );
} ?>`

== Frequently Asked Questions ==

= I see the message “Error: Not a valid Facebook Page url.”. What am I doing wrong? =

Page Plugin is only for Pages and **not** for Profiles, Events and Groups.

== Screenshots ==

1. Widget in the dashboard.
2. Simple Widget on your website.
3. Widget with posts on your website.

== Changelog ==

= 1.5 =
* Added Events and Messages tabs
* Added Small Header Option
* Fixed PHP7 Warning and Notice messages
* Removed deprecated Like Box widget

= 1.4.1 =
* Redirect issue fixed

= 1.4 =
* Deprecated "Like Box" replaced with new Facebook "Page Plugin"

= 1.3 =
* Add-on support added

= 1.2.2 =
* Option to show Like Box with no border changed to native Facebook data-show-border=false

= 1.2.1 =
* Added option to show Like Box with no border
* Added Norwegian(bokmal) locale to widget

= 1.2 =
Plugin structure reorganized. Shortcode and template tag functionality added

= 1.1 =
More than 20 Facebook Locales added