=== Delicious Readings ===
Contributors: aldolat
Donate link: http://www.aldolat.it/wordpress/wordpress-plugins/delicious-readings/
Tags: delicious, readings, bookmarks, widget
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Publish your readings on your WordPress blog using your Delicious Bookmarks.

== Description ==

This plugin allows you to publish some of your Delicious bookmarks on your blog:
it retrieves the bookmarks from a specific tag and publishes them on your sidebar.

I could be useful, for example, to publish your readings on the Web.
Let's say that you read a webpage and bookmark it as "readings".
This plugin can get the bookmarks from the tag "readings" (or whatever it is) and display them on a widget in your sidebar.

The plugin may display for each tag:

* The title with link
* The description if any
* The date of the bookmark
* The tags assigned to the bookmark
* The link to the entire archive of that tag on Delicious

After the plugin's activation, you will have a new widget in Appearance / Widgets.

== Installation ==

This section describes how to install the plugin and get it working.

1. From your WordPress dashboard search the plugin Delicious Readings, install and activate it.
1. Add the new widget on your sidebar.
1. The only necessary thing to do is to add the feed of the tag on Delicious to retrieve.

== Screenshots ==

1. The dashboard panel to set up the widget
2. An example of rendered widget

== Frequently Asked Questions ==

= The rendered text on my blog is not similar to the screenshot =

You have to modify the style.css of yout theme to suit your needs.

= What link have I to insert in the widget? =

The link for the feed of a specific Delicious tag is like this: `http://delicious.com/v2/rss/USERNAME/TAG-NAME`
where `USERNAME` is your username on Delicious and `TAG-NAME` is the tag that collects all your bookmarks to be published.
So, for example, a link could be: `http://delicious.com/v2/rss/myusername/mytag`. Obviously adjust it to your real username ad tag.

== Changelog ==

= 1.1 =

* Moved the widget into a separate file.
* Fixed a typo in the widget panel.
* Fixed a bug in the "nofollow" value for rel attribute.
* Security focusing.

= 1.0 =
First release of the plugin.

== Upgrade Notice ==

No upgrade notice.
