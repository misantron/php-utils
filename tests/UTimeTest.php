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
            $this->fail('Expected exception not thrown');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testTimeDiff()
    {
        try {
            UTime::timeDiff(true, '2015-02-01');
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            UTime::timeDiff(true, false);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

        try {
            UTime::timeDiff(1424380190, false);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
        }

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

    /**
     * Call protected class method using reflection
     *
     * @param string $obj
     * @param string $name
     * @param array $args
     * @return mixed
     */
    protected static function callMethod($obj, $name, $args = array())
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs(null, $args);
    }
}