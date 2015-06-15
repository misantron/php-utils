<?php

namespace Utility\Tests;

use Utility\Exception\NonStaticCallException;
use Utility\Exception\InvalidArgumentException;
use Utility\UString;

/**
 * Class UStringTest
 * @package Utility\Tests
 */
class UStringTest extends TestCase
{
    public function testConstructor()
    {
        try {
            new UString();
            $this->fail('Expected exception not thrown');
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
            $this->fail('Expected exception not thrown');
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
        $this->assertEquals(16, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{16}$/', $string);

        $string = UString::random(21);
        $this->assertEquals(21, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{21}$/', $string);

        $string = UString::random(12, true);
        $this->assertEquals(12, strlen($string));
        $this->assertRegExp('/^[abdefghjkmnpqrstuvwxyz123456789ABDEFGHJKLMNPQRSTUVWXYZ]{12}$/', $string);
    }

    public function testSecureRandom()
    {
        $result = UString::secureRandom();
        $this->assertEquals(16, strlen($result));
        $this->assertRegExp('/^[a-zA-Z0-9\-_]{16}$/', $result);

        $result = UString::secureRandom(34);
        $this->assertEquals(34, strlen($result));
        $this->assertRegExp('/^[a-zA-Z0-9\-_]{34}$/', $result);
    }

    public function testByteLength()
    {
        $example = 'sfbfs%f472bss7842hdw7';
        $result = UString::byteLength($example);
        $this->assertEquals(mb_strlen($example, '8bit'), $result);

        $example = 'аовырРА763та-?№то4оаы7ол427ал_';
        $result = UString::byteLength($example);
        $this->assertEquals(mb_strlen($example, '8bit'), $result);
    }

    public function testByteSubstr()
    {
        $example = 'sfbfs%f472bss7842hdw7';

        $result = UString::byteSubstr($example);
        $this->assertEquals(21, mb_strlen($result));
        $this->assertEquals($example, $result);

        $result = UString::byteSubstr($example, 2);
        $this->assertEquals(19, mb_strlen($result));
        $expected = 'bfs%f472bss7842hdw7';
        $this->assertEquals($expected, $result);

        $result = UString::byteSubstr($example, 0, 5);
        $this->assertEquals(5, mb_strlen($result));
        $expected = 'sfbfs';
        $this->assertEquals($expected, $result);

        $result = UString::byteSubstr($example, 4, 12);
        $this->assertEquals(12, mb_strlen($result));
        $expected = 's%f472bss784';
        $this->assertEquals($expected, $result);
    }

    public function testSlug()
    {
        $expected = 'hello-world';
        $result = UString::slug('Hello World');
        $this->assertEquals($expected, $result);

        $expected = 'privet-mir';
        $result = UString::slug('Привет мир');
        $this->assertEquals($expected, $result);

        $expected = 'c-est-du-francais';
        $result = UString::slug('..C’est du français !');
        $this->assertEquals($expected, $result);
    }

    public function testFileSize()
    {
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
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Can not load translation for method.', $e->getMessage());
        }

        $result = static::callMethod('\\Utility\\UString', 'loadTranslations', array('fileSize'));
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
    }

    public function testContains()
    {
        $haystack = 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque';
        $needle = 'unde';
        $result = UString::contains($haystack, $needle);
        $this->assertTrue($result);

        $haystack = 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque';
        $needle = 'oMnis';
        $result = UString::contains($haystack, $needle);
        $this->assertTrue($result);

        $haystack = 'Sed ut perspiciatis unde omnis iste natus Error sit voluptatem accusantium doloremque';
        $needle = 'oMnis';
        $result = UString::contains($haystack, $needle, true);
        $this->assertFalse($result);

        $haystack = 'Sed ut perspiciatis unde omnis iste natus Error sit voluptatem accusantium doloremque';
        $needle = 'Error';
        $result = UString::contains($haystack, $needle, true);
        $this->assertTrue($result);

        $haystack = 'Sed ut perspiciatis unde omnis iste natus Error sit voluptatem accusantium doloremque';
        $needle = 'error';
        $result = UString::contains($haystack, $needle, true);
        $this->assertFalse($result);
    }
}