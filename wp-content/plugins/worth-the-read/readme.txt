=== Plugin Name ===
Contributors: brianmcculloh
Tags: reading, length, progress, reading time, scroll, scroll progress, reading progress
Requires at least: 3.8
Tested up to: 4.7.3
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An adjustable progress meter showing how much of the post/page the user has scrolled through, and a read time commitment label near the post titles.

== Description ==

A very unobtrusive and light-weight reading progress bar indicator showing the user how far scrolled through the current post or page they are. You can control placement and color of the progress bar, and you can choose whether it includes just the main content or also the comments.

The progress bar only displays once the user begins scrolling the page so it is as unobtrusive as possible. Once the user stops scrolling or scrolls down past the content the progress bar subtly mutes until it is needed again.

There is also a reading time commitment feature that you can separately enable. Control the placement (above or below title, or above content), style, and whether it displays on posts and/or pages. Uses 200wpm as the metric for average reading time.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/worth-the-read` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Worth The Read screen to configure the plugin
4. Enable the plugin by selecting the Enable checkbox on the plugin settings screen.


== Frequently Asked Questions ==

= Why isn't the progress bar showing up? =

Make sure you enabled it in the Worth The Read settings page and that you're actually viewing a single post or page on your site (not your archive page, for instance). The bar won't display unless you have actually scrolled down into your main content. So if you have stuff going on at the top of your page above your post content (sliders, content panels, ads, etc.) the progress bar will remain hidden until it becomes relevant.

If the height of your post content is less than the height of the visible page, the progress bar will not display since the user already knows how much content there is. 

The functionality is javascript-based, so if you have a javascript error caused by something else like another plugin or your theme, it could affect the progress bar from displaying.

= How much control do I have over the look and feel of the progress bar? =

You can control the foreground color, the background color, and the transparency of the plugin. You can also separately control the background color of the comments portion (if enabled).

= How does it work? =

WordPress action hooks are used to insert small html tags above and below your post/page content and comments. jQuery is used to target those tags and use them to calculate distances on window scroll, and then the actual progress bar is animated accordingly.

= Why do you say it's "unobtrusive"? =

The plugin is as minimally distracting visually as it can be while still being easy to find. It auto-mutes any time the user does not need to visually reference it. Technically speaking, the html tags added to the DOM and corresponding CSS are very minimal and will not have any affect on the rest of the page DOM or any other plugins or your theme.

== Changelog ==

= 1.2.1 =
* Scripts/styles no longer load on homepage if progress bar is not set to display on the homepage
* Comments div anchor only renders where applicable

= 1.2 =
* Added new time commitment feature
* Added custom post types compatibility
* Added home page compatibility
* Added disable for touch devices feature
* Added placement offset feature
* Added muted opacity feature (was previously locked at .5) 
* Improved top placement to work better with WordPress admin bar on various screen sizes
* Changed “width” setting label to “thickness” to be more intuitive
* Changed “mute” setting label to “fixed opacity” to be more intuitive
* Fixed php notices that displayed while wp_debug was turned on

= 1.1 =
* Added ability to display progress bar on posts and pages, instead of only posts
* Added new settings to adjust width and opacity of progress bar
* Added new setting to choose whether progress bar stays muted on scroll
* Improved calculations of progress bar scroll placement when Include Comments is on
* Fixed a few text strings that weren't wrapped in gettext function (i18n)

= 1.0.2 =
* Added settings link directly to plugin page

= 1.0.1 =
* Improved detection of comments

= 1.0 =
* Initial release

== Upgrade Notice ==

There are no upgrade notices at this time.
