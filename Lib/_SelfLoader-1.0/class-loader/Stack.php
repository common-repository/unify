<?php

namespace _Self\ClassLoader;

/**
 * @author CodeClouds <sales@codeclouds.com>
 * @final
 * @package _Self
 */
final class Stack
{
    private static $classes = [];

    public static function run($class)
    {
        self::loader();

        if(isset(self::$classes[$class]))
            return __DIR__ . '/' . self::$classes[$class];
    }

    private static function loader()
    {
        self::$classes = json_decode(
                file_get_contents(__DIR__ . "/../bootstrap.json"), true
        );
    }

}