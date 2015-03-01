<?php

namespace Utility;

use Cocur\Slugify\Slugify;
use Utility\Exception\ExtensionNotLoadedException;
use Utility\Exception\InvalidArgumentException;

class UString extends UAbstract
{
    /** @var string */
    protected static $encoding = 'UTF-8';

    /**
     * Truncate string to given length.
     *
     * @param string $str
     * @param int $length
     * @param string $ending
     * @return string
     *
     * @throws ExtensionNotLoadedException
     */
    public static function truncate($str, $length, $ending = '')
    {
        // @codeCoverageIgnoreStart
        if(!extension_loaded('mbstring')){
            throw new ExtensionNotLoadedException('Mbstring extension is required for using this method.');
        }
        // @codeCoverageIgnoreEnd

        $strLength = mb_strlen($str, static::$encoding);
        return $strLength <= $length ? $str : trim(mb_substr($str, 0, $length, static::$encoding)) . $ending;
    }

    /**
     * Truncate string to given word count.
     *
     * @param string $str
     * @param int $count
     * @param string $ending
     * @return string
     *
     * @throws ExtensionNotLoadedException
     */
    public static function truncateWords($str, $count, $ending = '')
    {
        // @codeCoverageIgnoreStart
        if(!extension_loaded('mbstring')){
            throw new ExtensionNotLoadedException('Mbstring extension is required for using this method.');
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
     * @param int $number
     * @param array $forms
     * @param bool $withNumber
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function plural($number, $forms, $withNumber = false)
    {
        if(sizeof($forms) < 3){
            throw new InvalidArgumentException('Param $forms must contains three words.');
        }
        $text = $number%10==1&&$number%100!=11?$forms[0]:($number%10>=2&&$number%10<=4&&($number%100<10||$number%100>=20)?$forms[1]:$forms[2]);
        return $withNumber ? $number . ' ' . $text : $text;
    }

    /**
     * Generate random string.
     *
     * @param int $length
     * @return string
     */
    public static function random($length = 10)
    {
        $randChars = array();
        $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphabetSize = strlen($alphabet);
        while(--$length+1){
            $randChars[] = $alphabet[mt_rand(0, $alphabetSize-1)];;
        }
        shuffle($randChars);
        return implode('', $randChars);
    }

    /**
     * Return slug for given string.
     *
     * @param string $str
     * @return string
     */
    public static function slugify($str)
    {
        return Slugify::create()->slugify($str);
    }

    /**
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function fileSize($bytes, $precision = 2)
    {
        $translations = static::loadTranslations(__FUNCTION__);
        $translations = array_values($translations);
        $factor = (int)floor((strlen($bytes) - 1) / 3);
        return round(($bytes / pow(1024, $factor)), $precision) . ' ' .  $translations[$factor];
    }
}