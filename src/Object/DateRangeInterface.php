<?php

namespace Utility\Object;

/**
 * Interface DateRangeInterface
 *
 * @category Object
 * @package  Utility\Object
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/Object/DateRangeInterface.php
 */
interface DateRangeInterface
{
    /**
     * @return \DateTime
     */
    public function getRangeBegin();

    /**
     * @return \DateTime
     */
    public function getRangeEnd();

    /**
     * @param string $format
     * @return \DateTime[]
     */
    public function getRange($format = 'Y-m-d');

    /**
     * @return \DateTime[]
     */
    public function toArray();
}