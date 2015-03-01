<?php

use Utility\Exception\NonStaticCallException;
use Utility\Exception\InvalidArgumentException;
use Utility\UString;

class UStringTest extends \PHPUnit_Framework_TestCase
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
            new UString();
            $this->setExpectedException('\\Utility\\Exception\\NonStaticCallException');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testTruncate()
    {
        if (!extension_loaded('mbstring')) {
            $this->markTestSkipped('The mbstring extension is not available.');
        }

        $result = UString::truncate('http://www.php.net/manual/en/function.substr.php', 12);
        $expected = 'http://www.p';
        $this->assertEquals($expected, $result);

        $result = UString::truncate('www.php.net', 20);
        $expected = 'www.php.net';
        $this->assertEquals($expected, $result);

        $result = UString::truncate('www.php.net', 18, true);
        $expected = 'www.php.net';
        $this->assertEquals($expected, $result);

        $result = UString::truncate('http://www.php.net/manual/en/function.substr.php', 16, '...');
        $expected = 'http://www.php.n...';
        $this->assertEquals($expected, $result);
    }

    public function testTruncateWords()
    {
        if (!extension_loaded('mbstring')) {
            $this->markTestSkipped('The mbstring extension is not available.');
        }

        $result = UString::truncateWords('Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться.', 3);
        $expected = 'Давно выяснено, что';
        $this->assertEquals($expected, $result);

        $result = UString::truncateWords('Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться.', 5, '...');
        $expected = 'Давно выяснено, что при оценке...';
        $this->assertEquals($expected, $result);

        $result = UString::truncateWords('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 4);
        $expected = 'Lorem ipsum dolor sit';
        $this->assertEquals($expected, $result);

        $result = UString::truncateWords('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 6, '...');
        $expected = 'Lorem ipsum dolor sit amet, consectetur...';
        $this->assertEquals($expected, $result);

        $result = UString::truncateWords('Lorem ipsum', 5);
        $expected = 'Lorem ipsum';
        $this->assertEquals($expected, $result);
    }

    public function testPlural()
    {
        try {
            UString::plural(12, array('страница', 'страниц'));
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Param $forms must contains three words.', $e->getMessage());
        }

        $result = UString::plural(5, array('машина', 'машины', 'машин'));
        $expected = 'машин';
        $this->assertEquals($expected, $result);

        $result = UString::plural(2, array('машина', 'машины', 'машин'));
        $expected = 'машины';
        $this->assertEquals($expected, $result);

        $result = UString::plural(1, array('машина', 'машины', 'машин'), true);
        $expected = '1 машина';
        $this->assertEquals($expected, $result);

        $result = UString::plural(34, array('содат', 'солдата', 'солдат'));
        $expected = 'солдата';
        $this->assertEquals($expected, $result);

        $result = UString::plural(178, array('содат', 'солдата', 'солдат'), true);
        $expected = '178 солдат';
        $this->assertEquals($expected, $result);
    }

    public function testRandom()
    {
        $string = UString::random();
        $this->assertEquals(10, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{10}$/', $string);

        $string = UString::random(17);
        $this->assertEquals(17, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{17}$/', $string);
    }

    public function testSlugify()
    {
        $expected = 'hello-world';
        $result = UString::slugify('Hello World');
        $this->assertEquals($expected, $result);

        $expected = 'privet-mir';
        $result = UString::slugify('Привет мир');
        $this->assertEquals($expected, $result);

        $expected = 'c-est-du-francais';
        $result = UString::slugify('..C’est du français !');
        $this->assertEquals($expected, $result);
    }

    public function testFileSize()
    {
        try {
            $bytes = PHP_INT_MAX + 36;
            UString::fileSize($bytes);
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('Utility\Exception\InvalidArgumentException', $e);
            $this->assertEquals('Bytes size exceeds PHP_INT_MAX.', $e->getMessage());
        }

        $expected = '1.33 Гб';
        $result = UString::fileSize(1424380190);
        $this->assertEquals($expected, $result);

        $expected = '1.3266 Гб';
        $result = UString::fileSize(1424380190, 4);
        $this->assertEquals($expected, $result);

        $expected = '371.28 Кб';
        $result = UString::fileSize(380190);
        $this->assertEquals($expected, $result);

        $expected = '1.391 Кб';
        $result = UString::fileSize(1424, 3);
        $this->assertEquals($expected, $result);

        $expected = '23.25 Мб';
        $result = UString::fileSize(24380156);
        $this->assertEquals($expected, $result);
    }

    public function testLoadTranslations()
    {
        try {
            static::callMethod('\\Utility\\UString', 'loadTranslations', array('fileSize1'));
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Can not load translation for method.', $e->getMessage());
        }

        $result = static::callMethod('\\Utility\\UString', 'loadTranslations', array('fileSize'));
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
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