<?php

namespace Utility;

use Utility\Exception\NonStaticCallException;
use Utility\Translation\UAbstractTranslation;

class UAbstract
{
    /**
     * @throws NonStaticCallException
     */
    function __construct()
    {
        throw new NonStaticCallException('Non static call is disabled.');
    }

    /**
     * @return array
     */
    protected static function loadTranslations()
    {
        /** @var UAbstractTranslation $className */
        $className = str_replace('Utility', 'Utility\\Translation', get_called_class()) . 'Translation';
        return $className::load();
    }
}