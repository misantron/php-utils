<?php

require_once 'TestCase.php';

use Utility\Exception\InvalidArgumentException;
use Utility\Exception\OutOfRangeException;
use Utility\Exception\NonStaticCallException;
use Utility\UTime;

date_default_timezone_set('UTC');

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
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
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
        } catch(OutOfRangeException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\OutOfRangeException', $e);
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
            static::callMethod('\\Utility\\UString', 'loadTranslations', array('timeDiffSomething'));
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Can not load translation for method.', $e->getMessage());
        }

        $result = static::callMethod('\\Utility\\UTime', 'loadTranslations', array('timeDiff'));
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }

    public function testPrepareArgs()
    {
        try {
            static::callMethod('\\Utility\\UTime', 'prepareArgs', array(true, '2015-02-01'));
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            static::callMethod('\\Utility\\UTime', 'prepareArgs', array(true, false));
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            static::callMethod('\\Utility\\UTime', 'prepareArgs', array(1424380190, false));
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        $result = static::callMethod('\\Utility\\UTime', 'prepareArgs', array('2015-02-13', '2015-02-26'));
        $expected = array(new \DateTime('2015-02-13'), new \DateTime('2015-02-26'));
        $this->assertEquals($expected, $result);

        $result = static::callMethod('\\Utility\\UTime', 'prepareArgs', array(1424380190, 1424808432));
        $date1 = new \DateTime();
        $date1->setTimestamp(1424380190);
        $date2 = new \DateTime();
        $date2->setTimestamp(1424808432);
        $expected = array($date1, $date2);
        $this->assertEquals($expected, $result);

        $result = static::callMethod('\\Utility\\UTime', 'prepareArgs', array('2014-11-13', 1424380190));
        $date1 = new \DateTime('2014-11-13');
        $date2 = new \DateTime();
        $date2->setTimestamp(1424380190);
        $expected = array($date1, $date2);
        $this->assertEquals($expected, $result);

        $result = static::callMethod('\\Utility\\UTime', 'prepareArgs', array(new \DateTime('2015-02-26 13:05'), new \DateTime('2015-02-26 22:16')));
        $expected = array(new \DateTime('2015-02-26 13:05'), new \DateTime('2015-02-26 22:16'));
        $this->assertEquals($expected, $result);
    }
}