<?php

use Utility\Exception\NonStaticCallException;
use Utility\UString;

class UStringTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $dir = dirname(__FILE__) . '/..';
        if (file_exists("{$dir}/vendor/autoload.php")) {
            require_once "{$dir}/vendor/autoload.php";
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

    public function testCutChars()
    {

    }

    public function testCutWords()
    {

    }

    public function testPlural()
    {

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

    }
}