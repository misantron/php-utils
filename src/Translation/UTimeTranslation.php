<?php

namespace Utility\Translation;

/**
 * Class UTimeTranslation
 *
 * @category Translation
 * @package  Utility\Translation
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class UTimeTranslation extends UAbstractTranslation
{
    /**
     * Translations dictionary.
     *
     * @var array
     */
    protected static $dictionary = array(
        'timeDiff' => array(
            'ago' => 'назад',
            'second' => array('секунда', 'секунды', 'секунд'),
            'minute' => array('минута', 'минуты', 'минут'),
            'hour' => array('час', 'часа', 'часов'),
            'day' => array('день', 'дня', 'дней'),
            'week' => array('неделя', 'недели', 'недель'),
            'month' => array('месяц', 'месяца', 'месяцев'),
            'year' => array('год', 'года', 'лет'),
        ),
    );
}
