=== More Privacy Options ===
Contributors: dsader
Donate link: http://dsader.snowotherway.org
Tags: privacy, private blog, multisite, members only
Requires at least: 3.3.2
Tested up to: 3.3.2
Stable tag: Trunk

WP3.0 multisite "mu-plugin" to add more privacy options to the options-privacy and ms-blogs pages. Just drop in mu-plugins.

== Description ==
Adds three more levels of privacy to the Options--Privacy page.

1. Blog visible to any logged in community member - "Network Users Only".

2. Blog visible only to registered users of blog - "Blog Members Only".

3. Blog visible only to administrators - "Admins Only".

Mulitsite Network Admin can set an override on blog privacy at "Network Privacy Selector" on Network Admin-Options page

Multisite Network Admin can set privacy options at Network Admin-Sites-Edit under "Misc Site Options" as well.

Network Admin receives an email when blog privacy changes.

RSS feeds require authentication.

robots.txt updates accordingly.

Ping sites filters correctly.

Privacy status reflected in Dashboard/Admin header.

Uses WP3 functions network_home_url() and home_url() for SSL login redirects.

Login message has link to sign-up page of a "Network Users Only" blog or a link the blog admin email if user is logged in but not a member of a "Members Only" blog.

Supports filters to the login_url and wp_signup_location. 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `ds_wp3_private_blog.php` to the `/wp-content/mu-plugins/` directory
2. Set multisite "Network Privacy" option at Network Admin-Settings page
3. Set individual blog privacy options at Settings-Privacy page, or...
4. Set individual blog privacy options at Network Admin-Sites-Edit page

== Frequently Asked Questions ==

* Will this plugin also protect feeds? Yes.
* Will this plugin protect uploaded files and images? No.
* Will this plugin redirect to a custom signup page or login page? Yes I've added support for filters to the login_url and wp_signup_location. I've included a couple of sample functions of these hooks in the plugin's comment section in the plugin code.

== Screenshots ==

1. Settings Privacy: Site Visibility Settings
2. Network Admin Option: Network Privacy Selector
3. Sites Edit: Misc Site Options

== Changelog ==

= 3.2.1.5 =
* Tested up to: WP 3.3.2

= 3.2.1.1 =
* Tested up to: WP 3.2.1


= 3.0.1.3 =
* fixed bug causing 404 from login_url: wp_login_url(urlencode($_SERVER['REQUEST_URI'])) to wp_login_url()
modified authentication checks for feeds

= 3.0.1.2 = 
* Supports filters to the login_url and wp_signup_location. 

= 3.0.1.1 =
* Network Admin receives an email when blog privacy changes.
* Privacy status reflected in Dashboard/Admin header.
* Uses WP3 functions network_home_url() and home_url() for SSL login redirects.
* Login message has link to signup page if visitor is not logged or a link the blog admin email if user is logged in but not a member of a members only blog.
* noindex,nofollow correctly added to meta in wp_head and login_head
 
= 3.0.1 = 
* deprecated $user_level check replaced with is_user_logged_in()

= 3.0 =
* WP3.0 Multisite enabled

= 2.9.2 =
* WPMU version no longer supported.

== Upgrade Notice ==
= 3.2.1.5 =
* Tested up to: WP 3.3.2

= 3.0.1.3 =
* fixed bug causing 404 from login_url

= 3.0.1.2 =
* Supports filters to the login_url and wp_signup_location. 

= 3.0.1 = 
* deprecated $user_level check replaced with is_user_logged_in()

= 3.0 =
* WP3.0 Multisite enabled

= 2.9.2 =
* WPMU version no longer supported.
