<?php

use Utility\Exception\InvalidArgumentException;
use Utility\Exception\NonStaticCallException;
use Utility\UTime;

date_default_timezone_set('UTC');

class UTimeTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $vendorAutoloadPath = dirname(__FILE__) . '/../vendor/autoload.php';
        if (file_exists($vendorAutoloadPath)) {
            require_once $vendorAutoloadPath;
        }
    }

    public function testConstructor()
    {
        try {
            new UTime();
            $this->setExpectedException('\\Utility\\Exception\\NonStaticCallException');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testTimeDiff()
    {
        try {
            UTime::timeDiff(true, '2015-02-01');
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            UTime::timeDiff(true, false);
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            UTime::timeDiff(1424380190, false);
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            UTime::timeDiff('2015-03-04', '2015-02-01');
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        $result = UTime::timeDiff(1424380190, 1424380947);
        $expected = '12 минут назад';
        $this->assertEquals($expected, $result);

        $result = UTime::timeDiff(1424380190, 1424808432);
        $expected = '4 дня назад';
        $this->assertEquals($expected, $result);
    }
}