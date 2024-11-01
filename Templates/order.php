<?php
use CodeClouds\Unify\Model\Order as OrderModel;
?>

<div style="margin-top: 10px; display: inline-block;">
    <h3>Payment Information</h3>
    <?php
        $connection_type = CodeClouds\Unify\Model\Config\Connection::get(OrderModel::get_connection($order->get_id(), 'connection'));
    ?>
    <p><strong><?php echo esc_html(__('Connection')) ?>:</strong> <?php echo esc_html($connection_type); ?></p>

    <?php
    if (!empty(OrderModel::get_connection($order->get_id(), 'connection_id')))
    {
        $connection = \CodeClouds\Unify\Model\Connection::get_post_meta(OrderModel::get_connection($order->get_id(), 'connection_id'));

        if (!empty($connection['unify_connection_campaign_id'][0]))
        {
            ?>
            <p><strong><?php echo esc_html(__('Campaign ID')) ?>:</strong> <?php echo esc_html($connection['unify_connection_campaign_id'][0]) ?></p>
            <?php
        }
        $connection_type_index = !empty($connection)?$connection['unify_connection_crm'][0]:'';

        if (!empty($connection_type_index === 'limelight'))
        {
            $Shipping_id = get_post_meta($order->get_id(), "_codeclouds_unify_shipping_id");
            ?>
            <p><strong><?php echo esc_html(__('Shipping ID')) ?>:</strong> <?php echo esc_html($Shipping_id[0]);?></p>
            <?php
        }else{
            if(!empty($connection['unify_connection_shipping_id'][0])){
            ?>
            <p><strong><?php echo esc_html(__('Shipping ID')) ?>:</strong> <?php echo esc_html($connection['unify_connection_shipping_id'][0]) ?></p>
        <?php 
            }
        }
    }
    ?>
</div>