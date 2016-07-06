<?php

namespace Utility\Tests;

date_default_timezone_set('UTC');

/**
 * Class TestCase
 * @package Utility\Tests
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
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