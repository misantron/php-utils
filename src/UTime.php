<?php

namespace Utility;

use Utility\Exception\InvalidArgumentException;
use Utility\Exception\OutOfRangeException;
use Utility\Exception\RuntimeException;

/**
 * Class UTime
 *
 * @category Utility
 * @package  Utility
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class UTime extends UAbstract
{
    /**
     * Get time diff in human-readable format
     *
     * @param mixed $from
     * @param mixed $to
     *
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
        if ($diff === false) {
            throw new RuntimeException('Unexpected runtime error.');
        }
        // @codeCoverageIgnoreEnd

        if ($diff->invert === 1) {
            throw new InvalidArgumentException(
                '$from argument can not be greater than $to argument.'
            );
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
     * Return seconds difference between two dates
     *
     * @param mixed $periodStartDate
     * @param mixed $periodEndDate
     *
     * @return int
     *
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     * @throws RuntimeException
     */
    public static function secondsDiff($periodStartDate, $periodEndDate)
    {
        /**
         * @var \DateTime $fromDate
         * @var \DateTime $toDate
         */
        // @codeCoverageIgnoreStart
        list($fromDate, $toDate) = static::prepareArgs($periodStartDate, $periodEndDate);
        // @codeCoverageIgnoreEnd

        $diff = $fromDate->diff($toDate);
        // @codeCoverageIgnoreStart
        if ($diff === false) {
            throw new RuntimeException('Unexpected runtime error.');
        }
        // @codeCoverageIgnoreEnd

        if ($diff->y > 0 || $diff->m > 0) {
            throw new OutOfRangeException('Date diff may not exceed one month.');
        }

        $seconds = 0;

        if ($diff->d >= 1) {
            $seconds += $diff->d * 24 * 60 * 60;
        }
        if ($diff->h >= 1) {
            $seconds += $diff->h * 60 * 60;
        }
        if ($diff->i >= 1) {
            $seconds += $diff->i * 60;
        }
        if ($diff->s >= 1) {
            $seconds += $diff->s;
        }

        return $diff->invert === 0 ? $seconds : -$seconds;
    }

    /**
     * Convert date-like arguments to DateTime objects
     *
     * @param mixed $firstArg
     * @param mixed $secondArg
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private static function prepareArgs($firstArg, $secondArg)
    {
        if (is_int($firstArg)) {
            $firstDateObj = new \DateTime();
            $firstDateObj->setTimestamp($firstArg);
        } elseif (is_string($firstArg)) {
            $firstDateObj = new \DateTime($firstArg);
        } elseif ($firstArg instanceof \DateTime) {
            $firstDateObj = $firstArg;
        } else {
            throw new InvalidArgumentException('$from argument format is invalid.');
        }

        if (is_int($secondArg)) {
            $secondDateObj = new \DateTime();
            $secondDateObj->setTimestamp($secondArg);
        } elseif (is_string($secondArg)) {
            $secondDateObj = new \DateTime($secondArg);
        } elseif ($secondArg instanceof \DateTime) {
            $secondDateObj = $secondArg;
        } else {
            throw new InvalidArgumentException('$to argument format is invalid.');
        }

        return array($firstDateObj, $secondDateObj);
    }
}
