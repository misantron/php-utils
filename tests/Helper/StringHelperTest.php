<?php

namespace Utility\Test\Helper;

use Utility\Helper\StringHelper;

class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testCut()
    {

    }

    public function testPlural()
    {

    }

    public function testGenerateRandom()
    {
        $string = StringHelper::generateRandom();
        $this->assertEquals(10, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{10}$/', $string);

        $string = StringHelper::generateRandom(17);
        $this->assertEquals(17, strlen($string));
        $this->assertRegExp('/^[a-zA-Z0-9]{17}$/', $string);
    }
}