<?php

namespace CodeClouds\Unify\Actions;

use \CodeClouds\Unify\Service\Request;

/**
 * Custom post type to store Connection(s).
 * @package CodeClouds\Unify
 */
class Block
{

    /**
     * Register Custom Post Type.
     */
    public static function unify_connections()
    {
        $labels = [
            'name'                  => 'Connections',
            'singular_name'         => 'Connectivity',
            'menu_name'             => 'Unify',
            'name_admin_bar'        => 'Unify',
            'archives'              => 'Item Archives',
            'attributes'            => 'Item Attributes',
            'parent_item_colon'     => 'Parent Connection:',
            'all_items'             => 'Connectivity',
            'add_new_item'          => 'New Connection',
            'add_new'               => 'New Connection',
            'new_item'              => 'New Connection',
            'edit_item'             => 'Edit Connection',
            'update_item'           => 'Update Connection',
            'view_item'             => 'View Connection',
            'view_items'            => 'View Connections',
            'search_items'          => 'Search Connection',
            'not_found'             => 'Not found',
            'not_found_in_trash'    => 'Not found in Trash',
            'featured_image'        => 'Featured Image',
            'set_featured_image'    => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image'    => 'Use as featured image',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list'            => 'Items list',
            'items_list_navigation' => 'Items list navigation',
            'filter_items_list'     => 'Filter items list',
        ];
        $supports = [
            'title',
        ];
        $args = [
            'label'               => 'Connectivity',
            'description'         => 'Post Type Description',
            'labels'              => $labels,
            'supports'            => $supports,
            'hierarchical'        => false,
            'public'              => false,
			'show_ui'             => FALSE,
            'show_in_menu'        => FALSE,
            'menu_position'       => 2,
            'menu_icon'           => plugins_url('/../assets/images/unify-white-icon.svg',__FILE__),
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
        ];

        register_post_type('unify_connections', $args);
    }

    /**
     * Add MetaBox to connection to store API credentials.
     */
    public static function add_unify_connections_metaboxes()
    {
        add_meta_box(
            'unify_connection_api',
            'API Credentials',
            ['CodeClouds\Unify\Actions\MetaBox', 'add_api_fields'],
            'unify_connections',
            'normal',
            'high'
        );

        add_meta_box(
            'unify_connection_campaign',
            'Campaign',
            ['CodeClouds\Unify\Actions\MetaBox', 'add_campaign_fields'],
            'unify_connections',
            'normal',
            'high'
        );
		
		add_meta_box(
            'unify_connection_offer_model',
            'Offer Model',
            ['CodeClouds\Unify\Actions\MetaBox', 'add_offer_model_fields'],
            'unify_connections',
            'normal',
            'high'
        );
    }

    /**
     * Save API credentials(MetaBox).
     * @return $post_id
     */
    public static function save_unify_connections_metaboxes()
    {
        /**
         * Return if the user doesn't have edit permissions.
         */
        if (!current_user_can('edit_post', Request::post('post_ID'))) {
            return Request::post('post_ID');
        }

        /**
         * Now that we're authenticated, time to save the data.
         * This sanitizes the data from the field and saves it into an array $connection_metas.
         */
        $salt             = \Codeclouds\Unify\Model\Protection\Salt::generate();
        $connection_metas = [
            'unify_connection_salt'         => $salt,
            'unify_connection_crm'          => \esc_textarea(Request::post('unify_connection_crm')),
            'unify_connection_api_username' => \esc_textarea(Request::post('unify_connection_api_username')),
            'unify_connection_api_password' => \Codeclouds\Unify\Model\Protection\Encryption::make(Request::post('unify_connection_api_password'), $salt),
            'unify_connection_campaign_id'  => \esc_textarea(Request::post('unify_connection_campaign_id')),
            'unify_connection_shipping_id'  => \esc_textarea(Request::post('unify_connection_shipping_id')),
            'unify_connection_endpoint'     => \esc_textarea(Request::post('unify_connection_endpoint')),
			'unify_connection_offer_model'  => \esc_textarea(Request::post('unify_connection_offer_model')),
        ];

        /**
         * Cycle through the $events_meta array.
         * Note, in this example we just have one item, but this is helpful if you have multiple.
         */
        foreach ($connection_metas as $key => $value) {
            if (
                (gettype(\get_post_meta(Request::post('post_ID'), $key, true))=='array' && count(\get_post_meta(Request::post('post_ID'), $key, true)) > 0) ||
                (gettype(\get_post_meta(Request::post('post_ID'), $key, true))=='string' && !empty(\get_post_meta(Request::post('post_ID'), $key, true)))
                )
            {
                /**
                 * If the custom field already has a value, update it.
                 */
                \update_post_meta(Request::post('post_ID'), $key, $value);
            } else {
                /**
                 * If the custom field doesn't have a value, add it.
                 */
                \add_post_meta(Request::post('post_ID'), $key, $value);
            }

            if (!$value) {
                /**
                 * Delete the meta key if there's no value
                 */
                \delete_post_meta(Request::post('post_ID'), $key);
            }
        }
    }
}
