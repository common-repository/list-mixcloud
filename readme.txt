=== List Mixcloud ===
Contributors: Shmuel83
Tags: mixcloud, music, musique, list, widget, plugin, extension, podcast, stream, audio, mp3, listen, preaching, divi
Requires at least: 2.7.0
Tested up to: 5.8.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

List MixCloud to show last or all podcast from MixCloud.

== Description ==

This plugin allowed to add MixCloud player.
MixCloud is a online music streaming service that allows for the listening and distribution of radio shows, DJ mixes and podcasts, which are crowdsourced by its registered users.

If you want to view podcasts from your user account or another, this plugin is for you.
This plugin needs no data except the name of the user for whom you want to view the podcasts : https://www.mixcloud.com/useraccount/
No need for login, password or API code
If you want show your account, you must create an account on [https://www.mixcloud.com](https://www.mixcloud.com), that's free.

= ShortCode =
`[listmixcloud channel='EgliseEvangéliqueDeTOURS' widget='mini' playlist='1' mode='1' autoplay='1' style='Light' width='100%' hide_artwork='0'][/listmixcloud]`

None of attributes are required.

* Channel => Name of Channel that you want show : https://www.mixcloud.com/Channel/ | EgliseEvangéliqueDeTOURS
* widget => Type of MixCloud widget mini\classic\picture  | mini
* playlist => Show title list of audio instead of list of players. Show only one player
* mode => Number of podcast to show. 0 to infinite, 1,2,... | 0=infinite
* autoplay => Play automaticaly : 1 to auto, nothing or other to | 0=no play
* Style => Style of MixCloud Widget : Light\Black | Light
* width => Width in Pixel or % of widget. For Type "Picture" widget, that's height&width  | 100%
* hide_artwork => Configure show of Artwork :0=show, 1=hide | 0=show

Modify height of "mini" & "classis" is not implemented in this plugin, but if you have need that, ask me.

= Widget =
In your Widget Tab, add List Mixcloud Widget and modify and save form under this widget.

That's not official MixCloud plugin

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)

Compatible with DIVI

== Screenshots ==

1. Add last podcast with ShortCode
2. Add all list of podcasts with Widget
3. Example to show plugin
4. Playlist option

== Changelog ==

= 1.4 =
* Debug Widget

= 1.3 =
* Debug player with DIVI

= 1.2 =
* Show error if mixcloud server or internet connection is down.
* Debug when user not choice option : "Show only one widget and playlist". Just 1 widget was show.
* If you want help to translate in your language, that's now possible. Thank you for your help.
* For wordpress widget, help with autocompletion to add your channel and show number of playlist found.
* Improvement Playlist when user select an other audio (In 1.1 version, a popup was show when user choice an other audio)

= 1.1.0 =
* Debug javascript

= 1.1 =
* Added an playlist attribut "playlist" in shortcode and widget. If is used, this plugin show list of audio instead of list of mixcloud widget. Show only one widget.
* Debug an error with old PHP version.
