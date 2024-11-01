<?php

/**
 * All Hooks
 * @package CodeClouds\Unify
 */

/**
 * Loads assets
 */
add_action('admin_enqueue_scripts', ['CodeClouds\Unify\Actions\Assets', 'load_admin_assets_unify_connections']);

/**
 * Custom Post Type
 */
add_action('init', ['CodeClouds\Unify\Actions\Block', 'unify_connections']);

add_action('admin_menu', ['CodeClouds\Unify\Actions\Menu', 'add_settings_to_menu']);
add_action('admin_menu', ['CodeClouds\Unify\Actions\Menu', 'alter_menu_label']);

add_action('add_meta_boxes', ['CodeClouds\Unify\Actions\Block', 'add_unify_connections_metaboxes']);

add_action('save_post', ['CodeClouds\Unify\Actions\Block', 'save_unify_connections_metaboxes'], 1, 2);

/**
 * Add connection ID field to product
 */
add_action('woocommerce_product_options_related', ['CodeClouds\Unify\Actions\Product', 'product_options_grouping']);

add_action('woocommerce_process_product_meta', ['CodeClouds\Unify\Actions\Product', 'save_connection_id']);

/**
 * Product column
 */
add_filter("manage_edit-product_columns", ['CodeClouds\Unify\Actions\Product', 'woo_product_extra_columns']);

add_action("manage_posts_custom_column", ['CodeClouds\Unify\Actions\Product', 'woo_product_extra_columns_content']);

/**
 * Payment Gateway
 */
add_action('plugins_loaded', ['CodeClouds\Unify\Actions\Gateway', 'init']);

add_filter('woocommerce_payment_gateways', ['CodeClouds\Unify\Actions\Gateway', 'add_unify_gateway_class']);
add_action( 'before_woocommerce_init', array('CodeClouds\Unify\Actions\Gateway', 'woocommerce_hpos_compatible' ) );

add_action('woocommerce_checkout_fields', ['CodeClouds\Unify\Actions\Checkout', 'checkout_validation']);

add_action('woocommerce_checkout_process', ['CodeClouds\Unify\Actions\Checkout', 'process_unify_payment']);

/**
 * Order View
 */
add_action('woocommerce_admin_order_data_after_order_details', ['CodeClouds\Unify\Actions\Order', 'add_connection_details_to_view']);

/**
 * Tools
 */
add_action('admin_post_codeclouds_unify_tool_import', ['CodeClouds\Unify\Actions\Product', 'import_connections']);

add_action('admin_post_codeclouds_unify_tool_download', ['CodeClouds\Unify\Actions\Product', 'download_csv']);

add_action('admin_post_codeclouds_unify_tool_mapping', ['CodeClouds\Unify\Actions\Product', 'product_mapping']);

/**
 * About
 */
add_action('in_admin_footer', ['CodeClouds\Unify\Actions\About', 'copyright_msg']);

/**
 * Footer
 */
// Checkout validation
add_action('wp_footer', ['CodeClouds\Unify\Actions\Checkout', 'checkout_js_validation']);


add_action('admin_post_unify_connections_post', ['CodeClouds\Unify\Actions\Connection', 'save_connection']);
add_action('admin_post_unify_connections_delete', ['CodeClouds\Unify\Actions\Connection', 'delete_connection']);
add_action('admin_post_unify_product_post', ['CodeClouds\Unify\Actions\Tools', 'save_product']);
add_action('admin_post_unify_product_shipping', ['CodeClouds\Unify\Actions\Tools', 'save_shipping']);
add_action('admin_post_request_unify_pro', ['CodeClouds\Unify\Actions\Dashboard', 'request_unify_pro'] );
add_action('admin_post_unify_settings_form_post', ['CodeClouds\Unify\Actions\Settings', 'save_settings']);
add_action('admin_post_unify_paypal_settings_form_post', ['CodeClouds\Unify\Actions\Settings', 'save_paypal_settings']);

// Add hook for admin <head></head>
add_action( 'wp_ajax_bulk_delete_conn', ['CodeClouds\Unify\Actions\Connection', 'bulk_delete_conn'] );
add_action( 'wp_ajax_bulk_restore_conn', ['CodeClouds\Unify\Actions\Connection', 'bulk_restore_conn'] );
add_action( 'wp_ajax_activate_conn', ['CodeClouds\Unify\Actions\Connection', 'activate_conn'] );

// Add specific CSS class by filter. 
add_filter( 'admin_body_class', function( $classes ) {
	
	if (!empty($_GET['page']) && !empty(strrchr($_GET['page'], 'unify'))){
		return $classes . ' unify_body ';
	} else {
        return $classes;
    }
} );

// Registering custom post status
add_action( 'init', ['CodeClouds\Unify\Actions\Connection', 'custom_post_status_active'] );

// collecting affiliate params on template load.
add_action( 'template_redirect', ['CodeClouds\Unify\Actions\Checkout', 'collect_affiliate_param']);


