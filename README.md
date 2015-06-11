# PHP utils

[![Build Status](http://img.shields.io/travis/misantron/php-utils.svg?style=flat-square)](https://travis-ci.org/misantron/php-utils)
[![Code Coverage](http://img.shields.io/coveralls/misantron/php-utils.svg?style=flat-square)](https://coveralls.io/r/misantron/php-utils)
[![GitHub Release](http://img.shields.io/github/release/misantron/php-utils.svg?style=flat-square)](https://github.com/misantron/php-utils)
[![Packagist Version](http://img.shields.io/packagist/v/misantron/php-utils.svg?style=flat-square)](https://packagist.org/packages/misantron/php-utils)

## Features

- Contains Array, String and Time helper utilities.
- Work with PHP version >= 5.3.3 (HHVM is not tested)
- Base encoding - UTF-8
- PSR-4 auto loading standard compatible.

## Server requirements

- PHP version >= 5.3.3.
- MBString PHP extension.
- OpenSSL PHP extension.

## External dependencies

- [Slugify](https://github.com/cocur/slugify) library

## Installing

The preferred way to install is through [Composer](https://getcomposer.org).
Run this command to install the latest stable version:

```shell
$ composer require misantron/php-utils
```

or add

```json
"misantron/php-utils": "~1.0"
```

to the require section of your composer.json.

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

array(25, 21, 28);
```