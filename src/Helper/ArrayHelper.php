<?php

namespace Utility\Helper;

/**
 * Class ArrayHelper
 * @package Utility\Helper
 */
class ArrayHelper
{
    const
        TYPE_INTEGER = 'integer',
        TYPE_STRING = 'string'
    ;

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
        $value = null;
        if(is_array($arr) && isset($arr[$key])){
            $value = $arr[$key];
        } else {
            if(func_num_args() < 3){
                throw new \InvalidArgumentException('Element with key "' . $key . '" not found.');
            }
            $value = $default;
        }
        return $value;
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
        if(!empty($arr)){
            foreach ($arr as $key => $val) {
                if(!isset($val[$columnKey])){
                    break;
                }
                $result[$indexKey ? $val[$indexKey] : $key] = $val[$columnKey];
            }
        }
        return $result;
    }

    /**
     * Add prefix and postfix to array keys
     *
     * @param array $arr
     * @param string $prefix
     * @param string $postfix
     * @return array
     */
    public static function wrapKeys($arr, $prefix = '', $postfix = '')
    {
        $result = array();
        $prefix = (string)$prefix;
        $postfix = (string)$postfix;
        foreach ($arr as $key => $val) {
            $result[$prefix . (string)$key . $postfix] = $val;
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
        $prefix = (string)$prefix;
        $postfix = (string)$postfix;
        foreach ($arr as $key => $val) {
            $result[$key] = $prefix . (string)$val . $postfix;
        }
        return $result;
    }

    /**
     * Extract elements from array by filter
     *
     * @param array $arr
     * @param string $type
     * @return array
     */
    public static function filterValues($arr, $type = self::TYPE_INTEGER)
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
     * Search selected key in array and return the offset
     *
     * @param array $arr
     * @param integer|string $key
     * @return int|bool
     */
    public static function searchKey($arr, $key)
    {
        $keys = array_keys($arr);
        return array_search($key, $keys, true);
    }

    /**
     * Insert element before selected key
     *
     * @param array $arr
     * @param integer|string $needleKey
     * @param array $element
     * @param integer|string $withKey
     * @return array
     */
    public static function insertBefore($arr, $needleKey, $element, $withKey = null)
    {
        if(!is_array($element)){
            $element = array(($withKey ? $withKey : 0) => $element);
        } elseif($withKey) {
            $element = array($withKey => reset($element));
        }
        $offset = self::searchKey($arr, $needleKey);
        if($offset === false){
            throw new \InvalidArgumentException('Element with key "' . $needleKey . '" not found.');
        }
        return array_merge(
            array_slice($arr, 0, $offset, true),
            $element,
            array_slice($arr, $offset, sizeof($arr), true)
        );
    }

    /**
     * Insert element after selected key
     *
     * @param array $arr
     * @param integer|string $needleKey
     * @param array $element
     * @param integer|string $withKey
     * @return array
     */
    public static function insertAfter($arr, $needleKey, $element, $withKey = null)
    {
        if(!is_array($element)){
            $element = array(($withKey ? $withKey : 0) => $element);
        } elseif($withKey) {
            $element = array($withKey => reset($element));
        }
        $arraySize = sizeof($arr);
        $offset = self::searchKey($arr, $needleKey);
        $offset = $offset === false ? $arraySize : $offset + 1;
        return array_merge(
            array_slice($arr, 0, $offset, true),
            $element,
            array_slice($arr, $offset, $arraySize, true)
        );
    }

    /**
     * Merge two arrays recursive
     *
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function mergeRecursive($arr1, $arr2)
    {
        $res = $arr1 + $arr2;
        foreach (array_intersect_key($arr1, $arr2) as $key => $val) {
            if (is_array($val) && is_array($arr2[$key])) {
                $res[$key] = self::mergeRecursive($val, $arr2[$key]);
            }
        }
        return $res;
    }

    /**
     * @param array $arr
     * @return array
     */
    public static function flatten($arr)
    {
        $result = array();
        array_walk_recursive($arr, function($a) use (& $result) { $result[] = $a; });
        return $result;
    }
}