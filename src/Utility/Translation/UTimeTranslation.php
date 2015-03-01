<?php

namespace Utility\Translation;

class UTimeTranslation extends UAbstractTranslation
{
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