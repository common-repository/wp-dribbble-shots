=== WP Dribbble Shots ===
Contributors: kevinlangleyjr
Donate link: http://ubergeni.us/
Tags: dribbble, json, widget, shots
Requires at least: 2.8
Tested up to: 3.3
Stable tag: trunk

Adds a template function, get_the_shots(), which returns the latest shots of the Dribbble user and adds a widget to cycle through the results.

== Description ==

Adds a template function, get_the_shots(), which returns the latest shots of a Dribbble user.

You can use the WP Dribble Shot Widget that is bundled into this plugin and uses <a href="http://jquery.malsup.com/cycle/">jQuery Cycle</a> to cycle the latest shots.  This, as well as the example.php, just display a basic usage of the functionality of the plugin and is included to help you get started integrating into your theme.

All of the available attributes of each shot is detailed within the <a href="http://dribbble.com/api#get_player_shots">Dribbble API</a> and can be viewed there and used with this plugin.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload entire post-type-converter directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Insert your Dribbble User ID into the text box at the bottom of the General Settings Page in the WP Admin.

== Changelog ==

= 0.1 =
* First Version

= 0.2 =
* Fixing caching
* Adding example.php as an example of the basic usage of the plugin.

= 0.3 =
* Fixing compatibility for PHP 5.2

= 0.4 =
* Fixing the error I introduced in 0.3 and actually fixing the PHP 5.2 error

= 0.5 =
*Fixing transient typo
