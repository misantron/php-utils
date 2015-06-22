<?php

namespace Utility\Object;

/**
 * Class DateRange
 *
 * @category Object
 * @package  Utility\Object
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/UTime.php
 */
class DateRange implements DateRangeInterface
{
    /** @var \DateTime */
    private $rangeBegin;
    /** @var \DateTime */
    private $rangeEnd;

    /**
     * @param mixed $dateBegin
     * @param mixed $dateEnd
     */
    function __construct($dateBegin, $dateEnd)
    {
        if (is_int($dateBegin)) {
            $this->rangeBegin = (new \DateTime())->setTimestamp($dateBegin);
        } elseif (is_string($dateBegin)) {
            $this->rangeBegin = new \DateTime($dateBegin);
        } elseif ($dateBegin instanceof \DateTime) {
            $this->rangeBegin = $dateBegin;
        } else {
            throw new \InvalidArgumentException('$dateBegin argument format is invalid.');
        }

        if (is_int($dateEnd)) {
            $this->rangeEnd = (new \DateTime())->setTimestamp($dateEnd);
        } elseif (is_string($dateEnd)) {
            $this->rangeEnd = new \DateTime($dateEnd);
        } elseif ($dateEnd instanceof \DateTime) {
            $this->rangeEnd = $dateEnd;
        } else {
            throw new \InvalidArgumentException('$dateEnd argument format is invalid.');
        }
    }

    /**
     * @return \DateTime
     */
    public function getRangeBegin()
    {
        return $this->rangeBegin;
    }

    /**
     * @return \DateTime
     */
    public function getRangeEnd()
    {
        return $this->rangeEnd;
    }

    /**
     * @return \DateTime[]
     */
    public function toArray()
    {
        return [$this->rangeBegin, $this->rangeEnd];
    }
}