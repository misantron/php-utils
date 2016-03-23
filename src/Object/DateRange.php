<?php

namespace Utility\Object;

/**
 * Class DateRange
 *
 * @category Object
 * @package  Utility\Object
 * @author   Alexandr Ivanov <misantron@gmail.com>
 * @license  MIT https://github.com/misantron/php-utils/blob/master/LICENSE
 * @link     https://github.com/misantron/php-utils/blob/master/src/Object/DateRange.php
 */
class DateRange implements DateRangeInterface
{
    /** @var \DateTime */
    protected $rangeBegin;
    /** @var \DateTime */
    protected $rangeEnd;
    /** @var \DateTime[] */
    protected $range;

    /**
     * @param mixed $dateBegin
     * @param mixed $dateEnd
     */
    public function __construct($dateBegin, $dateEnd)
    {
        if (is_int($dateBegin)) {
            $this->rangeBegin = (new \DateTime())->setTimestamp($dateBegin);
        } elseif (is_string($dateBegin)) {
            $this->rangeBegin = new \DateTime($dateBegin);
        } elseif ($dateBegin instanceof \DateTime) {
            $this->rangeBegin = $dateBegin;
        } else {
            throw new \InvalidArgumentException('Range begin date format is invalid.');
        }

        if (is_int($dateEnd)) {
            $this->rangeEnd = (new \DateTime())->setTimestamp($dateEnd);
        } elseif (is_string($dateEnd)) {
            $this->rangeEnd = new \DateTime($dateEnd);
        } elseif ($dateEnd instanceof \DateTime) {
            $this->rangeEnd = $dateEnd;
        } else {
            throw new \InvalidArgumentException('Range end date format is invalid.');
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
     * @param string $format
     * @return array
     */
    public function getRange($format = 'Y-m-d')
    {
        if ($this->range === null) {
            $this->range[] = $this->rangeBegin->format($format);
            $rangeBegin = $this->rangeBegin;
            while ($rangeBegin < $this->rangeEnd) {
                $this->range[] = $rangeBegin->add(new \DateInterval('P1D'))->format($format);
            }
        }
        return $this->range;
    }

    /**
     * @return \DateTime[]
     */
    public function toArray()
    {
        return [$this->rangeBegin, $this->rangeEnd];
    }
}