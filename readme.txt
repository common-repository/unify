== Unify ==
Contributors: codeclouds
Tags: woocommerce, limelight, konnektive, Response, Sublytics, shopify, payment, limelightcrm, konnektivecrm, responsecrm, sublyticscrm, shopping, shop, ecommence, wordpress, crm, connection.
Requires at least: 4.0
Tested up to: 6.4
Requires PHP: 5.6
Stable tag: 3.4.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html\

A CRM payment plugin which enables connectivity with LimeLight/Konnektive CRM and many more.

== Description ==

Unify is a Wordpress/WooCommerce plugin which integrates advanced features in your checkout to enhance the experience for your customers and increase your sales potential. With Unify you can process transactions through a supported CRM, process subscription-type orders, set up customer portals where you can access the light-weight support ticket system and support chat. In addition to the free features, the Unify Pro plugin allows you to set up 1-click upsells, sync between your CRM and WooCommerce, and much more. A full list of the features can be found below. <a href="https://www.codeclouds.com/unify/" target="_blank">Learn more about Unify Wordpress ></a>

<h4>Supported CRMS</h4>

* <a href="https://www.codeclouds.com/sticky-io/" target="_blank">Sticky.io</a> (Formerly <a href="https://www.codeclouds.com/crm/limelight-crm/" target="_blank">LimeLight CRM</a>)
* <a href="https://www.codeclouds.com/crm/konnektive-crm/" target="_blank">Konnektive CRM</a>
* <a href="https://www.codeclouds.com/crm/response-crm/" target="_blank">Response CRM</a>
* <a href="https://www.codeclouds.com/crm/sublytics/" target="_blank">Sublytics CRM</a>


<h4>External Service</h4>

* We are using <a href="https://ipinfo.io/" target="_blank">ipinfo</a> to get the location country for registration. Click here to check the <a href="https://ipinfo.io/terms-of-service" target="_blank">terms and contitions</a>.


<h4>BUILT-IN FEATURES</h4>

* Connect to a supported CRM
* Process regular and subscription-based orders through your CRM
* Map products between your storefront and CRM
* Batch import products
* Support for Sticky.io Billing Model
* Reverse synchronization between storefront and CRM


<h4>UNIFY PRO FEATURES</h4>

* All features from the free tier
* Set up true 1-click upsells
* Coupon/promo code manager
* Recover abandoned carts via third-party services
* And various other add-ons to enhance your checkout!
* Customer portal integration for users to:
      * Manage their subscriptions and orders
      * Request cancellation, return or refund on a subscription or order
      * Submit a support ticket
      * Message support through the chat system
      * View order details and history
      * Manage account and address
      * Switch to a different subscription product
      * “Skip a cycle” also available
      * Store and manage user preferences
      * Various portal templates available; Physical, Digital, Membership Boxes


If you are interested in Unify Pro, <a href="https://www.codeclouds.com/contact-us/" target="_blank">get in touch</a> with CodeClouds today!


== Installation ==

This section describes how to install the plugin and get it working.

<h4>Installation</h4>
<ul>
<li><strong>Upload files:</strong> Upload the entire unify folder to the <b>/wp-content/plugins/</b> directory.</li>
<li><strong>Activation:</strong> Activate the plugin through the <b>Plugins</b> menu in WordPress.</li>
</ul>

<h4>Configuration</h4>
<ul>
<li><strong>Add Connection:</strong> Open your Admin Panel and go to <b>Unify > Add New Connection</b>. Please have a look at the below screenshot.</li>
<li>
<strong>Product Mapping:</strong> Now, you need to map your product(s) with connection’s product(s). You can do mapping in 3 ways.
<ul>
<li><strong>One By One:</strong> Go to <b>Products > Add/Edit Product > Linked Products</b> and add your <b>Connection's Product ID</b>.</li>
<li><strong>Inline Editor:</strong> Go to <b>Unify > Tools > Product Mapping</b> and click on a row.</li>
<li><strong>Bulk Import:</strong> Go to <b>Unify > Tools > Import/Export</b> and import CSV file. Before upload you can export products as a CSV file & update that file with your connection’s product ID.</li>
</ul>
</li>
<li><strong>Configuration:</strong> Now you are in the last step. Go to <b>Unify > Settings</b> and scroll to the bottom. You can see <b>Unify Payment</b> Method. Add a title, select your connection, credit card types, etc.</li>
</ul>

== Screenshots == 

1. Add New Connection
2. Product Mapping Manually
3. Product Mapping by Inline Editor
4. Product Mapping by Bulk Import
5. Configuration

== Changelog ==

= 3.4.3 =
* Enhacement - Unify Checkout promo code enhacement.

= 3.4.2 =
* Enhacement - Optimized warning error.

= 3.4.1 =
* Enhacement - Woocommerce variant product image with Unify Hosted Checkout.
* Fix - Resolved payment form conflict with NMI payment form.

= 3.4.0 =
* Feature - Now support Multi Currency for Pro plugin.

= 3.3.5 =
* Fix - Product mapping saving issue.

= 3.3.4 =
* Enhacement - Updated License management links.

= 3.3.3 =
* Enhacement - Account License Key.

