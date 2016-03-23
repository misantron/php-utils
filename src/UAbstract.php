<?php

namespace Utility;

use Utility\Exception\NonStaticCallException;
use Utility\Translation\UAbstractTranslation;

/**
 * Class UAbstract
 *
 * @category Utility
 * @package  Utility
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  https://github.com/misantron/php-utils/blob/master/LICENSE (MIT License)
 * @link     https://github.com/misantron/php-utils/blob/master/src/UAbstract.php
 */
class UAbstract
{
    /**
     * @throws NonStaticCallException
     */
    public function __construct()
    {
        throw new NonStaticCallException('Non static call is disabled.');
    }

    /**
     * Load translations array.
     *
     * @param string $method
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     *
     * @codeCoverageIgnore
     */
    protected static function loadTranslations($method)
    {
        /**
         * @var UAbstractTranslation $className
         */
        $className = str_replace('Utility', 'Utility\\Translation', get_called_class()) . 'Translation';
        $dictionary = $className::load($method);
        if ($dictionary === null) {
            throw new \InvalidArgumentException('Can not load translation for method.');
        }
        return $dictionary;
    }
}
