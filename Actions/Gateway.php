<?php

namespace CodeClouds\Unify\Actions;

/**
 * Gateway actions.
 * @package CodeClouds\Unify
 */
class Gateway
{
    /**
     * Initialize payment gateway.
     */
    public static function init()
    {
        return new \CodeClouds\Unify\Models\Unify_Payment();
        return new \CodeClouds\Unify\Models\Unify_Paypal_Payment();
    }

    /**
     * Add payment gateway.
     * @param array $methods
     */
    public static function add_unify_gateway_class($methods)
    {
        $methods[] = 'CodeClouds\Unify\Models\Unify_Payment';
        $methods[] = 'CodeClouds\Unify\Models\Unify_Paypal_Payment';
        return $methods;
    }

    /**
	 * Declares WooCommerce HPOS compatibility.
	 *
	 * @return void
	 */
	public static function woocommerce_hpos_compatible() {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', '../unify.php', true );
		}
	}
}
