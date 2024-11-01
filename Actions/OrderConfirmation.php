<?php

namespace CodeClouds\Unify\Actions;

use \CodeClouds\Unify\Model\ConfigEncryption;

/**
 * Order actions.
 * @package CodeClouds\Unify
 */
class OrderConfirmation
{

    public static function unify_gateway_disable_paypal($available_gateways)
    {
        $crm_connection_id = WC()->payment_gateways->payment_gateways()['codeclouds_unify']->settings['connection'];
        $crm_connection_meta = get_post_meta($crm_connection_id);
        
        $crm_conection_name = '';
        if(!empty($crm_connection_meta)){
            if(isset($crm_connection_meta['unify_connection_crm_salt'][0]) && isset($crm_connection_meta['unify_connection_crm'][0])){
                $crm_conection_name = ConfigEncryption::metaDecryptSingle($crm_connection_meta['unify_connection_crm'][0], $crm_connection_meta['unify_connection_crm_salt'][0]);
            }else if(isset($crm_connection_meta['unify_connection_crm'][0])){
                $crm_conection_name = $crm_connection_meta['unify_connection_crm'][0];
            }
        }

        if (!is_admin()) {
            if (isset($available_gateways['codeclouds_unify_paypal_payment']) && !in_array($crm_conection_name,['limelight','sublytics'])) {
                unset($available_gateways['codeclouds_unify_paypal_payment']);
            }
        }
        return $available_gateways;
    }

/*Truncate response data from url and rebuild the url*/
    public static function truncatePaypalResponseParams($url)
    {
        $urlComponentArr = parse_url($url);
        parse_str($urlComponentArr['query'], $output);
        $wc_key = $output['key'];
        $order_id = $output['orderId'];
        $tran_id = $output['transactionID'];

        $page_id = isset($output['page_id']) ? "page_id=" . $output['page_id'] : '';
        $order_received = isset($output['order-received']) ? "&order-received=" . $output['order-received'] . "&" : '';

        $plain_str = $page_id . $order_received; //when WP permalink settings is plain

        $url = $urlComponentArr['scheme'] . "://" . $urlComponentArr['host'] . $urlComponentArr['path'] . "?" . $plain_str . "key=" . $wc_key . "&orderId=" . $order_id . "&transactionID=" . $tran_id . "&orderStatus=1";

        return $url;
    }

