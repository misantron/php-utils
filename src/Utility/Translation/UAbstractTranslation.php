<?php

namespace Utility\Translation;

class UAbstractTranslation
{
    protected static $dictionary = array();

    /**
     * @return array
     */
    public static function load()
    {
        return static::$dictionary;
    }
}