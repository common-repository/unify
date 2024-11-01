<?php

namespace CodeClouds\Unify\Service\Form;

/**
 * Generate TextBox for post type MetaBox.
 * @package CodeClouds\Unify
 */
class Input
{
    /**
     * Input style array
     * @var array
     */
    private static $style = [
        'text' => 'width: 100%; height: 35px; margin-bottom: 10px;',
        'password' => 'width: 100%; height: 35px; margin-bottom: 10px;'
    ];

    /**
     * Get input field.
     * @param int $meta_id
     * @param array $meta_box
     * @return string
     */
    public static function get($meta_id, $meta_box, $meta_value)
    {
        wp_nonce_field(basename(__FILE__), 'unify_connection_fields');

        $required    = !empty($meta_box['required']) ? 'required' : '';

        $outline = '<div id="unify_connection_' . $meta_box['name'] . '_area">';
        $outline .= '<label class="unify_connection-' . $meta_box['name'] . '-title" for="unify_connection_' . $meta_box['name'] . '" style="font-size: 16px;">' . esc_html__($meta_box['title'], 'text-domain') . '</label>';

        $outline .= '<input type="' . $meta_box['type'] . '" name="unify_connection_' . $meta_box['name'] . '" id="unify_connection_' . $meta_box['name'] . '" class="unify_connection-' . $meta_box['name'] . '-val" value="' . esc_attr($meta_value) . '" style=" ' . self::$style[$meta_box['type']] . ' " ' . $required . ' />';
        $outline .= '</div>';
        
        return $outline;
    }
}
