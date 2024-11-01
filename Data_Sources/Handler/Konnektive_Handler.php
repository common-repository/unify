<?php

namespace CodeClouds\Unify\Data_Sources\Handler;

/**
 * Handle Konnektive API.
 * @package CodeClouds\Unify
 */
class Konnektive_Handler extends \CodeClouds\Unify\Abstracts\Order_Abstract
{
	private $debug = false;
	
    /**
     * Connection authentication.
     * @param array $args Information for API.
     */
    public function __construct($args)
    {
        $args['pay_source'] = 'CREDITCARD';
        $this->api_payload  = $args;
    }

    /**
     * Call API for payment process.
     * @return array
     */
    public function make_order()
	{
		try
		{
			$context = array('source' => 'Unify-App');
			$wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');

			if (!empty($wc_codeclouds_unify_settings['enable_debugging']) && $wc_codeclouds_unify_settings['enable_debugging'] == 'yes')
			{
				$this->debug = true;
			}

			$this->format_data();

			if ($this->debug)
			{
				$logger = wc_get_logger();
				$temp_config = $this->api_config;
				$rep_num = substr($temp_config['cardNumber'], 6, -4);
				$to_rep_num = '';
				for ($i = strlen($rep_num); $i > 0; $i--)
				{
					$to_rep_num .= '*';
				}
				$temp_config['cardNumber'] = substr_replace($temp_config['cardNumber'], $to_rep_num, 6, -4);

				$to_rep_cvv = '';
				for ($i = strlen($temp_config['cardSecurityCode']); $i > 0; $i--)
				{
					$to_rep_cvv .= '*';
				}
				$temp_config['cardSecurityCode'] = str_replace($temp_config['cardSecurityCode'], $to_rep_cvv, $temp_config['cardSecurityCode']);

				$logger->info(('KK Request: ' . json_encode($temp_config, JSON_PRETTY_PRINT)), $context);
			}
			$this->api_response = $this->newOrder($this->api_config);

			if ($this->debug)
			{
				$logger->info(('KK Response: ' . json_encode($this->api_response, JSON_PRETTY_PRINT)), $context);
			}


			if (isset($this->api_response->result) && $this->api_response->result == 'SUCCESS')
			{
				$this->addNote($this->api_response->message->customerId, $this->api_payload['description'], $this->api_response->message->orderId);
				
				return ['status' => true, 'orderIds' => $this->api_response->message->orderId, 'transactionIds' => $this->api_response->message->merchantTxnId, 'notes' => []];
			}

			throw new \Exception((!empty($this->api_response->message) ? (is_array($this->api_response->message) ? implode("<br/>", $this->api_response->message) : $this->api_response->message) : ''), 9999);
			
		}
		catch (\Exception $ex)
		{
			if ($ex->getCode() == 9999 && !empty($ex->getMessage()))
			{
				throw new \Exception($ex->getMessage());
			}

			throw new \Exception('Payment Failed! Please make sure you have entered the correct information.');
		}
	}

	private function addNote($customerId, $note, $orderId = NULL)
	{
		$this->customerAddnote([
				'customerId' => $customerId,
				'message' => $note
			]);
	}

	private function newOrder()
	{
		$apiUrl = 'https://api.konnektive.com/order/import';
		$params = array_merge(['loginId'=>$this->api_payload['config']['api_username'],'password'=>$this->api_payload['config']['api_password']],$this->api_config);
		$args = array(
			'body'        => [],
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'cookies'     => [],
		);	
		$response = wp_remote_post($apiUrl.'/?'.http_build_query($params), $args );

		if(is_wp_error($response)){
            return ['result'=>false,'message'=>sanitize_text_field('Something went wrong with request.')];
        }else{
            return json_decode($response['body']);
		}
	}

	private function customerAddnote($note)
	{
		$apiUrl = 'https://api.konnektive.com/customer/addnote';
		$params = array_merge(['loginId'=>$this->api_payload['config']['api_username'],'password'=>$this->api_payload['config']['api_password']],$note);
		$args = array(
			'body'        => [],
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'cookies'     => [],
		);	
		$response = wp_remote_post($apiUrl.'/?'.http_build_query($params), $args );

		if(is_wp_error($response)){
            return ['result'=>false,'message'=>sanitize_text_field('Something went wrong with request.')];
        }else{
            return json_decode($response['body']);
		}
	}

}
