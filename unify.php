<?php

/**
 * Plugin Name: Unify
 * Plugin URI: https://www.codeclouds.com/unify/
 * Description: A CRM payment plugin which enables connectivity with LimeLight/Konnektive CRM and many more..
 * Author: CodeClouds <sales@codeclouds.com>
 * Author URI: https://www.CodeClouds.com/
 * Version: 3.4.3
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * WC requires at least: 3.0
 * WC tested up to: 7.5
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Loaded Hoocks & Actions.
 */
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if (is_plugin_active('woocommerce/woocommerce.php')) {
    
    // Plugin 'woocommerce' is Active
    require_once __DIR__ . '/Services/Hooks.php';
    require_once __DIR__ . '/Lib/_SelfLoader-1.0/autoload.php';
    //require_once __DIR__ . '/Lib/autoload.php';
} else {
    add_action('admin_notices', function () {
        echo '<div class="error"><p><strong>' .
        sprintf(esc_html__('Unify Plugin requires WooCommerce to be installed and active. You can download %s here.'), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>') .
            '</strong></p></div>';
    });
}

/**
 * Adding first plugin activation date, upon activating the plugin
 */
function pluginprefix_activate()
{
    $unify_plugin_activation_date = \get_option('unify_plugin_activation_date');
    if (empty($unify_plugin_activation_date)) {
        \add_option('unify_plugin_activation_date', time());
    }
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'pluginprefix_activate');

if (function_exists( 'wp_get_environment_type' ) && (\wp_get_environment_type() === 'development' || \wp_get_environment_type() === 'sandbox')) {
    define('UNIFY_HUB_URL', 'https://sandbox-dot-unify-hub.appspot.com/api/');
    define('UNIFY_PLATFORM_ENDPOINT', 'https://platform-sandbox.unify.to/');
} else {
    define('UNIFY_HUB_URL', 'https://web-service.unify.to/api/');
    define('UNIFY_PLATFORM_ENDPOINT', 'https://platform.unify.to/');
}
define('UNIFY_PLATFORM_LOGIN', 'https://accounts.unify.to/login');
define('UNIFY_WP_HOME_URL', home_url());
define('UNIFY_JS_VERSION', '3.4.3');
