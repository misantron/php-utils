<?php

use Utility\Exception\NonStaticCallException;
use Utility\Test\AbstractTest;
use Utility\UString;

class UStringTest extends AbstractTest
{
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
    }

    public function testPlural()
    {
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
}