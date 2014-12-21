<?php

namespace Utility\Test\Exception;

use Utility\Exception\StaticClassException;

class StaticClassExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException StaticClassException
     * @expectedExceptionMessage Can not use class in non static context.
     */
    public function testExceptionHasRightMessage()
    {
        throw new StaticClassException('Can not use class in non static context.');
    }
}