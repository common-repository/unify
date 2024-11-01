<?php

namespace CodeClouds\Unify\Actions;

use CodeClouds\Unify\Model\Order as OrderModel;

/**
 * Order actions.
 * @package CodeClouds\Unify
 */
class Order
{
    /**
     * Add connection details into order view.
     * @param Object $order
     */
    public static function add_connection_details_to_view($order)
    {
        if (!empty(OrderModel::get_connection($order->get_id(), 'connection')))
        {
            include_once __DIR__ . '/../Templates/order.php';
        }
    }
}
