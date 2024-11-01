<?php

namespace CodeClouds\Unify\Service\Mapping;

/**
 * Product option fields
 *
 * @author cispl-admin
 */
class Fields
{
    /**
     * 
     * @return array
     */
    public static function get()
    {
        return require __DIR__ . '/../../Config/connection/map/fields.php';
    }
}
