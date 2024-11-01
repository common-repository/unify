<?php

namespace CodeClouds\Unify\Model;
use \CodeClouds\Unify\Model\ConfigEncryption;

/**
 * Connection post type model.
 * @package CodeClouds\Unify
 */
class Connection
{

	/**
	 * Get an array list of all the connection posts.
	 * @return array
	 */
	public static function getArray()
	{
		$connections = [
			'' => 'Please select a connection'
		];
		$args = [
			'post_type' => array('unify_connections'),
			'post_status' => array('publish', 'active'),
		];
		$loop = new \WP_Query($args);

		foreach ($loop->posts as $post)
		{
			$connections[$post->ID] = $post->post_title;
		}

		return $connections;
	}

	/**
	 * Get a connection by ID.
	 * @param int|WP_Post|null $ID
	 * @return array
	 */
	public static function get_post($ID)
	{
		return \get_post($ID);
	}

	/**
	 * Get post meta by connection ID.
	 * @param int $ID
	 * @return array
	 */
	public static function get_post_meta($ID)
	{
		return \get_post_meta($ID);
	}

	public static function get_post_with_meta($request = [], $post_ID = '')
	{
		$all_connection = [];
		$args['post_type'] = array('unify_connections');
		if (!empty($post_ID))
		{
//			$args['post'] = $post_ID;
			$args['p'] = $post_ID;
		}

		if (!empty($request))
		{
			$args['posts_per_page'] = $request['posts_per_page'];
			$args['paged'] = $request['paged'];

			if (!empty($request['s']))
			{
				$args['s'] = $request['s'];
			}

			if (!empty($request['m']))
			{
				$args['m'] = $request['m'];
			}
			if (!empty($request['post_status']))
			{
				$args['post_status'] = $request['post_status'];
			}
			
			if($request['orderby']){
				$args['orderby'] = $request['orderby'];
			}			
			if($request['order']){
				$args['order'] = $request['order'];
			}
		}

		$connection = new \WP_Query($args);
		if (!empty($connection->posts))
		{
			foreach ($connection->posts as $key => $value)
			{
				$all_connection[$key] = (array) $value;
				$metas = Connection::get_post_meta($value->ID);
				foreach ($metas as $k => $val)
				{
					if (in_array($k, ['unify_connection_crm', 'unify_connection_endpoint', 'unify_connection_api_username', 'unify_connection_api_password', 'unify_connection_campaign_id', 'unify_connection_shipping_id','unify_sublytics_connection_id', 'unify_connection_offer_model', 'unify_order_note', 'unify_response_crm_type_enable','unify_connection_crm_salt']))
					{
						$all_connection[$key][$k] = $val[0];
					}
				}
			}
		}

		return ['list' => $all_connection, 'total' => $connection->max_num_pages];
	}

	public static function delete_post($post_ID)
	{
		return \wp_trash_post($post_ID);
	}

	public static function update_post_status($post_id, $post_status)
	{
		$data_to_update = [
			'ID' => (int) $post_id,
			'post_status' => $post_status
		];

		return \wp_update_post($data_to_update);
	}

	public static function set_default_crm($post_id)
	{
		$setting_option = \get_option('woocommerce_codeclouds_unify_settings');
		$additional_setting_option = \get_option('woocommerce_codeclouds_unify_paypal_payment_settings');
		$setting_option['connection'] = $post_id;
		$additional_setting_option['connection'] = $post_id;
		\update_option('woocommerce_codeclouds_unify_paypal_payment_settings', $additional_setting_option);
		return \update_option('woocommerce_codeclouds_unify_settings', $setting_option);
	}

	public static function prepare_data($data, &$connection_post, &$connection_metas, &$error)
	{
		foreach ($data as $key => $value)
		{
			if (in_array($key, ['ID', 'post_title', 'post_type', 'post_status']))
			{
				if (empty($value) && $key != 'ID')
				{
					$error[] = $key;
					continue;
				}
				(!empty($value)) ? $connection_post[$key] = \esc_textarea($value) : '';
			}

			if (in_array($key, ['unify_connection_crm', 'unify_connection_endpoint', 'unify_connection_api_username', 'unify_connection_api_password', 'unify_connection_campaign_id', 'unify_connection_shipping_id','unify_sublytics_connection_id', 'unify_connection_offer_model','unify_order_note','unify_response_crm_type_enable']))
			{
				if (empty($value) && !in_array($key, ['unify_connection_endpoint', 'unify_connection_shipping_id','unify_sublytics_connection_id', 'unify_connection_offer_model', 'unify_connection_api_password','unify_order_note','unify_response_crm_type_enable']))
				{
					$error[] = $key;
					continue;
				}

				if (empty($value) && (($data['unify_connection_crm'] == 'limelight' && $key == 'unify_connection_endpoint') || ($data['unify_connection_crm'] != 'response' && $key == 'unify_connection_api_password')))
				{
					$error[] = $key;
					continue;
				}

				
				if ($key == 'unify_connection_api_password')
				{
					 ConfigEncryption::passwordEncrypt($key,$value,$connection_metas);

				}else if (in_array($key, ['unify_connection_endpoint','unify_connection_api_username','unify_connection_crm'])){

					 ConfigEncryption::metaEncrypt($key,$value,$connection_metas);

				}else{
					(!empty($value)) ? $connection_metas[$key] = \esc_textarea($value) : '';
					(in_array($key , ['unify_connection_offer_model','unify_connection_shipping_id','unify_sublytics_connection_id','unify_order_note','unify_response_crm_type_enable'])) ? $connection_metas[$key] = \esc_textarea($value) : '';
				}
			}
		}
	}
	
	public static function getArrayWithMeta()
	{
		$connections = [
			'' => [
				'title' => 'Please select a connection',
				'crm' => '',
				'billing_model' => '',
			]
		];
		$args = [
			'post_type' => array('unify_connections'),
			'post_status' => array('publish', 'active'),
		];
		$loop = new \WP_Query($args);
		foreach ($loop->posts as $post)
		{
			$connections[$post->ID] = [
				'title' => $post->post_title,
				'crm' => get_post_meta($post->ID,'unify_connection_crm',true),
				'billing_model' => get_post_meta($post->ID,'unify_connection_offer_model',true),
			];
		}
		return $connections;
	}

	/*
	* Limelight: Test auth credentials
	*/

	public static function validateLimelight($username,$password,$endpoint){

		$args = array(
			'body'        => [],
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic '.base64_encode($username . ":" . $password)
			],
			'cookies'     => [],
		);		
		$response = wp_remote_post( 'https://'.$endpoint."/api/v1/validate_credentials", $args );
		return $response;
	}

	/*
	* Response: Test auth credentials
	*/

	public static function validateResponse($username){

		$args = array(
			'body'        => [],
			'httpversion' => '1.0',
			'headers'     => [
				'Authorization' => 'ApiKey '.$username
			],
			'cookies'     => [],
		);		
		$response = wp_remote_get( 'https://openapi.responsecrm.com/api/v2/open/test-auth', $args );
		return $response;		

	}

	/*
	* Sublytics: Test auth credentials
	*/

	public static function validateSublytics($username,$password,$endpoint){
		$args = array(
			'body'        => [
				'user_id'=>$username,
				'user_password'=>$password,
			],
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/x-www-form-urlencoded, application/json',
			],
			'cookies'     => [],
		);		
		$response = wp_remote_post( 'https://'.$endpoint."/api/authenticate", $args );
		return $response;

	}


}
