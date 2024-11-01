<?php

namespace CodeClouds\Unify\Service;

/**
 * Server request handler.
 * @package CodeClouds\Unify
 */
class Request
{
    /**
     * Get GET variable by $key or get all.
     * @param String $key
     * @return String
     */
    public static function get($key = null)
    {
        if($key == null)
        {
            return self::sanitize_array_field($_GET);
        }
        
        if(isset($_GET[$key]))
        {
            return self::sanitize_array_field($_GET[$key]);
        }
        
        return '';
    }
    
    /**
     * Get POST variable by $key or get all.
     * @param String $key
     * @return String/array
     */
    public static function post($key = null)
    {
        if($key == null)
        {
            return self::sanitize_array_field($_POST);
        }
        
        if(isset($_POST[$key]))
        {
            return self::sanitize_array_field($_POST[$key]);
        }
    }
    
    /**
     * Get REQUEST variable by $key or get all.
     * @param String $key
     * @return String/array
     */
    public static function any($key = null)
    {
        if($key == null)
        {
            return self::sanitize_array_field($_REQUEST);
        }
        
        if(isset($_REQUEST[$key]))
        {
            return self::sanitize_array_field($_REQUEST[$key]);
        }
    }
    
    

    /**
     * Get POST variable by $key.
     * @param String $key
     * @return String
     */
    public static function getPost($key)
    {
        if($_POST[$key])
        {
            return self::sanitize_array_field($_POST[$key]);
        }
    }
    
    /**
     * Get all POST variables as an array.
     * @return array
     */
    public static function getPostArray()
    {
        return self::sanitize_array_field($_POST);
    }
    
    /**
	 * Set POST variable.
	 * @param String $key
	 * @param String/array $value
	 */
	public static function setPost($key, $value)
	{
		$_POST[$key] = $value;
	}

    /**
     * sanitize array/string
     */
    public static function sanitize_array_field($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $array[$key] = self::sanitize_array_field($value);
                } else {
                    $array[$key] = sanitize_text_field($value);
                }
            }
        }

        return $array;
    } 
}
