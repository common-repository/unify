<?php

namespace CodeClouds\Unify\Actions;

/**
 * MetaBox actions.
 * @package CodeClouds\Unify
 */
class MetaBox
{
    /**
     * Add API fields to connection post type.
     * @param array $meta
     */
    public static function add_api_fields($meta)
    {
        self::add_fields($meta, self::load_metas('api'));
    }

    /**
     * Add campaign fields to connection post type.
     * @param array $meta
     */
    public static function add_campaign_fields($meta)
    {
        self::add_fields($meta, self::load_metas('campaign'));
    }

    /**
     * Add campaign fields to connection post type.
     * @param array $meta
     */
    public static function add_offer_model_fields($meta)
    {
        self::add_fields($meta, self::load_metas('offer_model'));
    }

    /**
     * Add API fields to connection post type.
     * @param array $meta Default MetaBoxs of WordPress.
     * @param array $meta_boxs Custom MetaBox(s) for connection post type.
     * @return String
     */
    public static function add_fields($meta, $meta_boxs)
    {
        $outline = '';
		
        foreach ($meta_boxs as $meta_box)
        {
            $class = '\\CodeClouds\\Unify\\Service\\Form\\' . ucfirst($meta_box['field']);
            $meta_value = get_post_meta($meta->ID, 'unify_connection_' . $meta_box['name'], true);

            if(!empty($meta_box['type']) && $meta_box['type'] == 'password' && !empty($meta_value))
            {
                $salt = get_post_meta($meta->ID, 'unify_connection_salt', true);
                $meta_value = \stripslashes(\Codeclouds\Unify\Model\Protection\Decryption::make($meta_value, $salt));
            }

            $outline .= $class::get($meta->ID, $meta_box, $meta_value);
        }

        echo esc_html($outline);
    }

    /**
     * Loads fields from configuration.
     * @param String $config
     * @return array
     */
    private static function load_metas($config)
    {
        return require_once __DIR__ . '/../Config/connection/meta_fields/' . $config . '.php';
    }

}
