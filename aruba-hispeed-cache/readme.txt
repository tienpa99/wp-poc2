=== Aruba HiSpeed Cache ===

Contributors: arubait, arubadev, arubasupport, agelmini, camaran
Tags: HiSpeed Cache, Aruba, cache, caching, performance, pagespeed, optimize, WP cache, speed, purge
Requires at least: 5.4
Tested up to: 6.2
Requires PHP: 5.6
Stable tag: 1.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Aruba HiSpeed Cache interfaces directly with an Aruba hosting platform's HiSpeed Cache service and automates its management.

== Description ==

**Aruba HiSpeed Cache** is a plugin that interfaces directly with the **HiSpeed Cache** service for an [Aruba](https://www.aruba.it/en/) [hosting platform](https://hosting.aruba.it/en/) and automates its management in the WordPress dashboard, without having to access the website's control panel.

**The plugin can only be used if your WordPress website is hosted on an [Aruba](https://www.aruba.it/en/) [hosting platform](https://hosting.aruba.it/en/).**

The HiSpeed Cache service significantly reduces the TTFB (first Byte transfer time) and webpage loading times.

When the service is active, the plugin lets you clear the cache automatically (and/or manually) every time a page or post is edited, without having to access the control panel for the website by clicking on the link provided.

HiSpeed Cache keeps dynamic content in the servers' memory after the first time it loads, making it available for subsequent requests much faster, significantly speeding up website browsing. The plugin simply clears the cache every time a custom page, article or content item is edited.

For more details and to find out whether the HiSpeed Cache service is active on your website [please refer to our guide](https://guide.hosting.aruba.it/hosting/wordpress-e-altri-cms/wordpress-plugin-aruba-hispeed-cache.aspx?viewmode=0#eng).

== Installation ==

= From your WordPress dashboard =
1. Visit 'Plugins -> Add New'
2. Search for 'Aruba HiSpeed Cache'
3. Activate Aruba HiSpeed Cache from your Plugins page.
4. Click 'Settings -> Aruba HiSpeed Cache'

= From WordPress.org =
1. Download Aruba HiSpeed Cache.
2. Create a directory named 'aruba-hispeed-cache' in your '/wp-content/plugins/' directory, using your preferred method (ftp, sftp, scp, etc.).
3. Activate Aruba HiSpeed Cache  from your Plugins page.
4. Click Options from your Plugins page

== Frequently Asked Questions ==

= What is HiSpeed Cache? =

HiSpeed Cache is a dynamic caching system that significantly improves webpage loading speeds. The active cache reduces the time to first byte (TTFB). The service also lets you clear the cache automatically or manually, whenever a page or any content is edited.

= What is the purpose of the plugin? =

When the service is active, using the plugin means you can clear the website's cache at any time directly from the WordPress dashboard, without having to access the hosting control panel. You can set the cache to clear automatically, or you can use the manual option.

= My website is not hosted on an Aruba hosting platform. Can I still use the plugin? =

You can install the plugin, but it was designed to interface with the caching system for an Aruba hosting platform. Purchase an Aruba hosting service then migrate your website to use the plugin.

= Should I exclude pages from the caching system? =

No, you don't need to because the caching system already excludes:

addresses with one of the following strings:
wp-login, preview=true, cart, my-account, checkout, addons, add-to-cart, wp-cron.php, xmlrpc.php, contact, task=registration, registerview=registration|administrator|remind|login, admin/content/backup_migrate/export, status.php, update.php, install.php, user, info, flag, ajax, aha;

requests with cookies containing one of the following strings:
wordpress_no_cache, comment_author, wordpress_logged_in_, yith_wcwl_products, wp-postpass_, it_exchange_session_, wp_woocommerce_session, woocommerce_cart_hash, edd_items_in_cart=1, jSGCacheBypass=1, wpSGCacheBypass=1, woocommerce_items_in_cart=1.

== Screenshots ==

1. General Settings enabled
2. General Settings disabled

== Changelog ==

= 1.2.4 =
* Add support for WordPress 6.2

= 1.2.3 =
* Add x-aruba-cache header in site health

= 1.2.2 =
* Woocommerce save option bug fix.
* Page update content bug fix.
* Add no limit transient for json call.
* Various minor bug fixes and other improvements.

= 1.2.1 =
* Edit the Bootstrap method to fix the large menu editing problem.
* Action and filter loading system fixed.
* Various minor bug fixes and other improvements.

= 1.2.0 =
* Discriminant added to REST API requests to include FSE support and remove recurring clear cache requests.
* Clear function added to the 'post_updated hook' action to include support for the editor with meta-box added by plugins and themes.
* Log function added to files activated with WP_DEBUG set to true, created in plugin folder. N.B. If disabled, file will be deleted.
* FILTER_SANITIZE_STRING replaced by FILTER_SANITIZE_URL to ensure compatibility with PHP 8.1.
* ArubaHiSpeedCache_update_plugins_db() mode added to allow management of future database updates.
* Action and filter loading system within plugin fixed.
* Various bug fixes and other improvements.

= 1.1.2 =
* Problem regarding check resolved. In the event of a false positive during activation, go to the plugin page (../wp-admin/options-general.php?page=aruba-hispeed-cache) to repeat check.
* Various bug corrections and other improvements.

= 1.1.1 =
* Problem resolution.  Display "service can be activated" message when cache is purged.;
* Update Plugin URI value;
* Problem resolution. WP_CLI Activation and Deactivation;
* Additional option to view the check request information by visiting the page dominio.tld/wp-admin/options-general.php?page=aruba-hispeed-cache&debug=1

= 1.1.0 =
* Compatibility with PHP 5.6 and above;
* Compatibility with WordPress 5.4 and above;
* Monitoring of Aruba HiSpeed Cache service enabled/disabled;
* Message in the admin panel following the check: on the status of the service and if the website is hosted on an Aruba server or a server which is not compatible with the HiSpeed Cache service.

= 1.0.0 =
* First stable version released.

== Upgrade Notice ==

= 1.2.4 =
* Add support for WordPress 6.2

= 1.2.3 =
* Add x-aruba-cache header in site health
