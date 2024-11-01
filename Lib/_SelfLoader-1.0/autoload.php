<?php

/**
 * @author CodeClouds <sales@codeclouds.com>
 * @final
 * @package _Self
 */

require_once __DIR__ . "/class-loader/Stack.php";

spl_autoload_register(function ($class)
{
    if(file_exists(_Self\ClassLoader\Stack::run($class)))
    	require_once _Self\ClassLoader\Stack::run($class);
});
