<?php

namespace Utility\Tests\Object;

use Utility\Object\DateRange;
use Utility\Tests\TestCase;

/**
 * Class DateRangeTest
 * @package Utility\Tests\Object
 */
class DateRangeTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Date format is invalid.
     */
    public function testConstructorWithInvalidRangeBegin()
    {
        new DateRange(true, '2015-02-01');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Date format is invalid.
     */
    public function testConstructorWithInvalidRangeEnd()
    {
        new DateRange(1424380190, false);
    }

    public function testConstructor()
    {
        $dateRange = new DateRange('2016-03-12', '2016-03-15');

        $this->assertAttributeEquals(new \DateTime('2016-03-12'), 'rangeBegin', $dateRange);
        $this->assertAttributeEquals(new \DateTime('2016-03-15'), 'rangeEnd', $dateRange);
    }

    public function testGetRangeBegin()
    {
        $result = (new DateRange('2015-02-13', '2015-02-26'))->getRangeBegin();
        $expected = new \DateTime('2015-02-13');
        $this->assertEquals($expected, $result);

        $result = (new DateRange(1424380190, 1424808432))->getRangeBegin();
        $expected = (new \DateTime())->setTimestamp(1424380190);
        $this->assertEquals($expected, $result);

        $result = (new DateRange(new \DateTime('2014-11-13'), 1424380190))->getRangeBegin();
        $expected = new \DateTime('2014-11-13');
        $this->assertEquals($expected, $result);
    }

    public function testGetRangeEnd()
    {
        $result = (new DateRange('2015-02-13', '2015-02-26'))->getRangeEnd();
        $expected = new \DateTime('2015-02-26');
        $this->assertEquals($expected, $result);

        $result = (new DateRange(1424380190, 1424808432))->getRangeEnd();
        $expected = (new \DateTime())->setTimestamp(1424808432);
        $this->assertEquals($expected, $result);

        $result = (new DateRange('2014-11-13', new \DateTime('2015-02-26')))->getRangeEnd();
        $expected = new \DateTime('2015-02-26');
        $this->assertEquals($expected, $result);
    }

    public function testGetRange()
    {
        $range = new DateRange('2016-03-11', '2016-03-16');
        $actual = $range->getRange();

        $this->assertInternalType('array', $actual);
        $this->assertEquals('2016-03-11', $actual[0]);
        $this->assertEquals('2016-03-16', $actual[5]);
        $this->assertCount(6, $actual);

        $range = new DateRange('2016-03-12', '2016-03-12');
        $actual = $range->getRange('d-m-Y');

        $this->assertInternalType('array', $actual);
        $this->assertEquals('12-03-2016', $actual[0]);
        $this->assertCount(1, $actual);
    }

    public function testGetRangeWithGreaterBegin()
    {
        $range = new DateRange('2016-03-13', '2016-03-11');
        $actual = $range->getRange();

        $this->assertInternalType('array', $actual);
        $this->assertEquals('2016-03-13', $actual[0]);
        $this->assertEquals('2016-03-12', $actual[1]);
        $this->assertEquals('2016-03-11', $actual[2]);
        $this->assertCount(3, $actual);
    }

    public function testToArray()
    {
        $result = (new DateRange('2015-02-13', '2015-02-26'))->toArray();
        $expected = [new \DateTime('2015-02-13'), new \DateTime('2015-02-26')];
        $this->assertEquals($expected, $result);

        $result = (new DateRange(1424380190, 1424808432))->toArray();
        $date1 = new \DateTime();
        $date1->setTimestamp(1424380190);
        $date2 = new \DateTime();
        $date2->setTimestamp(1424808432);
        $expected = [$date1, $date2];
        $this->assertEquals($expected, $result);

        $result = (new DateRange('2014-11-13', 1424380190))->toArray();
        $date1 = new \DateTime('2014-11-13');
        $date2 = new \DateTime();
        $date2->setTimestamp(1424380190);
        $expected = [$date1, $date2];
        $this->assertEquals($expected, $result);

        $result = (new DateRange(new \DateTime('2015-02-26 13:05'), new \DateTime('2015-02-26 22:16')))->toArray();
        $expected = [new \DateTime('2015-02-26 13:05'), new \DateTime('2015-02-26 22:16')];
        $this->assertEquals($expected, $result);
    }
}