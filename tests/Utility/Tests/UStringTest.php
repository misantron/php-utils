<?php

namespace Utility\Tests;

use Utility\Exception\NonStaticCallException;
use Utility\UString;

class UStringTest extends \PHPUnit_Framework_TestCase
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

    public function testCut()
    {

    }

    public function testPlural()
    {

    }

    public function testGenerateRandom()
    {
        $string = UString::generateRandom();
        $this->assertEquals(10, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{10}$/', $string);

        $string = UString::generateRandom(17);
        $this->assertEquals(17, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{17}$/', $string);
    }

    public function testSlugify()
    {

    }
}