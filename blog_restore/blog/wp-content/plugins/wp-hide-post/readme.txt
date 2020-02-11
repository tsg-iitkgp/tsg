=== WP Hide Post ===
Contributors: scriptburn
Donate link: http://scriptburn.com/wp-hide-post/
Tags: SEO,hide,show,visbility,privacy,customization,sitemap,filter
Requires at least: 2.6
Tested up to: 4.8
Stable tag: 2.0.10
License: GPLv2

Control the visibility of items on your blog by making posts/pages hidden on some parts , while still visible in other parts and search engines.

== Description ==

This plugin excels in giving you full control over the visibility of your a post. By default, any post you add to your WordPress blog will become the topmost post, and will show up immediately on the front page in the first position, and similarly in category/tag/archive pages. Sometimes, you want to create a "low-profile" addition to your blog that doesn't belong on the front page, or maybe you don't want it to show up anywhere else in your blog except when you explicitly link to it. This plugin allows you to create such "hidden gems".

In particular, this plugin allows you to control the visibility of a **post** in various different views:

* The Front Page (Homepage, depending on your theme, this may not be relevant)
* The Category Page (listing the posts belonging to a category)
* The Tag Page (listing the posts tagged with a given tag)
* The Authors Page (listing the posts belonging to an author)
* The Archive Pages (listing the posts belonging to time period: month, week, day, etc..)
* The Search Results
* Feeds

The posts will disappear from the places you choose them to disappear. Everywhere else they will show up as regular posts. In particular, permalinks of the posts still work, and if you generate a sitemap, with something like the [Google XML Sitemaps](http://wordpress.org/extend/plugins/google-sitemap-generator/) the post will be there as well. This means that the content of your post will be indexed and searchable by search engines.

For a WordPress **page**, this plugin also allows you to control the visibility with two options:

* Hide a page on the front page (homepage) only.
* Hide a page everywhere in the blog (hiding the page in the search results is optional).

This means, technically, whenever pages are listed somewhere using the `get_pages` filter, this plugin will kick in and either filter it out or not according to the options you choose. The same rules apply regarding permalinks and sitemaps as they do for regular posts.

"WP Hide Post" plugin is a great tool in your arsenal for SEO optimization. It allows you to add plenty of content to your blog, without forcing you to change the nature and presentation of your front page, for example. You can now create content that you otherwise would be reluctant to add to your blog because it would show immediately on the front page, or somewhere else where it would not belong. It's a must-have feature of WordPress.

Please enjoy this plugin freely, comment and rate it profusely, and send me feedback and any ideas for new features.

== Installation ==

1. Upload the `wp-hide-post` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. That's it!! Now whenever you edit a post/page or create a new one, you will see a small panel on the bottom right of the screen that shows the applicable options.

== Frequently Asked Questions ==

= What does this plugin do? =

It enables you to create posts/pages that can be *hidden* (temporarily or permanently) from the homepage, feeds and/or other places. The post/page will remain accessible normally through other means, such as permalinks, archives, search, etc... and thus will remain visible to search engines.

= How can I make a post or a page private so that no one can see it? =

If you want to make a post/page completely private you don't need this plugin. WordPress supports options such as private and/or password-protected posts/pages out of the box.

= Can I make a post or a page *hidden* for a while, but then make it normal again? =

Yes. The *hidden* flags are just another set of attributes of a post/page. They can be added or removed at any time, just like editing anything else about the post.

= I have an idea to improve this feature further, what can I do? =

Please contact me on my blog [ScriptBurn.com](http://scriptburn.com/wp-hide-post/). I'm looking forward to hearing any suggestions.

= I just found something that doesn't look right, do I just sit on it? =

By all means no! Please report any bugs on my blog [ScriptBurn.com](http://scriptburn.com/wp-hide-post/). I'd really appreciate it. This is free software and I rely on the help of people like you to maintain it.

= I'm worried this could reduce my search engine ranking. Is it gonna? =

Not at all. On the contrary. All the content you include on your blog, even though it's not directly accessible from the homepage for example, it's still to be available when search engines crawl your site, and will remain linkable for those individuals that are interested in it. Furthermore, if you use some sitemap generation plugin (like the [Google XML Sitemaps](http://wordpress.org/extend/plugins/google-sitemap-generator/) plugin I use on my own [ScriptBurn.com](http://scriptburn.com/)) all the content will be published to web crawlers and will be picked up by search engines. In fact, this plugin will make your SEO more effective by allowing you to add content that you wouldn't otherwise want to show on your homepage.

= I used the 'WP low Profiler' plugin before. This one sounds just like it. =

This plugin is the new version of the 'WP low Profiler'. The name has been changed, but the functionality and codebase is identical. In fact, once you activate this plugin, it will upgrade the existing 'WP low Profiler' plugin and take its place. The last version of 'WP low Profiler', 2.0.3, corresponds to the first version of 'WP Hide Post', 1.0.3.

= I already have 'WP low Profiler' installed and activated. What's going to happen to it? =

'WP Hide Post' will take the place of 'WP low Profiler'. Once 'WP Hide Post' is activated, 'WP low Profiler' is deactivated and deleted. All its data is imported first, so you won't loose any data.

= I already have 'WP low Profiler' installed but it's not activated. What's going to happen to it? =

Ditto. It will be deleted. If you had any existing data (if you had it active before) the data will persist.

= Why did you change the name of 'WP low Profiler' 'to WP Hide Post'? =

'WP low Profiler' wasn't descriptive enough of the functionality of the plugin. Being 'low profile' could mean many things to many people. It was hard to find and many people who needed it didn't know it exists because of that.

== Screenshots ==

1. Closup showing the *Visibility Attributes* for posts. [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-1.png)

2. A small panel will appear whenever you are editing or creating a **normal post**. or *custom post* You can check one or more of the *Visibility Attributes* as needed. [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-2.png)

3. Another panel will appear whenever you click on *Quick Edit* in normal post or custom post list page. You can check one or more of the *Visibility Attributes* as needed.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-3.png)

4. Another panel will appear whenever you use *Bulk Edit* in normal post or custom post list page. You can check one or more of the *Visibility Attributes* as needed and they will applied to selected posts.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-4.png)

5. In normal post or custom post list page you can filter the posts acording to the hide option applied to them. You can also see all of the hide options applied to each post.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-5.png)

6. Closup showing the *Visibility Attributes* for pages. [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-6.png)

7. Another panel will appear whenever you are editing or creating a new **page**. You can check one or more of the *Visibility Attributes* as needed. Note that options for pages are different from those of posts. [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-7.png)

8. Another panel will appear whenever you click on *Quick Edit* in **page** list. You can check one or more of the *Visibility Attributes* as needed.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-8.png)

9. Another panel will appear whenever you use *Bulk Edit* **page** list. You can check one or more of the *Visibility Attributes* as needed and they will applied to selected pages.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-9.png)

