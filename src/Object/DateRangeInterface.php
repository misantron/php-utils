<?php

namespace Utility\Object;

/**
 * Interface DateRangeInterface
 *
 * @category Object
 * @package  Utility\Object
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
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
}