<?php

namespace CodeClouds\Unify\Service\Form;

/**
 * Generate SelectBox handler for post type MetaBox.
 * @package CodeClouds\Unify
 */
class Select
{
    /**
     * Get select box.
     * @param int $meta_id
     * @param array $meta_box
     * @return string
     */
    public static function get($meta_id, $meta_box, $meta_value)
    {
        wp_nonce_field( basename( __FILE__ ), 'unify_connection_fields' );

        $required    = ($meta_box['required']) ? 'required' : '';

        $outline = '<div id="unify_connection_' . $meta_box['name'] . '_area">';
        $outline .= '<label class="unify_connection-' . $meta_box['name'] .'-title" for="unify_connection_' . $meta_box['name'] .'" style="font-size: 16px;">' . esc_html__($meta_box['title'], 'text-domain') . '</label>';
        $outline .= '<select name="unify_connection_' . $meta_box['name'] .'" id="unify_connection_' . $meta_box['name'] .'" class="unify_connection-' . $meta_box['name'] .'-val" style="width: 100%; height: 35px; margin-bottom: 10px;" ' . $required . '>';
        
        $options = $meta_box['source']::getArray();

        foreach ($options as $key=>$value)
        {
            $selected = ($meta_value == $key) ? 'selected' : '';
            $outline .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }

        $outline .= '</select>';
        $outline .= '</div>';

        return $outline;
    }
}
