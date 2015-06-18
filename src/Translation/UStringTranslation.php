<?php

namespace Utility\Translation;

/**
 * Class UStringTranslation
 *
 * @category Translation
 * @package  Utility\Translation
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class UStringTranslation extends UAbstractTranslation
{
    /**
     * Translations dictionary.
     *
     * @var array
     */
    protected static $dictionary = [
        'fileSize' => [
            'b' => 'б',
            'kb' => 'Кб',
            'mb' => 'Мб',
            'gb' => 'Гб',
            'tb' => 'Тб',
            'pb' => 'Пб',
            'eb' => 'Eб',
        ],
    ];
}