// Adding Custom fields (CRM Variation ID) for woocommerce product variation
add_action( 'woocommerce_product_after_variable_attributes', ['CodeClouds\Unify\Actions\Product', 'add_custom_field_to_variations'], 10, 3);
add_action( 'woocommerce_save_product_variation', ['CodeClouds\Unify\Actions\Product', 'save_custom_field_variations'], 10, 2 );

add_action( 'wp_ajax_validate_crm_connection', ['CodeClouds\Unify\Actions\Connection', 'validate_crm_connection'] );
add_action( 'wp_ajax_unify_plugin_lead_generate', ['CodeClouds\Unify\Actions\Dashboard', 'unify_plugin_lead_generate'] );

/* Unset PayPal Payment gateway other than limelight connection*/
add_action('woocommerce_available_payment_gateways', ['CodeClouds\Unify\Actions\OrderConfirmation', 'unify_gateway_disable_paypal'], 10, 1);
  
add_action( 'wp_loaded', ['CodeClouds\Unify\Actions\OrderConfirmation', 'unify_front_end_function']);

add_action( 'woocommerce_before_checkout_form', ['CodeClouds\Unify\Actions\OrderConfirmation','wnd_checkout_code'], 10 );



add_action( 'wp_ajax_validate_pro_license', ['CodeClouds\Unify\Actions\PlatformApi', 'validate_pro_license'] );

add_action('woocommerce_before_checkout_form', ['CodeClouds\Unify\Actions\PlatformApi', 'toUnify']);

$pro_license = \get_option('codeclouds_unify_pro_license');
if(!empty($pro_license)) add_action('admin_menu',['CodeClouds\Unify\Actions\PlatformApi', 'remove_free_menu']);  

add_filter( 'is_active_sidebar', ['CodeClouds\Unify\Actions\PlatformApi', 'unify_remove_sidebar'], 10, 2 );

add_shortcode('unify_checkout', ['CodeClouds\Unify\Actions\PlatformApi', 'unify_checkout_hook']);

add_action('init', ['CodeClouds\Unify\Actions\PlatformApi', 'unify_woocommerce_clear_cart_url']);

add_filter('woocommerce_rest_prepare_product_object', ['CodeClouds\Unify\Actions\PlatformApi', 'custom_change_product_response'], 20, 3);

add_action( 'woocommerce_new_order', ['CodeClouds\Unify\Actions\PlatformApi', 'modify_data_after_order'], 10, 1 );

add_action( 'wp_loaded',  ['CodeClouds\Unify\Actions\PlatformApi', 'woocommerce_add_multiple_products_to_cart'], 15 );

add_action('wp_footer', ['CodeClouds\Unify\Actions\PlatformApi', 'checkout_Pro_js']);

add_action( 'init', function(){

	if(isset($_GET['delete'])) :
        delete_option('codeclouds_unify_pro_license');
        delete_option('upgrde_request_sent');
        delete_option('config_transferred_from_button');
        delete_option('woocommerce_codeclouds_unify_free_trial_registation');
    endif;

    if(isset($_GET['delete-date'])) :
        delete_option('woocommerce_codeclouds_unify_free_trial_registation');
    endif;
});

add_action( 'woocommerce_after_add_to_cart_button', ['CodeClouds\Unify\Actions\Cart', 'add_custom_buy_now_button'], 10, 0 );

add_action( 'woocommerce_add_to_cart_redirect', ['CodeClouds\Unify\Actions\Cart', 'redirect_to_checkout'] );

add_action('wp_ajax_clearcart', ['CodeClouds\Unify\Actions\Cart', 'clearcart']);
add_action('wp_ajax_nopriv_clearcart', ['CodeClouds\Unify\Actions\Cart', 'clearcart']);

add_action( 'template_redirect', ['CodeClouds\Unify\Actions\PlatformApi', 'unify_collect_query_params']);

add_action( 'wp_ajax_configurationDataCollection', ['CodeClouds\Unify\Actions\PlatformApi', 'configurationDataCollection'] );


add_action( 'wp_ajax_unify_pro_request', ['CodeClouds\Unify\Actions\Dashboard', 'unify_pro_request'] );

add_action( 'wp_ajax_requestCancellation', ['CodeClouds\Unify\Actions\PlatformApi', 'requestCancellation'] );

add_action( 'wp_ajax_downgrading', ['CodeClouds\Unify\Actions\PlatformApi', 'downgrading'] );

add_action( 'admin_head', ['CodeClouds\Unify\Actions\Menu', 'unify_admin_menu_new_item']);

add_action( 'wp_loaded', ['CodeClouds\Unify\Actions\Menu', 'unify_pro_admin_menu']);

//increase and set wp_remote timeout.
add_filter( 'http_request_timeout', 'wp9838c_timeout_extend' );

function wp9838c_timeout_extend( $time )
{
    // Default timeout is 5
    return 30;
}





























