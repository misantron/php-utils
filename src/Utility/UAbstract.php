<?php

namespace Utility;

use Utility\Exception\InvalidArgumentException;
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
     * @codeCoverageIgnore
     * @param string $method
     * @return array
     *
     * @throws InvalidArgumentException
     */
    protected static function loadTranslations($method)
    {
        /** @var UAbstractTranslation $className */
        $className = str_replace('Utility', 'Utility\\Translation', get_called_class()) . 'Translation';
        $dictionary = $className::load($method);
        if($dictionary === null){
            throw new InvalidArgumentException('Can not load translation for method.');
        }
        return $dictionary;
    }
}