10. In list page you can filter the pages acording to the hide option applied to them. You can also see all of the hide options applied to each page.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-10.png)

11. In **Settings->WP Hide Post** you can select custom post types where you hide post widget to appear.  [See Larger Version](http://scriptburn.com/wp-content/uploads/wp-hide-post/screenshot-11.png)


== Changelog ==

= 2.0.10 =
*Release Date - 3 Aug 2017*

Fix: An issue related to display custom post types in admin setting section of plugin


= 2.0.9 =
*Release Date - 3 Aug 2017*

Fix:A js bug that was causing post editor toolbar not appearing in admin panel
Fix: Missing semicolon in a javascript
Fix: A bug due to that not all custom post types were being displayed in plugin setting section(reported by William Goodwin) 

= 2.0.8 =
*Release Date - 1 Aug 2017*

Fix: A bug that was causing recurse error and throwing fatal error

= 2.0.7 =
*Release Date - 31 July 2017*

Fix: Renamed some conflicting class name with other plugins

= 2.0.6 =
*Release Date - 31 July 2017*

Fix: post was not hiding if the static page was selected as homepage
Fix: Compatibility with old version of PHP


= 2.0.5 =
*Release Date - 30 July 2017*

Fix: Compatibility with old version of PHP

= 2.0.4 =
*Release Date - 30 July 2017*

*Fix: An issue that prevented system info file download

= 2.0.3 =
*Release Date - 12 August 2016*

* Fixed "Check All" not working in Bulk edit page
* Added a function array_column for wordpress install which are running < PHP5.5.5

= 2.0.2 =
*Release Date - 12 August 2016*

* Fixed an issue which was displaying error when activating the plugin

= 2.0 =
*Release Date - 11 August 2016*

* Fix to include Blog page when Hide on the front page option is checked.
* Added Quick Edit option for post and page in post list page.
* Added Bulk Edit option for post and page in post list page.
* Added Search for hidden post option in post list page.
* Added a new column “Hidden On” in post list page.
* Added “Check all” Option in hide post widget to select all options at once in post or custom post type page.
* Added a setting option in setting page where you can select what on which post type you want to show hide post widget.
* Added a setting option in setting page where you can choose whether to show hide post widest in quick edit mode or not 


= 1.1.9 =
*Release Date - 16 July 2015*

* Compatibility with Wordpress 4.2.2

= 1.1.4 =
*Release Date - 24 October 2009*

* Added compatibility with Wordpress 2.8.5

= 1.1.3 =
*Release Date - 07 August 2009*

* Extended support for Wordpress 2.6

= 1.1.2 =
*Release Date - 07 August 2009*

* Bug fixes.

= 1.1.1 =
*Release Date - 05 August 2009*

* Reduce the number of SQL queries to hide pages to a single queries for all pages, rather than one query per page.

= 1.1.0 =
*Release Date - 04 August 2009*

* Bug fix: bulk update clears "Visibility Attributes". Split code into separate files.

= 1.0.4 =
*Release Date - 24 July 2009*

= 1.0.3 =
*Release Date - 24 July 2009*

* Initial public release of 'WP Hide Post' and deprecation of 'WP low Profiler'


* Minor bug fixes
== Development Blog ==

Please visit the plugin page at [ScriptBurn.com](http://scriptburn.com/wp-hide-post/), and feel free to leave feedback, bug reports and comments.

 == Upgrade Notice ==
 n/a
1