= 3.3.2 =
* Add - Added validation messages for paypal payments.
* Add - Added browser agent in order note for sticky.io

= 3.3.1 =
* Tweak - Made compatible for wordpress multisite.

= 3.3.0 =
* Compatible - Made it comfortable for PHP above 8.
* Fix - Updated from CURL to HTTP API.
* Add - Added proper Sanitized, Escaped, and Validated.
* Enhacement - Updated file calling.
* Fix - Converted from obfuscated codes to minified version.

= 3.2.5 =
* Fix - Product mapping tool pricing round off issue.

= 3.2.4 =
* Add - Added sorting feature in product mapping tool.

= 3.2.3 =
* Add - Introduced an area to check the Plugin status and Server status.

= 3.2.2 =
* Fix - Activation issue for WPEngine.

= 3.2.1 =
* New - Support for WordPress 5.8.
* New - Support for WooCommerce 5.9.

= 3.2.0 =
* Feature – Unify plugin now supports Sublytics CRM.

= 3.1.4 =
* Tweak - added product variation image data for cart.

= 3.1.3 =
* Fix - Order creation with variant product for pro version.

= 3.1.2 =
* Enhancement - Compatibility with ReferralCandy(Unify Pro).
* Fix - Trial License Email Address Checking.

= 3.1.1 =
* Enhancement - API details encryption for more security.

= 3.1.0 =
* Enhancement - Enhanced UI for better user experience.

= 3.0.1 =
* Enhancement - Enhanced affiliate param feature.

= 3.0.0 =
* Feature - Unify Pro released.

= 2.6.0 =
* Feature - Introduced WooCommerce shipping method to connect with Sticky.io (Formally Limelight).
* Feature - Introduced trial license.
* Enhancement - Enhanced UI for better user experience.
* Enhancement - Enhanced the PayPal payment method section.

= 2.5.2 =
* Enhancement - Enhanced validation for CRM settings.

= 2.5.1 =
* Enhancement - Enhanced the log management for PayPal Payments with Sticky.io (Formally Limelight).

= 2.5.0 =
* Feature - PayPal Payments support for Sticky.io (Formally Limelight).

= 2.4.1 =
* New - Support for WordPress 5.5.
* New - Support for WooCommerce 4.4.

= 2.4.0 =
* Enhancement - Unify plugin now supports Affiliate Parameters.

= 2.3.2 =
* Enhancement - Enhanced the feature to support the latest Response CRM.

= 2.3.1 =
* Enhancement - Enhanced the shipping ID feature for each product with Sticky.io (Formally Limelight).

= 2.3.0 =
* Feature - Now order note is available in Unify plugin with Sticky.io (Formally Limelight) Legacy CRM for same product multiple variation.
* Enhancement - Made shipping profile optional for Konnektive CRM.

= 2.2.0 =
* Feature - Unify plugin now supports Konnektive CRM product variation.
* Enhancement - Payment page escaping special character.

= 2.1.1 =
* Fix - Connection typo, which was causing issue for Limelight legecy version.

= 2.1.0 =
* Feature - Unify plugin now supports Response CRM.
* Dev - Updated Author Name and Email ID in library file.
* Fix - validation.required error message on validation failure.
* Enhancement - Optimized the Limelight CRM library.

= 2.0.4 =
* Enhancement - Showing error messages from CRM.
* Localization - Changed few labels.

= 2.0.3 =
* Fix - The issue for console error in checkout page.
* Tweak - Changed the validate jquery library path.

= 2.0.2 =
* Fix - The issue with WooCommerce Logger.

= 2.0.1 =
* Template - Added Email templates.

= 2.0 =
* Template - Plugin New Admin UI.
* Feature - Added debugging option to log API request and response.
* Fix - Upsell ID issue for empty Shipment Price Settings.

= 1.2.4 =
* Feature - Custom note for Konnektive CRM

= 1.2.3 =
* Feature - Added support for custom shipping price changed for Konnktive CRM
* Fix - Undefined Index title in Product Mapping
* Fix - The issue with special character in API password
* Fix - Added ipAddress key in order creation for Konnektive CRM
* Fix - Updated CVV validation so that number starting with zero do not throw any integer error in Konnektive CRM
* Fix - 'Invalid UPSELL product id of 0 found' for Limelight Billing Model

= 1.2.2 =
* Fix - Decryption issue.

= 1.2.1 =
* Fix - Setting of default card type for Test card.

= 1.2.0 =
* Fix - American Express CVV checking error.
* Fix - Credit Card 2 digit month checking.
* Fix - Undefined index type in MetaBox
* Fix - Undefined index required in Input
* Feature - Added Product Variant Support for Limelight CRM
* Feature - Added Limelight CRM Offer and Billing Model Support

= 1.1.1 =
* Fix - Notice for undefined index.
* Performance - Prevented calling of un-necessary Class.

= 1.1.0 =
* Feature - A new feature has been added for various shipping method for a product.
* Fix - Notice of calling id incorrectly of Order properties.
* Enhancement - Enhanced the validation for card expiry date in checkout page.

= 1.0.2 =
* Made it comfortable for PHP 7.2

= 1.0.1 =
* Made it comfortable for PHP 5.6

= 1.0.0 =
* First public release


== Upgrade Notice ==

Coming soon
