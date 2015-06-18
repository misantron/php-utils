<?php

namespace Utility;

use Utility\Exception\InvalidArgumentException;

/**
 * Class UArray
 *
 * @category Utility
 * @package  Utility
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class UArray extends UAbstract
{
    const
        TYPE_INTEGER = 'integer',
        TYPE_STRING = 'string'
    ;

    /**
     * Get selected value from array by the key.
     *
     * @param array       $arr
     * @param string      $key
     * @param string|null $default
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public static function get(&$arr, $key, $default = null)
    {
        $value = null;
        if (is_array($arr) && isset($arr[$key])) {
            $value = $arr[$key];
        } else {
            if (func_num_args() < 3) {
                throw new InvalidArgumentException('Element with key "' . $key . '" not found.');
            }
            $value = $default;
        }
        return $value;
    }

    /**
     * Extract selected column from assoc array.
     *
     * @param array  $arr
     * @param string $columnKey
     * @param bool   $preserveKeys
     *
     * @return array
     */
    public static function extractColumn(&$arr, $columnKey, $preserveKeys = false)
    {
        $result = [];
        foreach ($arr as $key => $val) {
            if (!isset($val[$columnKey])) {
                break;
            }
            $result[$key] = $val[$columnKey];
        }
        return $preserveKeys ? $result : array_values($result);
    }

    /**
     * Add prefix and postfix to array keys.
     *
     * @param array       $arr
     * @param string|null $prefix
     * @param string|null $postfix
     *
     * @return array
     */
    public static function wrapKeys(&$arr, $prefix = null, $postfix = null)
    {
        $result = [];
        $prefix = (string)$prefix;
        $postfix = (string)$postfix;
        foreach ($arr as $key => $val) {
            $result[$prefix . (string)$key . $postfix] = $val;
        }
        return $result;
    }

    /**
     * Add prefix and postfix to array values.
     *
     * @param array       $arr
     * @param string|null $prefix
     * @param string|null $postfix
     *
     * @return array
     */
    public static function wrapValues(&$arr, $prefix = null, $postfix = null)
    {
        $result = [];
        array_walk($arr, function($item, $key) use (&$result, $prefix, $postfix) {
            $result[$key] = (string)$prefix . (string)$item . (string)$postfix;
        });
        return $result;
    }

    /**
     * Extract elements from array by filter.
     *
     * @param array  $arr
     * @param string $type
     * @param bool   $preserveKeys
     *
     * @return array
     */
    public static function filterValues(&$arr, $type = self::TYPE_INTEGER, $preserveKeys = false)
    {
        switch($type) {
            case self::TYPE_INTEGER:
                $callback = 'is_int';
                break;
            case self::TYPE_STRING:
                $callback = 'is_string';
                break;
            default:
                $callback = null;
                break;
        }
        if ($callback) {
            $filtered = array_filter($arr, $callback);
            $result = $preserveKeys ? $filtered : array_values($filtered);
        } else {
            $result = array_filter($arr);
        }
        return $result;
    }

    /**
     * Search selected key in array and return the offset.
     *
     * @param array  $arr
     * @param string $key
     *
     * @return int|bool
     */
    public static function searchKey(&$arr, $key)
    {
        $keys = array_keys($arr);
        return array_search($key, $keys, true);
    }

    /**
     * Insert element before selected key.
     *
     * @param array        $arr
     * @param string       $needleKey
     * @param array|string $element
     * @param string|null  $withKey
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public static function insertBefore(&$arr, $needleKey, $element, $withKey = null)
    {
        if (!is_array($element)) {
            $element = [($withKey ? $withKey : 0) => $element];
        } elseif ($withKey) {
            $element = [$withKey => reset($element)];
        }
        $offset = static::searchKey($arr, $needleKey);
        if ($offset === false) {
            throw new InvalidArgumentException('Element with key "' . $needleKey . '" not found.');
        }
        return array_merge(
            array_slice($arr, 0, $offset, true),
            $element,
            array_slice($arr, $offset, sizeof($arr), true)
        );
    }

    /**
     * Insert element after selected key.
     *
     * @param array        $arr
     * @param string       $needleKey
     * @param array|string $element
     * @param string|null  $withKey
     *
     * @return array
     */
    public static function insertAfter(&$arr, $needleKey, $element, $withKey = null)
    {
        if (!is_array($element)) {
            $element = [($withKey ? $withKey : 0) => $element];
        } elseif($withKey) {
            $element = [$withKey => reset($element)];
        }
        $size = sizeof($arr);
        $offset = static::searchKey($arr, $needleKey);
        $offset = $offset === false ? $size : $offset + 1;
        return array_merge(
            array_slice($arr, 0, $offset, true),
            $element,
            array_slice($arr, $offset, $size, true)
        );
    }

    /**
     * Merge several arrays recursive.
     * Basically use for two arrays.
     *
     * @param array $arr1
     * @param array $arr2
     *
     * @return array
     */
    public static function mergeRecursive($arr1, $arr2)
    {
        $args = func_get_args();
        $result = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_integer($k)) {
                    if (isset($result[$k])) {
                        $result[] = $v;
                    } else {
                        $result[$k] = $v;
                    }
                } elseif (is_array($v) && isset($result[$k]) && is_array($result[$k])) {
                    $result[$k] = static::mergeRecursive($result[$k], $v);
                } else {
                    $result[$k] = $v;
                }
            }
        }
        return $result;
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function flatten(&$arr)
    {
        $result = [];
        array_walk_recursive($arr, function ($a) use (&$result) {
            $result[] = $a;
        });
        return $result;
    }

    /**
     * Build a map from multi-dimensional array.
     *
     * @param array       $arr
     * @param string      $keyColumn
     * @param string|null $valColumn
     *
     * @return array
     */
    public static function map(&$arr, $keyColumn, $valColumn = null)
    {
        $result = [];
        foreach ($arr as $val) {
            if (!isset($val[$keyColumn]) || ($valColumn !== null && !isset($val[$valColumn]))) {
                break;
            }
            $result[$val[$keyColumn]] = $valColumn === null ? $val : $val[$valColumn];
        }
        return $result;
    }

    /**
     * Sorts multi-dimensional array by one or several keys.
     *
     * @param array        $arr
     * @param string|array $key
     * @param int|array    $direction
     * @param int|array    $sortFlag
     *
     * @throws InvalidArgumentException
     */
    public static function multisort(&$arr, $key, $direction = SORT_ASC, $sortFlag = SORT_REGULAR)
    {
        $keys = is_array($key) ? $key : [$key];
        if (empty($keys) || empty($arr)) {
            throw new InvalidArgumentException('Params $arr or $key is invalid for sorting.');
        }
        $keysCount = sizeof($keys);
        if (is_scalar($direction)) {
            $direction = array_fill(0, $keysCount, $direction);
        } elseif (sizeof($direction) !== $keysCount) {
            throw new InvalidArgumentException('The length of $direction and $keys params must be equal.');
        }
        if (is_scalar($sortFlag)) {
            $sortFlag = array_fill(0, $keysCount, $sortFlag);
        } elseif (sizeof($sortFlag) !== $keysCount) {
            throw new InvalidArgumentException('The length of $sortFlag and $keys params must be equal.');
        }
        $args = [];
        foreach ($keys as $i => $key) {
            $args[] = static::extractColumn($arr, $key);
            $args[] = $direction[$i];
            $args[] = $sortFlag[$i];
        }
        $args[] = &$arr;
        call_user_func_array('array_multisort', $args);
    }

    /**
     * Get array first key.
     *
     * @param  array $arr
     *
     * @return mixed
     */
    public static function firstKey(&$arr)
    {
        reset($arr);
        return key($arr);
    }

    /**
     * Get array last key.
     *
     * @param  array $arr
     *
     * @return mixed
     */
    public static function lastKey(&$arr)
    {
        end($arr);
        return key($arr);
    }

    /**
     * Check whether an array is associative or not.
     *
     * @param  array $arr
     *
     * @return bool
     */
    public static function isAssoc(&$arr)
    {
        return (bool)sizeof(array_filter(array_keys($arr), 'is_string'));
    }
}
