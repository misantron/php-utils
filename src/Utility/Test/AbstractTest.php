<?php

namespace Utility\Test;

class AbstractTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $vendorAutoloadPath = dirname(__FILE__) . '/../../../vendor/autoload.php';
        if (file_exists($vendorAutoloadPath)) {
            require_once $vendorAutoloadPath;
        }
    }
}