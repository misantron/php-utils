# PHP utils

[![Build Status](http://img.shields.io/travis/misantron/php-utils.svg?style=flat-square)](https://travis-ci.org/misantron/php-utils)
[![Code Coverage](http://img.shields.io/coveralls/misantron/php-utils.svg?style=flat-square)](https://coveralls.io/r/misantron/php-utils)
[![Code Climate](http://img.shields.io/codeclimate/github/misantron/php-utils.svg?style=flat-square)](https://codeclimate.com/github/misantron/php-utils)
[![Latest Stable Release](http://img.shields.io/github/release/misantron/php-utils.svg?style=flat-square)](https://github.com/misantron/php-utils)
[![License](http://img.shields.io/packagist/l/misantron/php-utils.svg?style=flat-square)](https://packagist.org/packages/misantron/php-utils)

## Features

- Contains Array, String and Time helper utilities.
- Base encoding - UTF-8
- PSR-4 auto loading standard compatible.

## Server requirements

- PHP version >= 5.4.
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
"misantron/php-utils": "dev-master"
```

to the require section of your composer.json.

## Basic usage examples

```php
use Utility\UArray;  
use Utility\UString;  
use Utility\UTime;

$array = [  
    ['name' => 'Alex', 'age' => 25],  
    ['name' => 'Sara', 'age' => 21],  
    ['name' => 'John', 'age' => 28]  
];  
$string = '..C’est du français !';  
$date1 = new \DateTime('2015-02-26 13:05');  
$date2 = new \DateTime('2015-02-26 22:16');  

$result1 = UArray::extractColumn($array, 'age');  
$result2 = UString::slug($string);  
$result3 = UTime::secondsDiff($date1, $date2);

var_dump($result1, $result2, $result3);

[25, 21, 28];  
'c-est-du-francais';  
33060;
```