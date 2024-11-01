<?php

namespace CodeClouds\Unify\Actions;
use \CodeClouds\Unify\Service\Request;
use \CodeClouds\Unify\Service\Helper;
use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Model\Connection as Connection_Model;
/**
 * Plugin's Tools.
 * @package CodeClouds\Unify
 */
class Settings
{	
	public static function setting()
	{ 
		$request_url = Request::get();
		$setting_data = \get_option('woocommerce_codeclouds_unify_settings');
		$unify_plugin_activation_date = \get_option('unify_plugin_activation_date');
		$free_trial_license_data = \get_option('woocommerce_codeclouds_unify_free_trial_registation');
		$additional_setting_option = \get_option('woocommerce_codeclouds_unify_paypal_payment_settings');
		$pro_license = \get_option('codeclouds_unify_pro_license');
		$connection_list = \CodeClouds\Unify\Model\Connection::getArrayWithMeta();
		$shipment_list = [ 1 => 'Single Order With Custom Shipping Price', 2 => 'Multiple orders'];
		$paypal_button_size_list = [1 => 'Pill/Rounded', 2 => 'Rectangular'];
		$paypal_button_size_color_list = [1 => 'Gold', 2 => 'Blue', 3 => 'Silver', 4 => 'White', 5 => 'Black'];
		if(!empty($request_url) && isset($request_url['section']) && $request_url['section']==='license-management'){
			include_once __DIR__ . '/../Templates/license-management.php';	
		}else {
			include_once __DIR__ . '/../Templates/setting.php';
			
		}
			
	}

	
	public static function save_settings()
	{
		$data = Connection_Model::get_post_with_meta();
		$active_connection = array();
		if(!empty($data)){
			foreach ( $data['list'] as $connection ) {
      			if ( $connection['post_status'] == 'active') {
        			$active_connection['active_connection'] = $connection['ID'] ;
      			}
    		}
		}

		$active_gateways = array();
        $gateways        = WC()->payment_gateways->payment_gateways();

    	foreach ( $gateways as $id => $gateway ) {
      		if ( $id == 'codeclouds_unify' ||  $id == 'codeclouds_unify_paypal_payment') {
        	$active_gateways[$id] = $gateway->enabled ;
      		}
    	}

		$request = Request::post(); 
		$nonce = $request['_wpnonce'];	
		$messages = Helper::getDataFromFile('Messages');
		$setting_option = \get_option('woocommerce_codeclouds_unify_settings');
		$additional_setting_option = \get_option('woocommerce_codeclouds_unify_paypal_payment_settings');
		
		if (wp_verify_nonce($nonce, 'unify-settings-data'))
		{
			//****** Save to option Starts *********** //
			$fields = ['enabled' => $active_gateways['codeclouds_unify'], 'title' => '', 'description' => '', 'connection' => $active_connection['active_connection'], 'shipment_price_settings' => '','shipping_product_id' => '', 'shipping_product_offer_id' => '', 'shipping_product_billing_id' => '', 'testmode' => 'no', 'enable_debugging' => 'no'];
			$aditional_fields = [ 'paypal_enabled'=>$active_gateways['codeclouds_unify_paypal_payment'], 'paypal_payment_title' => 'PayPal', 'paypal_payment_description' => 'Unify PayPal', 'paypal_payment_mode' => 'no', 'connection' => $active_connection['active_connection'],'paypal_button_size'=> '', 'paypal_button_color'=>'', 'paypal_button_size_selected'=> '1','paypal_button_color_selected'=>'1'];
			
			foreach ($request as $reqKey => $reqValue)
			{				
				if(array_key_exists($reqKey, $fields) && !empty($reqValue)){
					$fields[$reqKey] = \esc_html($reqValue);
				}
			}
			foreach ($request as $additionalReqKey => $additionalReqValue)
			{
				if(array_key_exists($additionalReqKey, $aditional_fields) && !empty($additionalReqValue)){
					if($additionalReqKey == 'paypal_enabled'){
						$aditional_fields['enabled'] = \esc_html($additionalReqValue);
					}
				}
				
			}
			$aditional_fields['title'] = (!empty($additional_setting_option['title'])) ? $additional_setting_option['title'] : 'PayPal';
			$aditional_fields['description'] = (!empty($additional_setting_option['description'])) ? $additional_setting_option['description'] : 'Unify PayPal payment';
			$aditional_fields['paypal_payment_mode'] = (!empty($additional_setting_option['paypal_payment_mode'])) ? $additional_setting_option['paypal_payment_mode'] : 'no';
			$aditional_fields['paypal_button_size'] = (!empty($additional_setting_option['paypal_button_size'])) ? $additional_setting_option['paypal_button_size'] : 1;
			$aditional_fields['paypal_button_color'] = (!empty($additional_setting_option['paypal_button_color'])) ? $additional_setting_option['paypal_button_color'] : 1;
			$aditional_fields['connection'] = $active_connection['active_connection'];

			unset($aditional_fields['paypal_enabled']);

			
			if(empty($setting_option) || empty($additional_setting_option)){
				$result = \add_option('woocommerce_codeclouds_unify_settings', $fields);
				$additional_result = \add_option('woocommerce_codeclouds_unify_paypal_payment_settings', $aditional_fields);
			}else{
				if(!empty($setting_option['connection']) && $setting_option['connection'] != $fields['connection']){
					\wp_update_post( ['ID' => $setting_option['connection'], 'post_status' => 'publish'] );
					\wp_update_post( ['ID' => $fields['connection'], 'post_status' => 'active'] );
				}
				$result = \update_option('woocommerce_codeclouds_unify_settings', $fields);
				$result = \update_option('woocommerce_codeclouds_unify_paypal_payment_settings', $aditional_fields);
			}
			
			//****** Save to option ENDS *********** //
			
				$msg = $messages['SETTINGS']['SAVE'];
				Notice::setFlashMessage('success', $msg);
				
				wp_redirect(Request::post('_wp_http_referer'));
				exit();
				
		}
		
		
		$error_msg = $messages['COMMON']['ERROR'];
		Notice::setFlashMessage('error', $error_msg);
			
		wp_redirect(Request::post('_wp_http_referer'));
		exit();
	}


