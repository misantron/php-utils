<?php

namespace Utility;

use Utility\Exception\InvalidArgumentException;

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
     */
    public static function timeDiff($from, $to)
    {
        if(is_int($from)){
            $fromDate = new \DateTime();
            $fromDate->setTimestamp($from);
        } elseif(is_string($from)){
            $fromDate = new \DateTime($from);
        } elseif($from instanceof \DateTime) {
            $fromDate = $from;
        } else {
            throw new InvalidArgumentException('$from argument format is invalid.');
        }

        if(is_int($to)){
            $toDate = new \DateTime();
            $toDate->setTimestamp($to);
        } elseif(is_string($to)){
            $toDate = new \DateTime($to);
        } elseif($to instanceof \DateTime) {
            $toDate = $to;
        } else {
            throw new InvalidArgumentException('$to argument format is invalid.');
        }

        if($fromDate > $toDate){
            throw new InvalidArgumentException('$from argument can not be greater than $to argument.');
        }

        $diff = $fromDate->diff($toDate);

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
}