<?php

namespace Utility\Object;

use Utility\Exception\NonStaticCallException;

/**
 * Class AbstractDictionary
 *
 * @category Object
 * @package  Utility\Object
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  https://github.com/misantron/php-utils/blob/master/LICENSE (MIT License)
 * @link     https://github.com/misantron/php-utils/blob/master/src/Object/Dictionary.php
 */
abstract class AbstractDictionary
{
    /** @var array */
    protected static $titleMapping = [];
    /** @var array */
    protected static $cache = [];

    /**
     * @throws NonStaticCallException
     */
    public function __construct()
    {
        throw new NonStaticCallException('Non static call is disabled.');
    }

    /**
     * @return array
     */
    final public static function getKeys()
    {
        $class = new \ReflectionClass(get_called_class());
        $hash = crc32($class);
        if (!isset(static::$cache[$hash])) {
            static::$cache[$hash] = array_values($class->getConstants());
        }
        return static::$cache[$hash];
    }

    /**
     * @return array
     */
    public static function getTitles()
    {
        return static::$titleMapping;
    }

    /**
     * @param int $key
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