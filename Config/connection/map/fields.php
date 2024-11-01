<?php

return [
    [
        'id' => 'codeclouds_unify_connection',
        'label' => 'Connection\'s Product ID',
        'description' => 'Enter your connection\'s product ID here.',
        'payload' => 'connection_product_id'
    ],
	[
        'id' => 'codeclouds_unify_shipping',
        'label' => 'Shipping ID',
        'description' => 'Enter your connection\'s shipping ID here. Only for LimeLight.',
        'payload' => 'connection_shipping_id',
		'crm' => 'limelight'
    ],
    [
        'id' => 'codeclouds_unify_offer_id',
        'label' => 'Offer ID',
        'description' => 'Enter your connection\'s offer ID here. Only for LimeLight.',
        'payload' => 'connection_offer_id',
		'crm' => 'limelight'
    ],
    [
        'id' => 'codeclouds_unify_billing_model_id',
        'label' => 'Billing Model ID',
        'description' => 'Enter your connection\'s billing model ID here. Only for LimeLight.',
        'payload' => 'connection_billing_model_id',
		'crm' => 'limelight'
    ],
     [
        'id' => 'codeclouds_unify_group_id',
        'label' => 'Group ID',
        'description' => 'Enter your connection\'s Group ID here. Only for Response.',
        'payload' => 'connection_group_id',
		'crm' => 'response'
    ]

];
