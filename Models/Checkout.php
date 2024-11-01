<?php

namespace CodeClouds\Unify\Model;

/**
 * Connection post type model.
 * @package CodeClouds\Unify
 */
class Checkout
{
	private static $platform_endpoint = 'https://platform.unify.to/';
	/*
	 * Send data to unify
	 */
	public static function sendStoreData($cart_data)
	{
		$calling_method = "checkout";
		$curl_url = self::$platform_endpoint.$calling_method;
		
		$args = array(
			'body'        => [
				'woo_cart_data' => $cart_data
			],
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json',
				'Authorization' => 'X-Auth-token: ' . $auth_token
			],
			'cookies'     => [],
		);		
		$response = wp_remote_post( $curl_url, $args );
	    return $response;
	}


	

}