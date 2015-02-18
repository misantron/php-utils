<?php

namespace Utility;

use Utility\Exception\ExtensionNotLoadedException;
use Utility\Exception\InvalidArgumentException;
use Utility\Exception\NonStaticCallException;

class UString
{
    /** @var string */
    protected static $encoding = 'UTF-8';

    /**
     * @throws NonStaticCallException
     */
    function __construct()
    {
        throw new NonStaticCallException('Non static call is disabled.');
    }

    /**
     * @param string $string
     * @param int $maxLength
     * @param string|null $encoding
     * @return string
     *
     * @throws ExtensionNotLoadedException
     */
    public static function cut($string, $maxLength, $encoding = null)
    {
        if(!extension_loaded('mbstring')){
            throw new ExtensionNotLoadedException('Mbstring extension is required for using this method.');
        }

        if($encoding === null){
            $encoding = static::$encoding;
        }
        $length = mb_strlen($string, $encoding);
        if($length > $maxLength){
            $length = $maxLength;
        }
        return mb_substr($string, 0, $length, $encoding);
    }

    /**
     * @param int $number
     * @param array $forms
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function plural($number, $forms)
    {
        if(sizeof($forms) < 3){
            throw new InvalidArgumentException('Param $forms must contains three words.');
        }
        return $number%10==1&&$number%100!=11?$forms[0]:($number%10>=2&&$number%10<=4&&($number%100<10||$number%100>=20)?$forms[1]:$forms[2]);
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generateRandom($length = 10)
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

    public static function slugify($string)
    {

    }
}