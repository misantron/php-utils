<?php

namespace Utility\Translation;

/**
 * Class UTimeTranslation
 *
 * @category Translation
 * @package  Utility\Translation
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/Translation/UTimeTranslation.php
 */
class UTimeTranslation extends UAbstractTranslation
{
    /**
     * Translations dictionary.
     *
     * @var array
     */
    protected static $dictionary = [
        'timeDiff' => [
            'ago' => 'назад',
            'second' => ['секунда', 'секунды', 'секунд'],
            'minute' => ['минута', 'минуты', 'минут'],
            'hour' => ['час', 'часа', 'часов'],
            'day' => ['день', 'дня', 'дней'],
            'week' => ['неделя', 'недели', 'недель'],
            'month' => ['месяц', 'месяца', 'месяцев'],
            'year' => ['год', 'года', 'лет'],
        ],
    ];
}
