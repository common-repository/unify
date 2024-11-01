<?php

namespace CodeClouds\Unify\Model\Config;

/**
 * Connection model.
 * @package CodeClouds\Unify
 */
class Connection
{
    /**
     * Connections array.
     * @var array 
     */
    private static $connections = [
//        ''           => '--Select--',
        'limelight'  => 'sticky.io (Formerly LimeLight CRM)',
        'konnektive' => 'Konnektive',
        'response' => 'Response',
        'sublytics' => 'Sublytics',
    ];

    /**
     * Get array list of connections.
     * @return array
     */
    public static function getArray()
    {
        return self::$connections;
    }

    /**
     * Get the connection name.
     * @param String $key
     * @return String
     */
    public static function get($key)
    {
        if(!empty($key))
        {
            return self::$connections[$key];
        }
        
        return '';
    }
}
