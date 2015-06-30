<?php

namespace Utility\Object;

use Utility\Exception\NonStaticCallException;

/**
 * Class Dictionary
 *
 * @category Object
 * @package  Utility\Object
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  https://github.com/misantron/php-utils/blob/master/LICENSE (MIT License)
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
abstract class Dictionary
{
    /** @var array */
    protected static $titleMapping = [];

    /** @var array|null */
    protected static $cache;

    /**
     * @throws NonStaticCallException
     */
    function __construct()
    {
        throw new NonStaticCallException('Non static call is disabled.');
    }

    /**
     * @return array
     */
    final public static function getKeys()
    {
        if(static::$cache === null){
            $class = new \ReflectionClass(get_called_class());
            static::$cache = array_values($class->getConstants());
        }
        return static::$cache;
    }

    /**
     * @return array
     */
    final public static function getTitles()
    {
        return static::$titleMapping;
    }

    /**
     * @param string|int $key
     * @return string
     */
    public static function getTitle($key)
    {
        if(!isset(static::$titleMapping[$key])){
            throw new \InvalidArgumentException('Element with key "' . (string)$key . '" not found.');
        }
        return static::$titleMapping[$key];
    }
}