<?php

namespace Utility\Translation;

class UAbstractTranslation
{
    protected static $dictionary = array();

    /**
     * @param string $key
     * @return array
     */
    public static function load($key)
    {
        return isset(static::$dictionary[$key]) ? static::$dictionary[$key] : null;
    }
}