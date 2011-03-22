=== Plugin Name ===
Contributors: Qoate
Donate link: http://qoate.com/donate/
Tags: newsletter,comments,registration,sign-up,newsletter signup,mailinglist,mailchimp,aweber,phplist,sign-up form,widget
Requires at least: 2.0
Tested up to: 3.0.1
Stable tag: 2.0.1

Adds a checkbox to your comment form to subscribe people to your mailing list. Also offers a sign-up form widget.

== Description ==

= Qoate Newsletter Sign-up =

Want to offer your visitors a simple checkbox when writing a comment or registering at your blog that signs them up to your mailinglist of choice? This plugin
takes care of it. This plugin also contains a simple sign-up form widget that can be somewhat customized. 

You can choose whether you want people to subscribe with their names also. On top of that you can sent custom information like the page where they chose to sign-up.

The least you have to do to get it working is filling in your newsletter provider settings (form action and e-mail name attribute at least).
I've made this as easy as possible, most newsletter services have some preselected values. 
Check out the [Qoate Newsletter Sign Up](http://qoate.com/wordpress-plugins/newsletter-sign-up/) for more information.

The plugin currently supports MailChimp, FeedBlitz, PHPlist, YMLP, iContact and Aweber but is not limited to those since it can easily be set-up for other mailinglists.

More info:

* [Qoate Newsletter Sign Up](http://qoate.com/wordpress-plugins/newsletter-sign-up/)
* Read more great [WordPress tips](http://qoate.com/) to get the most out of your website
* Check out more [WordPress plugins](http://qoate.com/wordpress/) by the same author

Got a great idea on how to improve this plugin, to get even more newsletter subscribers? Please, [let me know](http://qoate.com/contact/)!

== Installation ==

Follow the instruction on the [Qoate Newsletter Sign Up](http://qoate.com/wordpress-plugins/newsletter-sign-up/) page.

== Frequently Asked Questions ==

= Why does the checkbox not show up? =

You're theme probably does not support the comment hook this plugin uses. You can add it manually by adding . `<?php do_action('comment_form',$post->ID); ?>`

= Where can I get the form action of my sign-up form? =

Look at the source code of your sign-up form and check for `<form action="http://www.yourmailinglist.com/signup?a=asd128"`....
The action url is what you need to configure in the admin panel.

= Where can I get the email identifier of my sign-up form? =

If you're using MailChimp or YMLP all you have to do is select your newsletter service from the dropdown. When using some other service, select other and look
at the source code of your sign-up form. Look for the input field that holds the e-mailadress. The name attribute is what you need here. Want your newsletter service to
appear in the drop down? Let me know and i'll add!

= Your checkbox stopped working with Aweber = 

I did change something in version 1.7 that caused that, sorry. All you have to do is hit the submit button with the right settings once again.

For more questions and answers go and have a look at [Qoate.com](http://qoate.com/wordpress-plugins/newsletter-signup/)

== Changelog ==
= 2.0.1 =
Fixed a thing where the cookie expiry date gave an error. 

= 2.0 =
Added a sign-up form widget, which you can customize a little for now. Will expand in the future!

= 1.8.2 =
Added an option to hide the checkbox for users that signed up trough the Qoate checkbox.

= 1.8.1 = 
PHPlist should now work.

= 1.8 = 
Advanced users with PHP knowledge can now send extra variables.

= 1.7 = 
Added support for PHPList. For the people using Aweber, you might have to hit the submit button with the right settings again. Sorry.

= 1.6 =
You can now add a checkbox to your registration form too.

= 1.5.2 =
Fixed another little Aweber bug.

= 1.5.1 =
Fixed a little bug with Aweber.

= 1.5 = 
* Added Aweber support.
* Pre select checkbox option
* Subscribe with name option.
* Conditional plugin files loading for improved blog performance.

= 1.4 =
Major performance increase

= 1.3 =
You can now select MailChimp, YMLP to help you with providing the Email identifier. 

= 1.2 = 
The checkbox now shows up automatically for most themes!

= 1.1 =
Updated so you no longer need to add the action hook manually.

= 1.0 =
Stable release.