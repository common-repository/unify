<?php

namespace CodeClouds\Unify\Data_Sources;

/**
 * Connection handler.
 * @package CodeClouds\Unify
 */
class Connection_Handler
{
    /**
     * Instance of the Connection_Handler class.
     * @var Connection_Handler
     */
    private static $_instance = null;

    /**
     * Store an instance of the a CRM handler.
     */
    private $connection_instance = null;

    /**
     * Create instance of Connection_Handler class using singleton pattern.
     */
    private static function make_instance()
    {
        if (self::$_instance == null)
        {
            self::$_instance = new self;
        }
    }

    /**
     * Create an instance if $connection_instance is empty.
     * @return Object An instance of Connection_Handler.
     */
    public static function call($args)
    {
        self::make_instance();
        if (self::$_instance->connection_instance == null)
        {
            $class                                = '\CodeClouds\\Unify\\Data_Sources\\Handler\\' . ucfirst($args['config']['connection']) . '_Handler';
            self::$_instance->connection_instance = new $class($args);
        }
        return self::$_instance;
    }

    /**
     * Call handler for payment process.
     * @return array
     */
    public function order()
    {
        return $this->connection_instance->make_order();
    }

    

}