	public static function save_paypal_settings()
	{
		$data = Connection_Model::get_post_with_meta();
		$active_connection = array();
		if(!empty($data)){
			foreach ( $data['list'] as $connection ) {
      			if ( $connection['post_status'] == 'active') {
        			$active_connection['active_connection'] = $connection['ID'] ;
      			}
    		}
		}

		$request = Request::post();
		$nonce = $request['_wpnonce'];	
		$messages = Helper::getDataFromFile('Messages');
		$additional_setting_option = \get_option('woocommerce_codeclouds_unify_paypal_payment_settings');
		if(wp_verify_nonce($nonce, 'unify-additional-settings-data')){
			//****** Save to option Starts *********** //
			$aditional_fields = ['enabled'=>'yes','paypal_payment_title' => '', 'paypal_payment_description' => '','connection' => $active_connection['active_connection'],'paypal_payment_mode' => 'no', 'paypal_button_size'=> '1','paypal_button_color'=>'1', 'paypal_button_size_selected'=> '1','paypal_button_color_selected'=>'1'];
			
			foreach ($request as $additionalReqKey => $additionalReqValue)
			{ 
				if(array_key_exists($additionalReqKey, $aditional_fields) && !empty($additionalReqValue)){
					if($additionalReqKey == 'paypal_payment_title'){
						$aditional_fields['title'] = \esc_html($additionalReqValue);
					}else if($additionalReqKey == 'paypal_payment_description'){
						$aditional_fields['description'] = \esc_html($additionalReqValue);
					}else if($additionalReqKey == 'paypal_button_size'){
						$aditional_fields['paypal_button_size'] = !empty($additionalReqValue)?\esc_html($additionalReqValue):$additional_setting_option['paypal_button_size'];
					}else if($additionalReqKey == 'paypal_button_color'){
						$aditional_fields['paypal_button_color'] = !empty($additionalReqValue)?\esc_html($additionalReqValue):$additional_setting_option['paypal_button_color'];
					}else if($additionalReqKey == 'paypal_payment_mode'){
						$aditional_fields['paypal_payment_mode'] = \esc_html($additionalReqValue);
					}else if($additionalReqKey == 'paypal_button_size_selected'){
						$aditional_fields['paypal_button_size_selected'] = \esc_html($additionalReqValue);
					}else if($additionalReqKey == 'paypal_button_color_selected'){
						$aditional_fields['paypal_button_color_selected'] = \esc_html($additionalReqValue);
					}else{
						$aditional_fields[$additionalReqKey] = \esc_html($additionalReqValue);
					}
				}
			}
			unset($aditional_fields['paypal_payment_title']);
			unset($aditional_fields['paypal_payment_description']);
			
			if(empty($additional_setting_option)){
				$result = \add_option('woocommerce_codeclouds_unify_paypal_payment_settings', $aditional_fields);
			}else{
				$result = \update_option('woocommerce_codeclouds_unify_paypal_payment_settings', $aditional_fields);
			}
			
			//****** Save to option ENDS *********** //
			
				$msg = $messages['SETTINGS']['SAVE'];
				Notice::setFlashMessage('success', $msg);
				
				wp_redirect(Request::post('_wp_http_referer'));
				exit();
				
		}
		
		$error_msg = $messages['COMMON']['ERROR'];
		Notice::setFlashMessage('error', $error_msg);
			
		wp_redirect(Request::post('_wp_http_referer'));
		exit();
	}

}
