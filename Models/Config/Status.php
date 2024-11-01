<?php

namespace CodeClouds\Unify\Model\Config;

/**
 * Description of Status
 *
 * @author cispl-admin
 */
class Status 
{
    /**
     * Status array.
     * @var array 
     */
    private static $status = [
        '' => '--Select--',
        1 => 'Yes',
        0 => 'No',
    ];

    /**
     * Get array list of status.
     * @return array
     */
    public static function getArray() {
        return self::$status;
    }

    /**
     * Get the status name.
     * @param String $key
     * @return String
     */
    public static function get($key) {
        if (!empty($key)) {
            return self::$status[$key];
        }

        return '';
    }

}
