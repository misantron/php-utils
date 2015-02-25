# PHP utils

Collection of useful PHP utils.

[![Build Status](https://travis-ci.org/misantron/php-utils.svg?branch=master)](https://travis-ci.org/misantron/php-utils)
[![Code Coverage](http://img.shields.io/coveralls/misantron/php-utils.svg)](https://coveralls.io/r/misantron/php-utils)

## Features

- Contains Array, String and Time utilities.
- Work with PHP version >= 5.3.3 (HHVM is not tested)
- Base encoding - UTF-8
- PSR-0 auto loading standard compatible.

## Server requirements

- PHP version >= 5.3.3.
- PHP mbstring extension.

## External dependencies

- [Slugify](https://github.com/cocur/slugify) library

## Installation

You can install library through [Composer](https://getcomposer.org):

```shell
$ composer require misantron/php-utils
```

## Simple usage example

```php
use Utility\UArray;

$array = array(
    array('name' => 'Alex', 'age' => 25),
    array('name' => 'Sara', 'age' => 21),
    array('name' => 'John', 'age' => 28)
);

$result = UArray::extractColumn($array, 'age');

var_dump($result);

array(
    25,
    21,
    28
);
```