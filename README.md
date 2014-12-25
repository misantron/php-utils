Array utils
=========

[![Build Status](https://travis-ci.org/misantron/php-utils.svg?branch=master)](https://travis-ci.org/misantron/php-utils)
[![Coverage Status](https://coveralls.io/repos/misantron/php-utils/badge.png?branch=master)](https://coveralls.io/r/misantron/php-utils?branch=master)

Collection of PHP array helper methods.

# Methods list

```php
get(&$arr, $key, $default = null) // Get selected value from array by the key 
extractColumn(&$arr, $columnKey, $indexKey = null, $preserveKeys = false) // Extract selected column from assoc array 
wrapKeys($arr, $prefix = '', $postfix = '') // Add prefix and postfix to array keys 
wrapValues() 
filterValues() 
searchKey() 
insertBefore() 
insertAfter() 
mergeRecursive() 
flatten() 
map() 
multisort() 
```