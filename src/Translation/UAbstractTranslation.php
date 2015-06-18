<?php

namespace Utility\Translation;

/**
 * Class UAbstractTranslation
 *
 * @category Translation
 * @package  Utility\Translation
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class UAbstractTranslation
{
    /**
     * Translations dictionary.
     *
     * @var array
     */
    protected static $dictionary = [];

    /**
     * Get translation by key.
     *
     * @param string $key
     *
     * @return array
     */
    public static function load($key)
    {
        return isset(static::$dictionary[$key]) ? static::$dictionary[$key] : null;
    }
}
