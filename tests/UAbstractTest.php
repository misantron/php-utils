<?php

use Utility\Exception\NonStaticCallException;
use Utility\UAbstract;

class UAbstractTest extends \PHPUnit_Framework_TestCase
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
            new UAbstract();
            $this->setExpectedException('\\Utility\\Exception\\NonStaticCallException');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }
}