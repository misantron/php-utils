<?php

namespace Utils\Helper;

use Utils\Exception\StaticClassException;

/**
 * Class ArrayHelper
 *
 * @package Utils\Helper
 */
class ArrayHelper
{
    const
        TYPE_INTEGER = 'integer',
        TYPE_STRING = 'string'
    ;

    /**
     * @throws StaticClassException
     */
    final public function __construct()
    {
        throw new StaticClassException();
    }

    /**
     * Get selected value from array
     *
     * @param array $arr
     * @param integer|string $key
     * @param integer|string|null $default
     *
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public static function get($arr, $key, $default = null)
    {
        $key = !is_array($key) ?: array($key);
        foreach($key as $k){
            if(!is_array($arr) || !isset($arr[$k])){
                if(func_num_args() < 3){
                    throw new \InvalidArgumentException('Element with key "' . $k . '" not found.');
                }
                return $default;
            }
            $arr = $arr[$k];
        }
        return $arr;
    }

    /**
     * Extract selected column from assoc array
     *
     * @param array $arr
     * @param integer|string $columnKey
     * @param integer|string|null $indexKey
     * @return array
     */
    public static function extractColumn($arr, $columnKey, $indexKey = null)
    {
        if(function_exists('array_column')){
            return array_column($arr, $columnKey, $indexKey);
        }
        $result = array();
        foreach ($arr as $key => $val) {
            if(!isset($val[$columnKey])){
                break;
            }
            $result[$indexKey ? $val[$indexKey] : $key] = $val[$columnKey];
        }
        return $result;
    }

    /**
     * Add prefix and postfix to array keys
     *
     * @param array $arr
     * @param integer|string $prefix
     * @param integer|string $postfix
     * @return array
     */
    public static function wrapKeys($arr, $prefix = '', $postfix = '')
    {
        $result = array();
        foreach ($arr as $key => $val) {
            $result[$prefix . $key . $postfix] = $val;
        }
        return $result;
    }

    /**
     * Add prefix and postfix to array values
     *
     * @param array $arr
     * @param integer|string $prefix
     * @param integer|string $postfix
     * @return array
     */
    public static function wrapValues($arr, $prefix = '', $postfix = '')
    {
        $result = array();
        foreach ($arr as $key => $val) {
            $result[$key] = $prefix . $val . $postfix;
        }
        return $result;
    }

    /**
     * Extract only integer elements from array
     *
     * @param array $arr
     * @param string $type
     * @return array
     */
    public static function filterIntegerValues($arr, $type = self::TYPE_INTEGER)
    {
        switch($type){
            case self::TYPE_INTEGER:
                $callback = 'is_int';
                break;
            case self::TYPE_STRING:
                $callback = 'is_string';
                break;
            default:
                $callback = null;
        }
        return $callback ? array_filter($arr, $callback) : array_filter($arr);
    }

    /**
     * Insert element before selected key
     *
     * @param array $arr
     * @param integer|string $beforeKey
     * @param array $element
     * @return array
     */
    public static function insertBefore($arr, $beforeKey, $element)
    {
        $element = is_array($element) ?: array($element);
        $offset = self::searchKey($arr, $beforeKey);
        return array_slice($arr, 0, $offset, true) + $element + array_slice($arr, $offset, sizeof($arr), true);
    }

    public static function insertAfter()
    {

    }

    /**
     * @param array $arr
     * @param integer|string $key
     * @return mixed
     */
    public static function searchKey($arr, $key)
    {
        return array_search($key, array_keys($arr), true);
    }
}