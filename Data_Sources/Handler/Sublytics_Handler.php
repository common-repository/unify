<?php

namespace CodeClouds\Unify\Data_Sources\Handler;

//use \CodeClouds\Unify\Actions\DeclineManager;
/**
 * Handle Sublytics API.
 * @package CodeClouds\Unify
 */
class Sublytics_Handler extends \CodeClouds\Unify\Abstracts\Order_Abstract
{
    private $debug = false;

    /**
     * Connection authentication.
     * @param array $args Information for API.
     */
    public function __construct($args)
    {
        $this->api_payload = $args;
        $this->messages = \CodeClouds\Unify\Service\Helper::getDataFromFile('Messages');
    }

    /*
     * Overriding the method for preparing customer payload
     */

    public function set_config($connection, $name)
    {
        $this->api_config = \file_get_contents(__DIR__ . '/../../Config/' . strtolower($connection) . '/om_' . $name . '.config.json');
    }

    /**
     * Call API for payment process.
     * @return array
     */
    public function make_order()
    {
        try
        {
            $payment_method = $this->api_payload['payment_method'];
            $context = array('source' => 'Unify-App');
            $wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');

            if (!empty($wc_codeclouds_unify_settings['enable_debugging']) && $wc_codeclouds_unify_settings['enable_debugging'] == 'yes') {
                $this->debug = true;
            }

            $this->format_data();
            $this->prepare_shipping();
            $this->get_product_variant_payload();
            $response = ($payment_method == 'codeclouds_unify_paypal_payment') ? $this->process_crm_paypal() : $this->process_to_crm();

            if ($payment_method == 'codeclouds_unify_paypal_payment') {
                return $this->api_response;
            } else {
                $response = $this->api_response;
                if ($response['success'] == 1) {
                    $order_id = $response['data']['transaction']['order_id'];
                    $transaction_id = $response['data']['transaction']['transaction_id'];
                    $this->api_response['responseCode'] = 100;
                    $this->api_response['errorFound'] = 0;

                    return ['status' => true, 'orderIds' => $order_id, 'transactionIds' => $transaction_id, 'notes' => [], 'shipping_ids' => $this->api_config['shippingId']];
                }
                if ($response['success'] != 1) {
                    $this->api_response['declineReason'] = $response['message'];
                    throw new \Exception((isset($response['message']) && !empty($response['message']) ? $response['message'] : $response['message']), 9999);
                }
            }
        } catch (\Exception $ex) {
            if ($ex->getCode() == 9999 && !empty($ex->getMessage())) {
                throw new \Exception($ex->getMessage());
            }

            throw new \Exception('Payment Failed! Please make sure you have entered the correct information');
        }
    }

    private function prepare_shipping()
    {

        $chosen_wooCommerce_shipping = WC()->session->get('chosen_shipping_methods')[0];
        $chosen_wooCommerce_shipping_array = explode(":", $chosen_wooCommerce_shipping);
        $chosen_wooCommerce_shipping_ID = !empty($chosen_wooCommerce_shipping_array) ? $chosen_wooCommerce_shipping_array[1] : '';
        $crm_shipping_ID_array = get_post_meta($chosen_wooCommerce_shipping_ID, "crm_shipping_id");
        $crm_shipping_ID = !empty($crm_shipping_ID_array) ? $crm_shipping_ID_array[0] : '';

        $crm_shipping_price_array = get_post_meta($chosen_wooCommerce_shipping_ID, "crm_shipping_price");
        $crm_shipping_price = !empty($crm_shipping_price_array) ? $crm_shipping_price_array[0] : '';

        $crm_shipping_price = !empty(WC()->cart->get_shipping_total()) ? WC()->cart->get_shipping_total() + WC()->cart->get_shipping_tax() : $crm_shipping_price;

        if (!empty($crm_shipping_ID)) {
            $this->api_config['shipping_profile_id'] = $crm_shipping_ID;
        }
        /*
         * If no Custom shipping price given, then remove from payload
         */
        $offers = $this->api_config['offers'];

        // for ($i = 0; $i < count($offers); $i++) {
        //     $this->api_config['offers'][$i]['order_offer_shipping'] = $crm_shipping_price;
        // }

        $this->api_config['shipping_profile_id'] = (isset($this->api_config['shipping_profile_id'])) ? $crm_shipping_ID : '';
    }

