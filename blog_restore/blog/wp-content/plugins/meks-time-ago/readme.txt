=== Meks Time Ago ===
Contributors: mekshq, seebeen
Donate link: https://mekshq.com/
Tags: date, time, ago, custom, format, post, page, the_date, the_time
Requires at least: 3.7
Tested up to: 4.9
Stable tag: 1.1.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Automatically change your post date display to "time ago" format like 1 hour ago, 3 days ago, etc...

== Description ==

Meks Time Ago WordPress plugin will allow you to easily change your post date display to "time ago" format. For example, 1 hour ago, 2 weeks ago, 5 months, 4 days ago, etc... Several options are provided to suit your needs.

== Features ==

* Option to automatically override the_date and/or the_time WordPress functions to display date in "time ago" format
* Options to display "time ago" format only for post which are not older then specific time range
* Option to position "ago" word before actual value (helpful for "not English" WP installations which may naturally require different word ordering)

Meks Time Ago plugin is created by [Meks](https://mekshq.com)

== Live example? ==
You can see Meks Time Ago live example on our [Herald theme demo website](https://mekshq.com/demo/herald)

== Installation ==

1. Upload meks-time-ago.zip to plugins via WordPress admin panel or upload unzipped folder to your wp-content/plugins/ folder
2. Activate the plugin through the "Plugins" menu in WordPress
3. Go to Settings -> General to manage the options

== Frequently Asked Questions ==

For any questions, error reports and suggestions please visit https://mekshq.com/contact

== Screenshots ==

1. Options interface

== Changelog ==

= 1.1.5 =

* Added: Plugin now supports posts loaded via AJAX too
* Improved: Smarter date/time format detection in order to avoid replacing functions with a single format parameter (i.e. date function which displays Month only)


= 1.1.4 =
* Fixed: Modified (updated) date & time not calculated correctly

= 1.1.3 =
* Fixed: Plugin throwing notices when General settings are not saved (when WP_DEBUG is enabled)

= 1.1.2 =
* Added: Options to apply "time ago" format to Modified (updated) post date and time (Settings -> General)
* Fixed: Conflict with meta tags using date and time inside "head" element

= 1.1.1 =
* Fixed: Conflict with AMP WordPress plugin

= 1.1 =
* Added an option to rewrite/translate "ago" word

= 1.0 =
* Initial release