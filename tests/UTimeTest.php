<?php

namespace Utility\Tests;

use Utility\Exception\NonStaticCallException;
use Utility\UTime;

/**
 * Class UTimeTest
 * @package Utility\Tests
 */
class UTimeTest extends TestCase
{
    public function testConstructor()
    {
        try {
            new UTime();
            $this->fail('Expected exception not thrown');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testTimeDiff()
    {
        try {
            UTime::timeDiff('2015-03-04', '2015-02-01');
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
        }

        $result = UTime::timeDiff(1424380190, 1424380947);
        $expected = '12 минут назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff(1424380190, 1424808432);
        $expected = '4 дня назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff(new \DateTime('2015-01-13'), new \DateTime('2015-01-13'));
        $expected = '0 секунд назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff('2014-01-13', '2015-01-15');
        $expected = '1 год назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff('2014-11-13', '2015-02-15');
        $expected = '3 месяца назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff('2015-02-13', '2015-02-26');
        $expected = '2 недели назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff(new \DateTime('2015-02-23'), new \DateTime('2015-02-26'));
        $expected = '3 дня назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff(new \DateTime('2015-02-26 13:05'), new \DateTime('2015-02-26 22:16'));
        $expected = '9 часов назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff(new \DateTime('2015-02-26 22:16:21'), new \DateTime('2015-02-26 22:16:37'));
        $expected = '16 секунд назад';
        $this->assertEquals($expected, $result);
    }

    public function testSecondsDiff()
    {
        try {
            UTime::secondsDiff('2014-11-13', '2015-02-15');
            $this->fail('Expected exception not thrown');
        } catch(\OutOfRangeException $e){
            $this->assertInstanceOf('\\OutOfRangeException', $e);
        }

        $result = UTime::secondsDiff(new \DateTime('2015-02-26 22:16:21'), new \DateTime('2015-02-26 22:16:37'));
        $expected = 16;
        $this->assertEquals($expected, $result);

        $result = UTime::secondsDiff(1424380190, 1424380947);
        $expected = 12 * 60 + 37;
        $this->assertEquals($expected, $result);

        $result = UTime::secondsDiff(new \DateTime('2015-02-26 13:05'), new \DateTime('2015-02-26 22:16'));
        $expected = 9 * 60 * 60 + 11 * 60;
        $this->assertEquals($expected, $result);

        $result = UTime::secondsDiff(new \DateTime('2015-02-23'), new \DateTime('2015-02-26'));
        $expected = 3 * 24 * 60 * 60;
        $this->assertEquals($expected, $result);

        $result = UTime::secondsDiff('2015-02-13', '2015-02-01');
        $expected = 12 * 24 * 60 * 60;
        $this->assertEquals(-$expected, $result);
    }

    public function testLoadTranslations()
    {
        try {
            static::callMethod('\\Utility\\UString', 'loadTranslations', ['timeDiffSomething']);
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
            $this->assertEquals('Can not load translation for method.', $e->getMessage());
        }

        $result = static::callMethod('\\Utility\\UTime', 'loadTranslations', ['timeDiff']);
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}