=== Languages Frontend Display ===
Tags: qTranslate,qTranslate-x,language,hide,disable,frontend
Donate link: http://waterproof-webdesign.info/donate
Contributors: jhotadhari
Tested up to: 4.7.2
Requires at least: 3.9
Stable tag: trunk
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

qTranslate-X extension. Enable/disable languages on frontend


== Description ==
		
This Plugin is an extension for [qTranslate-X](https://wordpress.org/plugins/qtranslate-x/) (and will not work alone).

You can exclude some languages on frontend. Check the plugins settings page.

The excluded languages will not appear in language chooser.
In case someone wants to access an excluded language, he/she/it/they will be redirected to the default language. 


qTranslate-X option "Detect Browser Language" and  "URL Modification Mode -> Hide URL language information for default language" has to be false.

tested with qTranslate-X version 3.4.6.8.

= Thanks for beautiful ressoucres =
* [CMB2](https://github.com/WebDevStudios/CMB2)
* [Integration CMB2-qTranslate](https://github.com/jmarceli/integration-cmb2-qtranslate)

== Installation ==
Requirements:

* [qTranslate-X](https://wordpress.org/plugins/qtranslate-x/)

Upload and install this Plugin in the same way you\'d install any other plugin.

== Screenshots ==
1. Settings Page

== Changelog ==

= 0.0.4 =
bug fix: text domain was not loaded;
abort if functions caled directly;
Edit readme;

= 0.0.3 =
Bug fix: some lost whitespace characters found their way between the closing and opening php tags. Resulted in a 'headers already sent' error on some servers.

= 0.0.2 =
added '(default language)' to settings multicheck

= 0.0.1 =
hurray, first stable version!

