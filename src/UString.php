<?php

namespace Utility;

use Cocur\Slugify\Slugify;
use Utility\Exception\ExtensionNotLoadedException;
use Utility\Exception\InvalidArgumentException;
use Utility\Exception\RuntimeException;

/**
 * Class UString
 *
 * @category Utility
 * @package  Utility
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class UString extends UAbstract
{
    /**
     * Base string encoding
     *
     * @var string
     */
    protected static $encoding = 'UTF-8';

    /**
     * Truncate string to given length.
     *
     * @param string $str
     * @param int    $length
     * @param string $ending
     *
     * @return string
     *
     * @throws ExtensionNotLoadedException
     */
    public static function truncate($str, $length, $ending = '')
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('mbstring')) {
            throw new ExtensionNotLoadedException('mbstring PHP extension is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $strLength = mb_strlen($str, static::$encoding);
        return $strLength <= $length ? $str : trim(mb_substr($str, 0, $length, static::$encoding)) . $ending;
    }

    /**
     * Truncate string to given word count.
     *
     * @param string $str
     * @param int    $count
     * @param string $ending
     *
     * @return string
     *
     * @throws ExtensionNotLoadedException
     */
    public static function truncateWords($str, $count, $ending = '')
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('mbstring')) {
            throw new ExtensionNotLoadedException('mbstring PHP extension is not installed.');
        }
        // @codeCoverageIgnoreEnd

        preg_match('/^\s*+(?:\S++\s*+){1,' . $count . '}/u', $str, $matches);
        if (!isset($matches[0]) || mb_strlen($str, static::$encoding) === mb_strlen($matches[0], static::$encoding)) {
            return $str;
        }
        return trim($matches[0]) . $ending;
    }

    /**
     * Return right word form for given number.
     *
     * @param int   $number
     * @param array $forms
     * @param bool  $withNumber
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function plural($number, $forms, $withNumber = false)
    {
        if (sizeof($forms) < 3) {
            throw new InvalidArgumentException('Param $forms must contains three words.');
        }
        $text = $number % 10 == 1 && $number % 100 != 11 ? $forms[0] : ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 >= 20) ? $forms[1] : $forms[2]);
        return $withNumber ? $number . ' ' . $text : $text;
    }

    /**
     * Generate random string.
     *
     * @param int  $length
     * @param bool $humanFriendly
     *
     * @return string
     */
    public static function random($length = 16, $humanFriendly = false)
    {
        $randChars = array();
        if ($humanFriendly) {
            $chars = 'abdefghjkmnpqrstuvwxyz123456789ABDEFGHJKLMNPQRSTUVWXYZ';
        } else {
            $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $charsLength = strlen($chars) - 1;
        $length++;
        while (--$length) {
            $randChars[] = $chars[mt_rand(0, $charsLength)];
        }
        return implode('', $randChars);
    }

    /**
     * Generate string based on pseudo-random bytes sequence.
     *
     * @param int $length
     *
     * @return string
     *
     * @throws ExtensionNotLoadedException
     * @throws RuntimeException
     */
    public static function secureRandom($length = 16)
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('openssl')) {
            throw new ExtensionNotLoadedException('The OpenSSL PHP extension is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $bytes = openssl_random_pseudo_bytes($length, $cryptStrong);
        // @codeCoverageIgnoreStart
        if (static::byteLength($bytes) < $length || !$cryptStrong) {
            throw new RuntimeException('Unable to generate random bytes.');
        }
        $bytes = static::byteSubstr($bytes, 0, $length);
        // @codeCoverageIgnoreEnd

        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
    }

    /**
     * Return byte length of string.
     *
     * @param string $str
     *
     * @return int
     *
     * @throws ExtensionNotLoadedException
     */
    public static function byteLength($str)
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('mbstring')) {
            throw new ExtensionNotLoadedException('mbstring PHP extension is not installed.');
        }
        // @codeCoverageIgnoreEnd

        return mb_strlen($str, '8bit');
    }

    /**
     * Return substring from byte sequence string.
     *
     * @param string   $bytes
     * @param int      $start
     * @param int|null $length
     *
     * @return string
     *
     * @throws ExtensionNotLoadedException
     */
    public static function byteSubstr($bytes, $start = 0, $length = null)
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('mbstring')) {
            throw new ExtensionNotLoadedException('mbstring PHP extension is not installed.');
        }
        // @codeCoverageIgnoreEnd

        return mb_substr($bytes, $start, $length === null ? mb_strlen($bytes, '8bit') : $length, '8bit');
    }

    /**
     * Return slug for given string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function slug($str)
    {
        return Slugify::create()->slugify($str);
    }

    /**
     * Get file size in human-readable format.
     *
     * @param int $bytes
     * @param int $precision
     *
     * @return string
     */
    public static function fileSize($bytes, $precision = 2)
    {
        $translations = static::loadTranslations(__FUNCTION__);
        $translations = array_values($translations);
        $factor = (int)floor((strlen($bytes) - 1) / 3);
        return round(($bytes / pow(1024, $factor)), $precision) . ' ' . $translations[$factor];
    }

    /**
     * Check whether a substring contains in string or not.
     *
     * @param string $haystack
     * @param string $needle
     * @param bool   $caseSensitive
     *
     * @return bool
     */
    public static function contains($haystack, $needle, $caseSensitive = false)
    {
        $offset = $caseSensitive ? strpos($haystack, $needle) : stripos($haystack, $needle);
        return $offset !== false;
    }
}
