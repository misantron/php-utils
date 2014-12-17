<?php

namespace Utils\Exception;

class StaticClassException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Can not use class in non static context.');
    }
}