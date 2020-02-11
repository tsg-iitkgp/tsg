=== WordPress Database Reset ===

Contributors: mousesports
Tags: wordpress, database, reset, restore, setup, developer, theme, default, secure
License: GPL2
Requires at least: 4.2
Tested up to: 4.3.1
Stable tag: 3.0.2

A plugin that allows you to skip the 5 minute installation and reset WordPress's database back to its original state.

== Description ==

**Overview**

The WordPress Database Reset plugin allows you to reset the database back to its default settings without having to go through the WordPress 5 minute installation.

**Features**

* Extremely fast one click process to reset the WordPress database
* Choose to reset the entire database or specific database tables
* Secure and super simple to use
* Prefer the command line? Reset the database in one command
* Excellent for theme and plugin developers who need to clean the database of any unnecessary content

**Command Line**

Once activated, you can use the WordPress Database Reset plugin with [WordPress CLI](http://wp-cli.org/).
Full documentation for the commands can be found on [the code repository](https://github.com/chrisberthe/wordpress-database-reset#command-line)

**Support Forum**

Create a new post in the [WordPress Database Reset support forum](https://wordpress.org/support/plugin/wordpress-database-reset).

**Want to help out?**

* Provide new language translations - [read this tutorial](http://weblogtoolscollection.com/archives/2007/08/27/localizing-a-wordpress-plugin-using-poedit/) or use the [online PO Editor](https://poeditor.com/).
* Help develop the plugin by forking [the code repository](https://github.com/chrisberthe/wordpress-database-reset) on GitHub.
* Want to help others that might be having issues? [Answer questions on the support forum](https://wordpress.org/support/plugin/wordpress-database-reset).
* If you love this plugin and would like to make a donation: 1. You're awesome and 2. You can find the donate button on the plugin page.

== Screenshots ==
1. The WordPress Database Reset plugin page

== Changelog ==
= 3.0.2 =
* Fix for plugin page not showing up in tools menu (on some hosting providers)
* Update how session tokens were being restored
* Remove unnecessary nonce
* Bump 'requires at least' to version 4.2
* Change 'theme_data' to 'theme_plugin_data'

= 3.0.1 =
* Fix plugin disabled after update, thanks to Ulrich Pogson
* Update the pot file

= 3.0.0 =
* Completely re-written from scratch
* Add extended WP_CLI command class
* Clean up admin interface
* Remove unnecessary help tabs
* Submit button is now deactivated until user inputs security code
* Add PayPal donation button
* Remove outdated localization files
* Update the text domain to match slug for translate.wordpress.org

= 2.3.2 =
* Add option to keep active theme, thanks to Ulrich Pogson
* Adhere to WordPress PHP coding syntax standards
* Delete the user session and recreate it
* Separate the backup_tables method into two new methods
* Reset only WP tables and not custom tables
* French language updates, thanks to Fx Benard
* Fix for undefined variable: backup_tables

= 2.3.1 =
* Fixed bug where reactivate plugins div was not displaying on 'options' table select

= 2.3 =
* Removed deprecated function $wpdb->escape(), replaced with esc_sql()
* Add German translation, thanks to Ulrich Pogson
* Updated screenshot-1.png
* Renamed default localization file
* Fixed broken if conditional during code clean up for version 2.2

= 2.2 =
* Fixed scripts and styles to only load on plugin page
* Formatted code to meet WordPress syntax standards

= 2.1 =
* Replaced 3.3 deprecated get_userdatabylogin() with get_user_by()
* Updated deprecated add_contextual_help() with add_help_tab()
* Small change in condition check for backup tables
* Removed custom _rand_string() with core wp_generate_password()
* Added Portuguese translation - thanks to Fernando Lopes

= 2.0 =
* Added functionality to be able to select which tables you want to reset, rather than having to reset the entire database.
* Added bsmSelect for the multiple select.
* Modified screenshot-1.png.
* Fixed redirect bug
* 'Reactivate current plugins after reset' only shows if the options table is selected from the dropdown.

= 1.4 =
* Made quite a few changes to the translation files
* Renamed french translation file for plugin format, not theme format
* Optimized (until potential version 2.0)

= 1.3 =
* Replaced reactivation option for all currently active plugins (not just this plugin)
* Updated language files

= 1.2 =
* Added capability to manually select whether or not plugin should be reactivated upon reset
* Modified class name to avoid potential conflicts with WordPress core
* Modified wp_mail override
* Removed deprecated user level for WordPress 3.0+
* Fixed small bug where if admin user did not have admin capabilities, it would tell the user they did

= 1.0 =
* First version