    public static function unsetSessionOtherPages()
    {
        $cur_url = !empty($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        if (strpos($cur_url, "checkout") == false) {
            if (!session_id()) {session_start();};
            if (isset($_SESSION['paypal_decline_msg'])) {
                unset($_SESSION['paypal_decline_msg']);
            }
        }
    }

    public static function unify_front_end_function()
    {

        if (!is_admin()) {
            self::unsetSessionOtherPages();

            if (isset($_GET['cancel']) && $_GET['cancel'] == 1) {
                $url = wc_get_checkout_url() . "/?orderStatus=0";
                if (!session_id()) {
                    session_start();
                };
                $_SESSION['paypal_decline_msg'] = urldecode(sanitize_text_field(wp_unslash($_GET["declineReason"])));
                wp_redirect($url);
                exit;
            }

            if (!empty($_GET["responseCode"])) {
                $order = \wc_get_order(sanitize_text_field(wp_unslash($_GET["unify_order"])));
                $domain = sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']));
                $url = "http://" . $domain . sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']));
                $debug = false;
                $wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');
                if (!empty($wc_codeclouds_unify_settings['enable_debugging']) && $wc_codeclouds_unify_settings['enable_debugging'] == 'yes') {
                    $debug = true;
                }

                $connection = get_post_meta($wc_codeclouds_unify_settings['connection']);
                $crm_conection_name = '';
                if(!empty($connection)){
                    if(isset($connection['unify_connection_crm_salt'][0]) && isset($connection['unify_connection_crm'][0])){
                        $crm_conection_name = ConfigEncryption::metaDecryptSingle($connection['unify_connection_crm'][0], $connection['unify_connection_crm_salt'][0]);
                    }else if(isset($connection['unify_connection_crm'][0])){
                        $crm_conection_name = $connection['unify_connection_crm'][0];
                    }
                }

                $response = $_REQUEST;
                $orderid = !empty($_REQUEST['orderId']) ? sanitize_text_field(wp_unslash($_REQUEST['orderId'])) : '';
                $tran_id = !empty($_REQUEST['transactionID']) ? sanitize_text_field(wp_unslash($_REQUEST['transactionID'])) : '';
                $hasInserted = get_post_meta($order->get_id(), '_codeclouds_unify_order_id', true);

                if ($_GET["responseCode"] == 100) {
                    if ($orderid != '' && $hasInserted == '') {
                        $order->update_meta_data('_codeclouds_unify_order_id', $orderid);
                        $order->update_meta_data('_codeclouds_unify_transaction_id', $tran_id);
                        $order->update_meta_data('_codeclouds_unify_connection', $crm_conection_name);
                        $order->update_meta_data('_codeclouds_unify_connection_id', $wc_codeclouds_unify_settings['connection']);
                        if ($crm_conection_name === 'limelight') {
                            $chosen_wooCommerce_shipping = WC()->session->get('chosen_shipping_methods')[0];
                            $chosen_wooCommerce_shipping_array = explode(":", $chosen_wooCommerce_shipping);
                            $chosen_wooCommerce_shipping_ID = !empty($chosen_wooCommerce_shipping_array) ? $chosen_wooCommerce_shipping_array[1] : '';
                            $crm_shipping_ID_array = get_post_meta($chosen_wooCommerce_shipping_ID, "crm_shipping_id");
                            $crm_shipping_ID = !empty($crm_shipping_ID_array) ? $crm_shipping_ID_array[0] : '';
                            $order->update_meta_data('_codeclouds_unify_shipping_id', $crm_shipping_ID);
                        }
                        $order->payment_complete($orderid);
                        WC()->session->set('order_awaiting_payment', false);
                        $order->update_status('completed');
                        $order->save();

                        if ($debug) {
                            $context = array('source' => 'Unify-App');
                            $logger = wc_get_logger();
                            $logger->info(('LL Response: ' . json_encode($response, JSON_PRETTY_PRINT)), $context);
                            WC()->session->__unset('chosen_payment_method');
                        }

                    }

                    wp_redirect(self::truncatePaypalResponseParams($url));

                    /**
                     * close popup windowafter successful payment
                     */
                    $additional_setting_option = \get_option('woocommerce_codeclouds_unify_paypal_payment_settings');
                    if ($additional_setting_option['paypal_payment_mode'] == 'no') {
                        exit;
                    }

                } else {
                    if ($orderid != '' && $hasInserted == '') {
                        $order->update_meta_data('_codeclouds_unify_order_id', $orderid);
                        $order->update_meta_data('_codeclouds_unify_transaction_id', $tran_id);
                        $order->update_meta_data('_codeclouds_unify_connection', $crm_conection_name);
                        $order->update_meta_data('_codeclouds_unify_connection_id', $wc_codeclouds_unify_settings['connection']);
                        if ($crm_conection_name === 'limelight') {
                            $chosen_wooCommerce_shipping = WC()->session->get('chosen_shipping_methods')[0];
                            $chosen_wooCommerce_shipping_array = explode(":", $chosen_wooCommerce_shipping);
                            $chosen_wooCommerce_shipping_ID = !empty($chosen_wooCommerce_shipping_array) ? $chosen_wooCommerce_shipping_array[1] : '';
                            $crm_shipping_ID_array = get_post_meta($chosen_wooCommerce_shipping_ID, "crm_shipping_id");
                            $crm_shipping_ID = !empty($crm_shipping_ID_array) ? $crm_shipping_ID_array[0] : '';
                            $order->update_meta_data('_codeclouds_unify_shipping_id', $crm_shipping_ID);
                        }
                        $order->payment_complete($orderid);
                        WC()->session->set('order_awaiting_payment', false);
                        $order->update_status('pending');
                        $order->save();

                        $order->save();
                        if ($debug) {
                            $context = array('source' => 'Unify-App');
                            $logger = wc_get_logger();
                            $logger->info(('LL Response: ' . json_encode($response, JSON_PRETTY_PRINT)), $context);
                            WC()->session->__unset('chosen_payment_method');
                        }

                    }
                    $url = wc_get_checkout_url() . "/?orderStatus=0";
                    if (!session_id()) {
                        session_start();
                    };
                    $_SESSION['paypal_decline_msg'] = urldecode(sanitize_text_field(wp_unslash($_GET["declineReason"])));
                    wp_redirect(wc_get_checkout_url() . '/?orderStatus=1');
                    exit;
                }

            }
            if (!isset($_GET["responseCode"]) && empty($_GET["responseCode"])) {
                $wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');
                if($wc_codeclouds_unify_settings){
                    $connection = get_post_meta($wc_codeclouds_unify_settings['connection']);
                    $crm_conection_name = '';
                    if(!empty($connection)){
                        if(isset($connection['unify_connection_crm_salt'][0]) && isset($connection['unify_connection_crm'][0])){
                            $crm_conection_name = ConfigEncryption::metaDecryptSingle($connection['unify_connection_crm'][0], $connection['unify_connection_crm_salt'][0]);
                        }else if(isset($connection['unify_connection_crm'][0])){
                            $crm_conection_name = $connection['unify_connection_crm'][0];
                        }
                    }
                    if ($crm_conection_name == 'sublytics') {
                        $api_username = ConfigEncryption::metaDecryptSingle($connection['unify_connection_api_username'][0], $connection['unify_connection_api_username_salt'][0]);
                        $api_password = ConfigEncryption::metaDecryptSingle($connection['unify_connection_api_password'][0], $connection['unify_connection_salt'][0]);
                        $endpoint = ConfigEncryption::metaDecryptSingle($connection['unify_connection_endpoint'][0], $connection['unify_connection_endpoint_salt'][0]);

                        $context = array('source' => 'Unify-App');
                        $logger = wc_get_logger();
                        /**
                         * Preparing payload for Final PayPal Transaction API Request for Suvlytics.
                         */
                        if (isset($_GET['token']) && isset($_GET['PayerID'])) {

                            /**
                             * Preparing payload for Final PayPal Transaction API Request for Suvlytics.
                             */
                            $order = \wc_get_order(sanitize_text_field(wp_unslash($_GET["unify_order"])));
                            $order->update_meta_data('_codeclouds_unify_order_id', sanitize_text_field(wp_unslash($_GET['ordID'])));
                            $order->update_meta_data('_codeclouds_unify_transaction_id', sanitize_text_field(wp_unslash($_GET['ordID'])));
                            $order->set_transaction_id(sanitize_text_field(wp_unslash($_GET['ordID'])));

                            $urlend = 'https://' . rtrim($endpoint) . '/api/order/doProcessPaypal';
                            
                            $args = array(
                                'body'        => [
                                    'user_id'=>$api_username,
                                    'user_password'=>$api_password,
                                    'order_id' => sanitize_text_field(wp_unslash($_GET['ordID'])),
                                    'transaction_token' => sanitize_text_field(wp_unslash($_GET['token'])),
                                ],
                                'httpversion' => '1.0',
                                'headers'     => [
                                    'Content-Type' => 'application/json'
                                ],
                                'cookies'     => [],
                            );		
                            $content2 = wp_remote_post( $urlend, $args );
                            $json_response = json_decode($content2['body'], true);

                            $transaction_id = '';
                            $shipping_id = '';
                            if (!empty($json_response['data'])) {
                                $transaction_id = $json_response['data']['transaction']['transaction_id'];
                                $shipping_id = $json_response['data']['transaction']['shipment_id'];
                            }

                            $logger->info(('Sublytics Response: ' . json_encode($json_response, JSON_PRETTY_PRINT)), $context);

                            /**
                             * Updating meta information with api response for Sublytics.
                             */
                            $order->update_meta_data('_codeclouds_unify_transaction_id', $transaction_id);
                            $order->update_meta_data('_codeclouds_unify_connection', $crm_conection_name);
                            $order->update_meta_data('_codeclouds_unify_connection_id', $wc_codeclouds_unify_settings['connection']);
                            $order->payment_complete(sanitize_text_field(wp_unslash($_GET["unify_order"])));
                            $order->update_meta_data('_codeclouds_unify_shipping_id', $shipping_id);
                            WC()->session->set('order_awaiting_payment', false);
                            $order->update_status('completed');
                            $order->save();                        
                        }

                        self::complete_sublytics_order($api_username, $api_password, $endpoint);
                    }
                }
            }
        }
    }

/**
 * Updating Shipping & other information with api response for Sublytics.
 */
    public static function complete_sublytics_order($api_username, $api_password, $endpoint)
    {
        if (isset($_GET['key']) || isset($_GET["unify_order"])) {
            $order = \wc_get_order(sanitize_text_field(wp_unslash($_GET["unify_order"])));
            $order_data = $order->get_data();

            $transaction_id = $order_data['transaction_id'];

            /**
            Updating Transaction ID for paypal payment
             */
            if (isset($_GET['ordID'])) {
                $transaction_id = sanitize_text_field(wp_unslash($_GET['ordID']));
                $order->set_transaction_id(sanitize_text_field(wp_unslash($_GET['ordID'])));                
                $order->update_meta_data('_codeclouds_unify_transaction_id', $transaction_id);
            }

            /**
            Get Order details from CRM
             */
            $orderViewPayload = [];
            $orderViewPayload['user_id'] = $api_username;
            $orderViewPayload['user_password'] = $api_password;
            $orderViewPayload['order_id'] = $transaction_id;
            $orderViewPayload['with'] = 'transactions';

            $query = $transaction_id . '?' . http_build_query($orderViewPayload);
            $urlend = 'https://' . rtrim($endpoint) . '/api/order/view/' . $query;
            
            $args = array(
                'body'        => [],
                'httpversion' => '1.0',
                'headers'     => [
                    'Content-Type' => 'application/json'
                ],
                'cookies'     => [],
            );		
            $content2 = wp_remote_get( $urlend, $args );
            $json_response = json_decode($content2['body'], true);

            /**
            Last transaction will contain the details
             */

            if(isset($json_response['data'])){
                $transaction_count = count($json_response['data']['order']['transactions']);
                if ($transaction_count > 0) {
                    $transaction_count -= 1;
                }
    
                $trx_data = $json_response['data']['order']['transactions'][$transaction_count];
                $trx_shipping_price = $trx_data['transaction_shipping'];
                $trx_total = $trx_data['transaction_total'];
                $order->update_meta_data('_shipping_total', $trx_shipping_price);
    
                /**
                Updating shipping charge in final order display page (Sut-total)
                 */
                $order->set_shipping_total($trx_shipping_price);
    
                /**
                Updating Order Total in final order display page (Sut-total)
                 */
                $order->set_total($trx_total);    
            }

            /**
            Updating shipping charge in final order display page (line items)
             */
            $line_items_shipping = $order->get_items('shipping');
            foreach ($line_items_shipping as $item_id => $item) {
                $item->set_total($trx_shipping_price);
            }

            /**
            Updating order status to complete.
             */
            WC()->session->set('order_awaiting_payment', false);
            $order->update_status('completed');

            $order->save();

        }

    }

/* Show Decline Message If order Canceled from Paypal Window*/
    public static function wnd_checkout_code()
    {
            $wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');
            $connection = get_post_meta($wc_codeclouds_unify_settings['connection']);
            $crm_conection_name = ConfigEncryption::metaDecryptSingle($connection['unify_connection_crm'][0], $connection['unify_connection_crm_salt'][0]);
            if ($crm_conection_name != 'sublytics' && isset($_SESSION['paypal_decline_msg'])) {
                include_once __DIR__ . '/../Templates/paypal-decline-msg.php';
            }
    }

}
