<?php

namespace CodeClouds\Unify\Actions;

/**
 * Checkout actions.
 * @package CodeClouds\Unify
 */
class Cart
{

    /**
     * Custom Buy Now Button
     */
    public static function add_custom_buy_now_button()
    {
        $pro_license = \get_option('codeclouds_unify_pro_license');
        if (empty($pro_license)) {
            return;
        }

        global $product;

        include_once __DIR__ . '/../Templates/buy-now-button.php';
    }

    /**
     * Redirect to checkout using custom Buy Now button
     */
    public static function redirect_to_checkout($redirect_url)
    {
        if (isset($_REQUEST['is_buy_now']) && sanitize_text_field(wp_unslash($_REQUEST['is_buy_now']))) {
            global $woocommerce;

            $redirect_url = wc_get_checkout_url();
        }
        return $redirect_url;
    }

    /**
     * Clear rest items from cart and add the buy now item and qty
     */
    public static function clearcart()
    {
        if (!empty($_POST['product_id']) && !empty($_POST['product_qty'])) {
            $product_id = sanitize_text_field(wp_unslash($_POST['product_id']));
            $product_qty = sanitize_text_field(wp_unslash($_POST['product_qty']));

            global $woocommerce;
            $woocommerce->cart->empty_cart();
            $woocommerce->cart->add_to_cart($product_id, $product_qty);

            die();
        }
    }

}
