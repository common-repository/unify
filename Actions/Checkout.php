<?php

namespace CodeClouds\Unify\Actions;

use \CodeClouds\Unify\Service\Validation\Card_Validation as Validation;
use \CodeClouds\Unify\Service\Request;
use \CodeClouds\Unify\Model\Checkout as Checkout_Model;


/**
 * Checkout actions.
 * @package CodeClouds\Unify
 */
class Checkout
{

    /**
     * CC validation before payment.
     */
    public static function process_unify_payment()
    {
        if (Request::post('payment_method') != 'codeclouds_unify')
        {
            return;
        }

        $validator = Validation::validate([
            'cc_number' => Request::post('cc_number'),
            'cc_expiry' => Request::post('cc_expiry'),
            'cc_cvc'    => Request::post('cc_cvc'),
        ]);

        if ($validator->failed())
        {
            $validator->print_messages();
        }
    }
    
    /**
     * Custom validation for checkout form.
     */
    public static function checkout_validation($fields)
    {
        $fields['billing']['billing_state']['required']   = true;
        $fields['shipping']['shipping_state']['required'] = true;

        return $fields;
    }
    
    /**
     * Custom validation for checkout form.
     */
    public static function checkout_js_validation()
    {
        if(is_checkout())
        {
            wp_register_script('checkoutjs', plugins_url('/../assets/js/checkout.js', __FILE__),'',UNIFY_JS_VERSION);
            wp_enqueue_script( 'checkoutjs' );


        }
    }
	
	/*
	 * collecting affiliate params on template load.
	 */
	public static function collect_affiliate_param()
    {
        
		if (!session_id())
		{
			session_start();
		};
		
		$params = ['click_id', 'AFID', 'AFFID', 'SID', 'C1', 'C2', 'C3', 'AID', 'affId', 'sourceValue1', 'sourceValue2', 'sourceValue3', 'sourceValue4', 'sourceValue5', 'AffiliateID', 'SubAffiliateID','utm_source','utm_medium','utm_campaign','utm_term','utm_content','OPT','AffiliateID','SubAffiliateID','SubAffiliateID2','SubAffiliateID3','SubAffiliateID4','SubAffiliateID5','device_category', 'c1', 'c2', 'c3'];
		
        $_SESSION['affiliate_param'] = Request::get();
		
    }


    


    
}
