<?php

namespace Utility\Tests\Object;

use Utility\Object\DateRange;
use Utility\Tests\TestCase;

date_default_timezone_set('UTC');

/**
 * Class DateRangeTest
 * @package Utility\Tests\Object
 */
class DateRangeTest extends TestCase
{
    public function testConstructor()
    {
        try {
            new DateRange(true, '2015-02-01');
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
            $this->assertEquals('$dateBegin argument format is invalid.', $e->getMessage());
        }

        try {
            new DateRange(1424380190, false);
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
            $this->assertEquals('$dateEnd argument format is invalid.', $e->getMessage());
        }

        try {
            new DateRange(false, new \StdClass());
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
            $this->assertEquals('$dateBegin argument format is invalid.', $e->getMessage());
        }
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