    /*
     * prepare product variant for payload
     */
    private function get_product_variant_payload()
    {
        foreach ($this->api_payload['cart_items'] as $key => $product) {
            if (!empty($product['order_offer_item_options'])) {
                foreach ($product['order_offer_item_options'] as $variant) {
                    $this->api_config['offers'][$key]['order_offer_item_options'][] = [
                        'item_option_id' => $variant['item_option_id'],
                        'item_option_value_id' => $variant['item_option_value_id'],
                    ];
                }
            }
        }
    }

    /*
     * processing the request to CRM
     */
    private function process_crm_paypal()
    {
        try
        {
            $context = array('source' => 'Unify-App');
            if ($this->debug) {
                $logger = wc_get_logger();
                $temp_config = $this->api_config;
                $rep_num = substr($temp_config['card_number'], 0);
                $to_rep_num = '';
                for ($i = strlen($rep_num); $i > 0; $i--) {
                    $to_rep_num .= '*';
                }
                $temp_config['card_number'] = substr_replace($temp_config['card_number'], $to_rep_num, 0);

                $to_rep_cvv = '';
                for ($i = strlen($temp_config['card_cvv']); $i > 0; $i--) {
                    $to_rep_cvv .= '*';
                }
                $temp_config['card_cvv'] = substr_replace($temp_config['card_cvv'], '***', 0);
                $temp_config['card_exp_month'] = substr_replace($temp_config['card_exp_month'], '**', 0);
                $temp_config['card_exp_year'] = substr_replace($temp_config['card_exp_year'], '****', 0);

                $to_rep_user_id = '';
                for ($i = strlen($temp_config['user_id']); $i > 0; $i--) {
                    $to_rep_cvv .= '*';
                }
                $temp_config['user_id'] = substr_replace($temp_config['user_id'], '***', 0);
                $temp_config['user_password'] = substr_replace($temp_config['user_password'], '****', 0);

                $logger->info(('Sublytics Request: ' . json_encode($temp_config, JSON_PRETTY_PRINT)), $context);
            }
            unset($this->api_config['card_type_id']);
            unset($this->api_config['card_number']);
            unset($this->api_config['card_cvv']);
            unset($this->api_config['card_exp_month']);
            unset($this->api_config['card_exp_year']);

            $this->api_config['payment_method_id'] = 6;
            $this->api_config['order_notes'] = $this->api_payload['description'];
            $url = 'https://' . rtrim($this->api_payload['config']['endpoint']) . '/api/order/doAdd';
            
            $args = array(
                'body'        => json_encode($this->api_config),
                'timeout' => '5000',
                'httpversion' => '1.0',
                'headers'     => [
                    'Content-Type' => 'application/json'
                ],
                'cookies'     => [],
            );		
            $content = wp_remote_post($url, $args );
            // $this->api_response=$content;
            if(is_wp_error($content)){
                throw new \Exception(sanitize_text_field('Something went wrong with request.'), 9999);
            }else{
                $json_response = json_decode($content['body'], true);
                $this->api_config['order_id'] = $json_response['data']['order']['id'];

                $this->api_config['redirect_url'] = $this->api_config['redirect_url'] . "&ordID=" . $this->api_config['order_id'];

                $url = 'https://' . rtrim($this->api_payload['config']['endpoint']) . '/api/order/doProcess';
                
                $args = array(
                    'body'        => json_encode($this->api_config),
                    'timeout' => '5000',
                    'httpversion' => '1.0',
                    'headers'     => [
                        'Content-Type' => 'application/json'
                    ],
                    'cookies'     => [],
                );		
                $content2 = wp_remote_post($url, $args );

                if(is_wp_error($content2)){
                    throw new \Exception(sanitize_text_field('Something went wrong with request.'), 9999);
                }else{
                    $json_response2 = json_decode($content2['body'], true);

                    if ($json_response2['success']) {
                        // $this->api_response = $content;
                        $sandbox_url = $json_response2['data']['transaction']['post_data'];
                        $responseArr = ['result' => $sandbox_url, 'messages' => ''];
                        $this->api_response = json_encode($responseArr);
                    } else {
                        $responseArr = ['result' => 'failure', 'messages' => ''];
                        $this->api_response = json_encode($responseArr);
                        throw new \Exception((isset($json_response2['message']) && !empty($json_response2['message']) ? $json_response2['message'] : $json_response2['message']), 9999);
                    }
                }
            }
        } catch (\Exception $ex) {

            if ($ex->getCode() == 9999 || $ex->getCode() == 0) {
                throw new \Exception($ex->getMessage(), 9999);
            }

            throw new \Exception($this->messages['COMMON']['PAYMENT_FAILED']);
        }
    }

