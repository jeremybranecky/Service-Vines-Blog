=== Contact Form 7 Modules ===
Tags: contact form 7, contactform7, contact form, hidden fields, hidden, cf7, cforms ii, cforms, Contact Forms 7, Contact Forms, contacted, contacts
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: trunk
Contributors: katzwebdesign
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=zackkatz%40gmail%2ecom&item_name=Contact%20Form%207%20Modules&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8

Contact Form 7 - Add modules such as hidden fields to the Contact Form 7 plugin for more informative form contacts

== Description ==

> <strong>You may be interested in the <a href="http://bit.ly/dvF8BI" rel="nofollow">Gravity Forms plugin</a></strong>. Gravity Forms is a revolutionary contact form plugin that does everything Contact Form 7 does...and lots of brilliant stuff Contact Form 7 can not. <a href="http://bit.ly/dvF8BI" rel="nofollow">Check out Gravity Forms today!</a>

### Add Hidden Fields to Contact Form 7

The Contact Form 7 plugin has over 2.8 <em>million</em> downloads, yet the great plugin still lacks a simple feature: hidden fields. This plugin adds hidden fields to Contact Form 7 once and for all.

#### Inserting dynamic values

You can also choose to have the value of the hidden field dynamically populated in your form when you are contacted. To do so, choose the "Default value" to be:

* `post-title` - Inserts the title of the post/page
* `post_category` - The categories of the post or page
* `post_url` - The URL of the post or page
* `post_author` - The author of the post or page
* `custom_field-[Name]` - The value of a post or page's custom field. If you had a custom field "Foo", you would use the following as the hidden field value: `custom_field-Foo`

<strong>You can also use a filter:</strong> hook into the `wpcf7_hidden_field_value` filter to modify the value of the hidden field  using <a href="http://codex.wordpress.org/Function_Reference/add_filter" rel="nofollow"><code>add_filter()</code></a>. If you know the ID of the input, you can also use the `wpcf7_hidden_field_value_[#ID]` filter.

Now, when someone contacts you using your Contact Form 7 contact form, you can have lots more information about their visit - and you'll see it when you receive the email that tells you you've been contacted.

<h4>Visit the official <a href="http://www.seodenver.com/contact-form-7-hidden-fields/">Contact Form 7 Modules plugin page</a> for more support & additional information</h4>

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
1. Activate the plugin
1. Edit a form in Contact Form 7
1. Choose "Hidden field" from the Generate Tag dropdown
1. Follow the instructions on the page

== Screenshots ==

1. The new Hidden fields tag generator

== Frequently Asked Questions == 

= What is the plugin license? =

* This plugin is released under a GPL license.

== Changelog ==

= 1.0.1 =
* Added support for using post titles as hidden fields
* Added support for using custom field values as hidden fields
* Added `wpcf7_hidden_field_value` filter to hook into using <a href="http://codex.wordpress.org/Function_Reference/add_filter" rel="nofollow"><code>add_filter()</code></a>

= 1.0 =
* Initial plugin release.

== Upgrade Notice ==

= 1.0.1 =
* Added support for using post titles as hidden fields
* Added support for using custom field values as hidden fields
* Added `wpcf7_hidden_field_value` filter to hook into using <a href="http://codex.wordpress.org/Function_Reference/add_filter" rel="nofollow"><code>add_filter()</code></a>

= 1.0 = 
* Woot!