<?php

namespace CodeClouds\Unify\Actions;

use CodeClouds\Unify\Model\Tools_model;
use \CodeClouds\Unify\Model\ConfigEncryption;
use \CodeClouds\Unify\Service\Helper;
use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Service\Request;

/**
 * Plugin's Tools.
 * @package CodeClouds\Unify
 */
class Tools
{
    /**
     * Setup tools page
     */
    public static function tools_page()
    {
        global $wpdb;
        $request = [];

        $sections = [
            'product-mapping',
            'shipping-mapping',
            'import-export',

        ];
        //******* Get setting for connection Starts ********
        $crm_meta = '';
        $crm_model_meta = '';
        $setting_option = \get_option('woocommerce_codeclouds_unify_settings');

        if (!empty($setting_option) && !empty($setting_option['connection'])) {

            $meta_data = \get_post_meta($setting_option['connection'], 'unify_connection_crm');

            $meta_data_salt = \get_post_meta($setting_option['connection'], 'unify_connection_crm_salt');

            if (!empty($meta_data)) {
                $crm_meta = isset($meta_data_salt[0]) ? ConfigEncryption::metaDecryptSingle($meta_data[0], $meta_data_salt[0]) : $meta_data[0];

                if ($crm_meta == 'limelight') {
                    $meta_model_data = get_post_meta($setting_option['connection'], 'unify_connection_offer_model');
                    $crm_model_meta = (!empty($meta_model_data)) ? $meta_model_data[0] : '';
                    $shipping_price_settings_option = (!empty($setting_option['shipment_price_settings'])) ? $setting_option['shipment_price_settings'] : '';
                }
                if ($crm_meta == 'sublytics') {
                    $meta_model_data = get_post_meta($setting_option['connection'], 'unify_connection_offer_model');
                    $crm_model_meta = (!empty($meta_model_data)) ? $meta_model_data[0] : '';
                    $shipping_price_settings_option = (!empty($setting_option['shipment_price_settings'])) ? $setting_option['shipment_price_settings'] : '';
                }

                if ($crm_meta == 'response') {
                    $meta_model_data = \get_post_meta($setting_option['connection'], 'unify_response_crm_type_enable');
                    $crm_model_meta = (!empty($meta_model_data)) ? $meta_model_data[0] : '';
                }

            }
        }
        //******* Get setting for connection Ends ********

        $request['paged'] = (empty($_GET['paged'])) ? 1 : sanitize_text_field(wp_unslash($_GET['paged']));
        $request['posts_per_page'] = (empty($_GET['posts_per_page'])) ? 10 : sanitize_text_field(wp_unslash($_GET['posts_per_page']));

        $request['orderby'] = (empty($_GET['orderby'])) ? 'post_title' : sanitize_text_field(wp_unslash($_GET['orderby']));
        $request['order'] = (empty($_GET['order'])) ? 'asc' : sanitize_text_field(wp_unslash($_GET['order']));

        $tools_model_object = new Tools_model();
        $data = $tools_model_object->get_products_with_meta($request);

        if (!empty($data['list'])) {
            foreach ($data['list'] as $k => $prod_list) {
                $product = \wc_get_product($prod_list['ID']);
                $data['list'][$k]['price'] = $product->get_price();
            }
        }

        $prev_dis = (($request['paged'] == 1)) ? true : false;
        $next_dis = (!empty($request['paged']) && $request['paged'] == $data['total']) ? true : false;
        include_once __DIR__ . '/../Templates/tools.php';
    }

    public static function save_product()
    {
        $nonce = Request::post('_wpnonce');
        $messages = Helper::getDataFromFile('Messages');
        $param = '';

        if (wp_verify_nonce($nonce, 'unify-product') && Request::post('check_submit') == 'update_product') {
            $fields = ['codeclouds_unify_connection', 'codeclouds_unify_shipping', 'codeclouds_unify_offer_id', 'codeclouds_unify_billing_model_id', 'codeclouds_unify_group_id'];

            foreach (Request::post()['map'] as $post_id => $value) {
                foreach ($value as $field_key => $field_val) {
                    if (in_array($field_key, $fields)) {
                        if (count(\get_post_meta($post_id, $field_key)) > 0) {
                            if (!empty($field_val)) {
                                /**
                                 * If the custom field already has a value, update it.
                                 */
                                var_dump(\update_post_meta($post_id, $field_key, trim(esc_attr($field_val))));
                            } else {
                                /**
                                 * Delete the meta key if there's no value
                                 */
                                \delete_post_meta($post_id, $field_key);
                            }
                        } else {
                            /**
                             * If the custom field doesn't have a value, add it.
                             */
                            \add_post_meta($post_id, $field_key, trim(esc_attr($field_val)));
                        }
                    }
                }
            }
            $msg = $messages['PRODUCT_MAP']['SUCCESS'];
            Notice::setFlashMessage('success', $msg);
        } else if (Request::post('check_submit') == 'sort_field') { // sort_field is for sorting
            $orderBy = (!empty(Request::post('orderby'))) ? Request::post('orderby') : 'post_title';
            $order = (!empty(Request::post('order'))) ? Request::post('order') : 'desc';
            $param .= (empty($_GET['orderby'])) ? '&orderby=' . $orderBy : $orderBy;
            $param .= (empty($_GET['order'])) ? '&order=' . $order : $order;
        } else {
            $msg = $messages['COMMON']['ERROR'];
            Notice::setFlashMessage('error', $msg);
        }

        wp_redirect(Request::post('_wp_http_referer') . (!empty($param) ? $param : ''));
        exit();
    }

    public static function save_shipping()
    {
        $nonce = Request::post('_wpnonce');
        $messages = Helper::getDataFromFile('Messages');
        $param = '';

        if (wp_verify_nonce($nonce, 'unify-shipping') && Request::post('check_submit') == 'update_product') {
            $fields = ['crm_shipping_id', 'woo_shipping_method_price', 'crm_shipping_price'];

            foreach (Request::post()['map'] as $post_id => $value) {
                foreach ($value as $field_key => $field_val) {
                    if (in_array($field_key, $fields)) {
                        if (count(\get_post_meta($post_id, $field_key)) > 0) {
                            if (!empty($field_val)) {
                                /**
                                 * If the custom field already has a value, update it.
                                 */
                                var_dump(\update_post_meta($post_id, $field_key, trim(esc_attr($field_val))));
                            } else {
                                /**
                                 * Delete the meta key if there's no value
                                 */
                                \delete_post_meta($post_id, $field_key);
                            }
                        } else {
                            /**
                             * If the custom field doesn't have a value, add it.
                             */
                            \add_post_meta($post_id, $field_key, trim(esc_attr($field_val)));
                        }
                    }
                }
            }

            $msg = $messages['SHIPPING_MAP']['SUCCESS'];
            Notice::setFlashMessage('success', $msg);
        } else {
            $msg = $messages['COMMON']['ERROR'];
            Notice::setFlashMessage('error', $msg);
        }

        wp_redirect(Request::post('_wp_http_referer') . (!empty($param) ? $param : ''));
        exit();
    }
}
