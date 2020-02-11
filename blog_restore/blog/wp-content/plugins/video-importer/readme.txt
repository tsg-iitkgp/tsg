=== Video Importer ===
Contributors: sutherlandboswell, refactoredco
Donate link: http://wie.ly/u/donate
Tags: videos, automatic, import, importer, youtube, vimeo
Requires at least: 3.3
Tested up to: 4.2
Stable tag: 1.6.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save time and hassle by automatically importing videos from your favorite sources on YouTube and Vimeo directly into your WordPress blog.

== Description ==

Easily define all the video sources you like to post on your WordPress blog and let Video Importer take care of the rest. In addition to importing past videos when you create a new source, Video Importer will check your sources for any new videos once per hour.

Adding a new source isn't just easy, it's also flexible enough to meet your needs. Choose between YouTube or Vimeo, choosing the type of feed, and then entering the username or ID. You can also set the post type, add categories and tags, choose the author and more for each source you're importing.

Works with:

**YouTube**

* User Uploads
* Playlists (limited support)

**Vimeo**

* User Uploads
* Groups
* Channels

= Video Importer Pro =

Development of [Video Importer Pro](https://refactored.co/plugins/video-importer) is underway! Buy today and get access to all the new features as they become available.

= Video Thumbnails =

Fully compatible with our [Video Thumbnails](https://refactored.co/plugins/video-thumbnails) plugin.

== Installation ==

1. Upload the `/video-importer/` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit the settings page and enter your YouTube and/or Vimeo API keys

== Frequently Asked Questions ==

= How do I get a YouTube API key? =

We have created an easy to follow [tutorial on creating a YouTube API key](http://wie.ly/u/ytkey).

= How often are videos imported? =

Videos are imported once per hour, but due to the way WordPress schedules events, the automatic import is only triggered when a page on your site gets loaded.

= Why can't I import tags from YouTube? =

YouTube no longer publicly provides video tags.

== Changelog ==

= 1.6.2 =
* Improved the "Import Now" feature

= 1.6.1 =
* Included YouTube API key tutorial and improved readme.txt
* Bugfix

= 1.6 =
* Video Importer is now free with [Video Importer Pro](https://refactored.co/plugins/video-importer) being released as a free upgrade for existing users
* Switched to the YouTube Data API v3 (requires creating a free API key in the [Google Developers Console](https://console.developers.google.com/project))
* Code refactoring

= 1.5.1 =
* Fixed potential bug caused by retrieving YouTube feeds over HTTPS
* Better handling of errors related to retrieving YouTube feeds
* Log the number of sources found

= 1.5 =
* Added a column for the last import date for each source
* Reduced potential confusion by hiding filters and bulk actions at the top of the source list
* Fixed a bug caused by changes in WordPress 4.1 that caused automatic hourly imports to fail

= 1.4.2 =
* Video Importer pages are now all contained under a single admin menu
* Users with import permissions now have access to the bulk import tool

= 1.4.1 =
* Fixed bug that caused sources created or last modified before version 1.3 to always import as drafts

= 1.4 =
* Added log capabilities (leave disabled unless troubleshooting)
* Included instructions to enter license key when an update is available
* Clear update cache automatically when settings are saved

= 1.3 =
* Added a post status option
* Added hooks for developer use
* Fixed bug where the scheduled import action might disappear
* Fixed bug in bulk import tool
* Smarter API error handling (if a provider has an API error other sources from that provider will be skipped temporarily)
* Import sources starting with the least recently imported
* Minor bugfixes

= 1.2 =
* Performance improvements

= 1.1 =
* Added a bulk video import page in the tools section

= 1.0.1 =
* Updated menu item to match the new admin design in WordPress 3.8

= 1.0 =
* Initial release