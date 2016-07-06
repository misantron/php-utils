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

    /**
     * @param mixed $dateBegin
     * @param mixed $dateEnd
     */
    public function __construct($dateBegin, $dateEnd)
    {
        $this->rangeBegin = $this->castDateParam($dateBegin);
        $this->rangeEnd = $this->castDateParam($dateEnd);
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
     * @return \DateTime[]
     */
    public function getRange($format = 'Y-m-d')
    {
        $range = [];
        $rangeBegin = clone $this->rangeBegin;
        if ($this->rangeBegin->format($format) > $this->rangeEnd->format($format)) {
            while ($rangeBegin >= $this->rangeEnd) {
                $range[] = $rangeBegin->format($format);
                $rangeBegin->sub(new \DateInterval('P1D'));
            }
        } else {
            while ($rangeBegin <= $this->rangeEnd) {
                $range[] = $rangeBegin->format($format);
                $rangeBegin->add(new \DateInterval('P1D'));
            }
        }
        return $range;
    }

    /**
     * @return \DateTime[]
     */
    public function toArray()
    {
        return [$this->rangeBegin, $this->rangeEnd];
    }

    /**
     * @param mixed $param
     * @return \DateTime
     */
    protected function castDateParam($param)
    {
        if (is_int($param)) {
            $date = (new \DateTime())->setTimestamp($param);
        } elseif (is_string($param)) {
            $date = new \DateTime($param);
        } elseif ($param instanceof \DateTime) {
            $date = $param;
        } else {
            throw new \InvalidArgumentException('Date format is invalid.');
        }
        return $date;
    }
}