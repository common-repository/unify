<?php

namespace CodeClouds\Unify\Abstracts;

/**
 * Abstract Validation class.
 * @package CodeClouds\Unify
 */
abstract class Validation
{
    /**
     * Store error messages.
     * @var array 
     */
    protected $fields_status = [];
    
    /**
     * Print error messages.
     */
    public abstract function print_messages();

        /**
     * Validate fields.
     * @param array $fields
     * @return \self
     */
    public static function validate($fields)
    {
        $class = get_called_class();
        $obj   = new $class();
        
        foreach ($fields as $method => $field)
        {
            $obj->$method($field);
        }
        
        return $obj;
    }
    
    /**
     * Check validation is failed or not.
     * @return boolean
     */
    public function failed()
    {
        if (count($this->fields_status) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
