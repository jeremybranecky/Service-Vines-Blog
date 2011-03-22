=== Plugin Name ===
Contributors: kubi23
Donate link: http://www.svenkubiak.de/yawasp-en/#donate
Tested up to: 2.7.1
Stable tag: 3.3
Requires at least: 2.6
Tags: wordpress, security, plugin, comment, comments, anti-spam, antispam, spam, spambot, spambots, protection

With the release of WordPress 2.8 development of Yawasp is discontinued!!! For further protection against automated Spambots please checkout the successor of Yawasp (NoSpamNX) or any other Anitspam Plugin!

== Description ==

With the release of WordPress 2.8 development of Yawasp is discontinued!!!

For further protection against automated Spambots please checkout the successor of Yawasp (NoSpamNX) or any other Anitspam Plugin!

== Installation ==

1. Backup your comments.php located in your template path (e.g. wp-content/themes/THEME)
2. Unzip Plugin
2. Copy complete yawasp-folder to wp-content/plugins
4. Activate plugin
5. Go to settings -> Yawasp in you WP-Admin
6. Try to automaticly change your comments.php
7. If 6. does not work, change your comments.php manualy by following the instructions on the Yawasp Homepage:

* [Yawasp Homepage English](http://www.svenkubiak.de/yawasp-en/)
* [Yawasp Homepage Deutsch](http://www.svenkubiak.de/yawasp/)

Done!

Note: You might want to remove (automatic or manual) the changes in comments.php after deactivation, but comments will work even
if you dont and Yawasp is deactivated.

== Frequently Asked Questions ==

= What is the difference to other anti-comment-spam plugins ? =

Comment Spam is blocked within the plugin. You don't see it and you don't have to moderate it.

= Does the plugin block Ping-/Trackback Spam as well ? =

No, the plugin focuses on Spambot-comment-spam only.

= What about false-positives ? =

Due to the core functionality of Yawasp false-postives are nearly impossible. There might be problems when using Cache-Plugins, but none have ever been reported. If you are uncertain, try puting catched Spambots in moderation queue or mark as Spam (Akismet or similar required). 

= Real-Users are reporting they recieve the message "Sorry, it seems you are a Spambot" ? =

Check you comments.php. This is very likely because your comments.php has not been setup correctly.

= I am in moderation mode and the counter is increasing but not comments are in the moderation queue ? =

As Yawasp is checking every comment before WordPress handles it, it is possible, that Yawasp catches a Spambot, counts is, but the WordPress comment handling does not accept the comment (e.g. missing Author). As a result, you have catched a spambot, but no comment. 

== Screenshots ==

1. Yawasp statistic on Dashboard
2. Yawasp settings

== Version History ==

* Version 3.3
	* Added notification Message for NoSpamNX
* Version 3.2
	* Fixed minor bug when using ip check
* Version 3.1
	* Fixed bug when comparing versions
	* Counter and table will not be removed when re-activating Plugin
	* Updated language file
	* Added Italian translation
* Version 3.0
	* Added compatibility to WordPress 2.7
	* Added function to mark catched Spambots as 'Spam' or put in moderation queue
	* Added additional options for blocking catched spambots 1 hour, 24 hours or indefinitely
	* Added function to enable or disable checks if user is logged in
	* Plugin requires now at least WordPress 2.6		
	* Updated language file
	* Updated FAQ 
	* Updated readme file
* Version 2.5
	* Minor code cleanup
* Version 2.4
	* Fixed bug when using WP 2.6
* Version 2.3
	* Fixed syntax error
* Version 2.2
	* Fixed bug when saving log and IP-Block is disabled
* Version 2.1
	* Fixed links in WP-Admin
* Version 2.0
	* Comment template can now be changed automaticly
	* Added option for checking and saving the IP of blocked Spambots
    * Updated language file
* Version 1.11
	* Added option to reset counter
    * Updated language file
* Version 1.10
	* Updated settings page
    * Updated language file
* Version 1.9
	* Added page for settings
    * Changed counter
    * Changed constant names for better compatibility
    * Updated language file
* Version 1.8
    * Using CSS for blank field
* Version 1.7
    * Using PLUGINDIR instead of hardcoding wp-plugin dir
* Version 1.6
    * Empty author or comment field is checked when in wp-comments-post.php
    * Code improvement
* Version 1.5beta
    * Using md5 instead of sha1 for hashing
    * Counter now increases when default comment field is submitted
    * Added german translation
* Version 1.4beta
    * Fixed bug when uploading a picture
    * Counter is now displayed in Dashboard
    * Minor code improvement
* Version 1.3beta
    * Existence of blank field is checked
    * Changed to blank field
    * Minor code improvement
    * Added counter
* Version 1.2beta
    * Plugin settings are deleted upon deactivation of plugin
    * Names of comment fields are changed every 24 hours
* Version 1.1beta
    * Change access to blank field
* Version 1.0beta
    * Initial version