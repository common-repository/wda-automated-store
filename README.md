=== WDA Automated Store ===
Contributors: devtarik
Donate link: https://webdevadvisor.com/wda-automated-store-donation/
Tags: Product Visibility, Stock Management, Automation
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically adjust product visibility in WooCommerce based on stock status with customizable settings.

== Description ==
**WDA Automated Store** is a time-saving solution for WooCommerce store administrators. This plugin automates the process of changing product visibility when stock status changes. For example, when a product goes out of stock, you can configure the plugin to automatically hide the product or set it to “Search Only” after a specified delay (e.g., 72 hours). You can also choose the exact time of day to trigger the visibility change.

Additionally, when a product is restocked, the plugin will automatically restore the product's visibility status to "Catalog & Search." This plugin reduces the need for manual intervention, streamlining store management.

Future updates will include more automated actions for WooCommerce.

== Features ==
* **Automated Visibility Changes**: Automatically updates product visibility based on stock status changes.
* **Custom Visibility Options**: Choose the visibility status for out-of-stock products (e.g., Hidden, Search Only).
* **Configurable Delay**: Set a delay (in hours) after a product goes out of stock before the visibility change is triggered.
* **Scheduled Actions**: Specify the exact time of day when the visibility change should occur.
* **Automatic Restore**: Automatically restores visibility to "Catalog & Search" when a product is back in stock.

== Installation ==
### Minimum Requirements:
* WordPress 6.0 or greater
* PHP version 7.4 or greater
* MySQL version 5.0 or greater

### Recommended Requirements:
* PHP version 8.1 or greater
* MySQL version 5.6 or greater
* WordPress Memory limit of 64 MB or greater (128 MB or higher is preferred)

### Installation Steps:
1. Install the plugin using the WordPress built-in Plugin installer, or extract the zip file and upload the contents to the `wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin settings under WooCommerce > Settings > Automated Product Actions to set visibility preferences and scheduling.

== Usage ==
The plugin provides an easy-to-use interface with clearly labeled settings. Admins can configure:
- The desired visibility status when a product goes out of stock.
- How long to wait before changing the product's visibility status.
- The time of day when this change should occur.

When a product is restocked, its visibility will automatically be restored to "Catalog & Search."

== Frequently Asked Questions ==
= Can I control when the visibility changes after a product goes out of stock? =
Yes, you can set the delay (in hours) and choose the time of day for the visibility change to take place.

= What happens when a product is back in stock? =
The plugin will automatically restore the product’s visibility to "Catalog & Search."

= Is manual action required to change the visibility status? =
No, the plugin handles the visibility changes automatically based on the stock status.

== Changelog ==
= 1.0.0 =
* Initial release of Automated Product Actions.
* Added automated visibility change based on stock status with configurable delays and scheduling.

== License ==
This plugin is licensed under the GNU General Public License v2.0 or later.
