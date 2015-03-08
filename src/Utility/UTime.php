<?php

namespace Utility;

use Utility\Exception\InvalidArgumentException;
use Utility\Exception\OutOfRangeException;
use Utility\Exception\RuntimeException;

class UTime extends UAbstract
{
    /**
     * Get time diff in human-readable format
     *
     * @param mixed $from
     * @param mixed $to
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function timeDiff($from, $to)
    {
        /**
         * @var \DateTime $fromDate
         * @var \DateTime $toDate
         */
        // @codeCoverageIgnoreStart
        list($fromDate, $toDate) = static::prepareArgs($from, $to);
        // @codeCoverageIgnoreEnd

        $diff = $fromDate->diff($toDate);
        // @codeCoverageIgnoreStart
        if($diff === false){
            throw new RuntimeException('Unexpected runtime error.');
        }
        // @codeCoverageIgnoreEnd

        if($diff->invert === 1){
            throw new InvalidArgumentException('$from argument can not be greater than $to argument.');
        }

        $translations = static::loadTranslations(__FUNCTION__);

        if ($diff->y >= 1) {
            $text = UString::plural($diff->y, $translations['year'], true);
        } elseif ($diff->m >= 1) {
            $text = UString::plural($diff->m, $translations['month'], true);
        } elseif ($diff->d >= 7) {
            $text = UString::plural(ceil($diff->d / 7), $translations['week'], true);
        } elseif ($diff->d >= 1) {
            $text = UString::plural($diff->d, $translations['day'], true);
        } elseif ($diff->h >= 1) {
            $text = UString::plural($diff->h, $translations['hour'], true);
        } elseif ($diff->i >= 1) {
            $text = UString::plural($diff->i, $translations['minute'], true);
        } elseif ($diff->s >= 1) {
            $text = UString::plural($diff->s, $translations['second'], true);
        } else {
            $text = UString::plural(0, $translations['second'], true);
        }

        return trim($text) . ' ' . $translations['ago'];
    }

    /**
     * @param mixed $date1
     * @param mixed $date2
     * @return int
     *
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     * @throws RuntimeException
     */
    public static function secondsDiff($date1, $date2)
    {
        /**
         * @var \DateTime $fromDate
         * @var \DateTime $toDate
         */
        // @codeCoverageIgnoreStart
        list($fromDate, $toDate) = static::prepareArgs($date1, $date2);
        // @codeCoverageIgnoreEnd

        $diff = $fromDate->diff($toDate);
        // @codeCoverageIgnoreStart
        if($diff === false){
            throw new RuntimeException('Unexpected runtime error.');
        }
        // @codeCoverageIgnoreEnd

        if($diff->y > 0 || $diff->m > 0){
            throw new OutOfRangeException('Date diff can not exceed one month.');
        }

        $seconds = 0;

        if ($diff->d >= 1) {
            $seconds += $diff->d * 24 * 60 * 60;
        }
        if ($diff->h >= 1) {
            $seconds += $diff->h * 60 * 60;
        }
        if ($diff->i >= 1) {
            $seconds += ($diff->i * 60);
        }
        if ($diff->s >= 1) {
            $seconds += $diff->s;
        }

        return $diff->invert === 0 ? $seconds : -$seconds;
    }

    /**
     * @param mixed $arg1
     * @param mixed $arg2
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private static function prepareArgs($arg1, $arg2)
    {
        if(is_int($arg1)){
            $date1 = new \DateTime();
            $date1->setTimestamp($arg1);
        } elseif(is_string($arg1)){
            $date1 = new \DateTime($arg1);
        } elseif($arg1 instanceof \DateTime) {
            $date1 = $arg1;
        } else {
            throw new InvalidArgumentException('$from argument format is invalid.');
        }

        if(is_int($arg2)){
            $date2 = new \DateTime();
            $date2->setTimestamp($arg2);
        } elseif(is_string($arg2)){
            $date2 = new \DateTime($arg2);
        } elseif($arg2 instanceof \DateTime) {
            $date2 = $arg2;
        } else {
            throw new InvalidArgumentException('$to argument format is invalid.');
        }

        return array($date1, $date2);
    }
}