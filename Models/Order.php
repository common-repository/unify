<?php

namespace CodeClouds\Unify\Model;

/**
 * Order model.
 * @package CodeClouds\Unify
 */
class Order
{
    /**
     * Postmeta's prefix.
     * @var String 
     */
    private static $prefix = '_codeclouds_unify_';

    /**
     * Get the connection.
     * @param int $order_id
     * @param String $key
     * @return mixed
     */
    public static function get_connection($order_id, $key)
    {
        return \get_post_meta($order_id, self::$prefix . $key, true);
    }
}
