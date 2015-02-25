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

        $translations = static::loadTranslations();

        if ($diff->y >= 1) {
            $text = UString::plural($diff->y, array($translations['year'], $translations['years1'], $translations['years2']), true);
        } elseif ($diff->m >= 1) {
            $text = UString::plural($diff->m, array($translations['month'], $translations['months1'], $translations['months2']), true);
        } elseif ($diff->d >= 7) {
            $text = UString::plural(ceil($diff->d / 7), array($translations['week'], $translations['weeks1'], $translations['weeks2']), true);
        } elseif ($diff->d >= 1) {
            $text = UString::plural($diff->d, array($translations['day'], $translations['days1'], $translations['days2']), true);
        } elseif ($diff->h >= 1) {
            $text = UString::plural($diff->h, array($translations['hour'], $translations['hours1'], $translations['hours2']), true);
        } elseif ($diff->i >= 1) {
            $text = UString::plural($diff->i, array($translations['minute'], $translations['minutes1'], $translations['minutes2']), true);
        } elseif ($diff->s >= 1) {
            $text = UString::plural($diff->s, array($translations['second'], $translations['seconds1'], $translations['seconds2']), true);
        } else {
            $text = '0 ' . $translations['seconds2'];
        }

        return trim($text) . ' ' . $translations['ago'];
    }
}