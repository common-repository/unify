<?php

return [
	'DASHBOARD' =>[
		'FREE_TRIAL_REGISTRATION_SUCCESS'=>'Registration for free trial done successfully',
	],
	'NEW_CONNECTION' => 'A new connection “{$title} #{$pid}” has been created.',
	'EDIT_CONNECTION' => '“{$title} #{$pid}” has been updated.',
	'active_connection_deleted' => '“{$title} #{$pid}” has been deleted.',
	'connection_restore' => '“{$title} #{$pid}” has been restored.',
	'bulk_connection_restore' => '“{$count}” connections have been restored.',
	'FILES' => [
		'INVALID' => 'Please select a valid .csv file to upload.',
		'VALID' => 'Successfully uploaded.',
	],
	'COMMON' => [
		'ERROR' => 'Something went wrong!.',
		'PAYMENT_FAILED' => 'Payment Failed! Please make sure you have entered the correct information.',
		'PAYPAL_BUSINESS_ERROR' => 'Payment Failed! Client is ineligible for Headless Recurring Profile Creation.',
	],
	'PRODUCT_MAP' => [
		'SUCCESS' => 'Successfully set the mapping for the products.'
	],
	'SHIPPING_MAP' => [
		'SUCCESS' => 'Successfully set the mapping for the shipping.'
	],
	'VALIDATION' => [
		'CREATE_CONNECTION' => [
			'POST_TITLE' => 'Connection Name is a required field',
			'UNIFY_CONNECTION_CRM' => 'CRM is a required field',
			'UNIFY_CONNECTION_ENDPOINT' => 'API Endpoint is a required field',
			'UNIFY_CONNECTION_API_USERNAME' => 'API Username is a required field',
			'UNIFY_CONNECTION_API_PASSWORD' => 'API Password is a required field',
			'UNIFY_CONNECTION_CAMPAIGN_ID' => 'Campaign ID is a required field',
		],
		'REQUEST_UNIFY_PRO' => [
			'FULL_NAME' => 'Full Name is a required field.',
			'COMPANY_NAME' => 'Company Name is a required field.',
			'EMAIL_ADDRESS' => 'Email is a required field.',
			'EMAIL_ADDRESS_INVALID' => 'Please provide a valid email address.',
			'PHONE_NUMBER' => 'Phone is a required field.',
			'PHONE_NUMBER_INVALID' => 'Please provide a valid phone number.',
			'COMMENT' => 'Comment is a required field.',
		],
	],
	'REQUEST_UNIFY_PRO' => [
		'MAIL_SENT' => 'Your request has been sent successfully.',
		'CANCELLATION_MAIL_SENT' => 'Unify pro license cancellation request has been sent successfully.'
	],
	'SETTINGS' => [
		'SAVE' => 'Unify settings has been updated successfully.'
	],
	'CONNECTION' => [
		'UNDO_CONNECTION' => 'Revert to previous connection “{$title} #{$pid}” as active connection.',
		'CONNECTION_ACTIVATED' => 'Successfully set “{$title} #{$pid}” as active connection.',
		'CONNECTION_DELETED' => '“{$title} #{$pid}” has been deleted.',
		'BULK_CONNECTION_DELETED' => '“{$count}” connections have been deleted.',
		'UNDO_CONNECTION_DELETED' => '“{$title} #{$pid}” connections has been revert back to publish.',
		'UNDO_CONNECTION_BULK_DELETED' => '“{$count}” connections has been revert back to publish.',
	],
	'PRO' => [
		'Valid License' => 'Your have successfully upgraded to Unify pro.',
		'Invalid License' => 'Please provide a valid License key.',
	]
];