    /*
     * processing the request to CRM
     */
    private function process_to_crm()
    {
        try
        {

            $context = array('source' => 'Unify-App');
            $wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');

            if (!empty($wc_codeclouds_unify_settings['enable_debugging']) && $wc_codeclouds_unify_settings['enable_debugging'] == 'yes') {
                $this->debug = true;
            }

            $this->api_config['payment_method_id'] = 1;

            if ($this->debug) {
                $logger = wc_get_logger();
                $temp_config = $this->api_config;
                $rep_num = substr($temp_config['card_number'], 0);
                $to_rep_num = '';
                for ($i = strlen($rep_num); $i > 0; $i--) {
                    $to_rep_num .= '*';
                }
                $temp_config['card_number'] = substr_replace($temp_config['card_number'], $to_rep_num, 0);

                $to_rep_cvv = '';
                for ($i = strlen($temp_config['card_cvv']); $i > 0; $i--) {
                    $to_rep_cvv .= '*';
                }
                $temp_config['card_cvv'] = substr_replace($temp_config['card_cvv'], '***', 0);
                $temp_config['card_exp_month'] = substr_replace($temp_config['card_exp_month'], '**', 0);
                $temp_config['card_exp_year'] = substr_replace($temp_config['card_exp_year'], '****', 0);

                $to_rep_user_id = '';
                for ($i = strlen($temp_config['user_id']); $i > 0; $i--) {
                    $to_rep_cvv .= '*';
                }
                $temp_config['user_id'] = substr_replace($temp_config['user_id'], '***', 0);
                $temp_config['user_password'] = substr_replace($temp_config['user_password'], '****', 0);

                $logger->info(('Sublytics Request: ' . json_encode($temp_config, JSON_PRETTY_PRINT)), $context);
            }
            
            $api_response = $this->newOrder();
            $this->api_response = json_decode($api_response['body'], true);
            /*
             * Adding note to order
             */
            $order_info = json_decode($api_response['body'], true);
            $order_id = $order_info['data']['transaction']['order_id'];
            if(!empty($order_id)){
                $resp = $this->orderDoNote(
                    [
                        'order_id' => $order_id,
                        'user_id' => $this->api_payload['config']['api_username'],
                        'user_password' => $this->api_payload['config']['api_password'],
                        'order_notes' => $this->api_payload['description'],
                    ]
                );
                if(!$resp){
                    throw new \Exception($this->messages['COMMON']['PAYMENT_FAILED']); 
                }
            }else{
                throw new \Exception($this->messages['COMMON']['PAYMENT_FAILED']);
            }    
        } catch (\Exception $ex) {

            if ($ex->getCode() == 9999 || $ex->getCode() == 0) {
                throw new \Exception($ex->getMessage(), 9999);
            }

            throw new \Exception($this->messages['COMMON']['PAYMENT_FAILED']);
        }
    }
    private function newOrder()
	{
        $apiUrl = 'https://' . rtrim($this->api_payload['config']['endpoint']) . '/api/order/doAddProcess';
		$args = array(
			'body'        => json_encode($this->api_config),
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'cookies'     => [],
		);
		$response = wp_remote_post($apiUrl, $args );

        if(is_wp_error($response)){
            return ['body'=>json_encode([
                'data'=>['transaction'=>['order_id'=>'']],         
            ],true)];
        }else{
            return $response;
        }
	}

    private function orderDoNote($params)
	{
        $apiUrl = 'https://' . rtrim($this->api_payload['config']['endpoint']) . '/api/order/doNote';
		$args = array(
			'body'        => json_encode($params),
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'cookies'     => [],
		);
		$response = wp_remote_post($apiUrl, $args );

        if(is_wp_error($response)){
            return ['status'=>false];
        }else{
            return $response;
        }
	}

}
