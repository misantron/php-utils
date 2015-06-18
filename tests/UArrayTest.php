<?php

namespace Utility\Tests;

use Utility\Exception\InvalidArgumentException;
use Utility\Exception\NonStaticCallException;
use Utility\UArray;

/**
 * Class UArrayTest
 * @package Utility\Tests
 */
class UArrayTest extends TestCase
{
    public function testConstructor()
    {
        try {
            new UArray();
            $this->fail('Expected exception not thrown');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testGet()
    {
        $array = [];

        try {
            UArray::get($array, 'key');
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "key" not found.', $e->getMessage());
        }

        $result = UArray::get($array, 'key', 'default');
        $this->assertEquals('default', $result);

        $array = ['key1' => 1, 'key2' => 2];
        $result = UArray::get($array, 'key1', 'default');
        $this->assertEquals(1, $result);
    }

    public function testExtractColumn()
    {
        $array = [
            10 => ['key1' => 1, 'key2' => 2],
            20 => ['key1' => 3, 'key2' => 4],
        ];

        $result = UArray::extractColumn($array, 'key3');
        $expected = [];
        $this->assertEquals($expected, $result);

        $result = UArray::extractColumn($array, 'key1', true);
        $expected = [10 => 1, 20 => 3];
        $this->assertEquals($expected, $result);

        $result = UArray::extractColumn($array, 'key1', false);
        $expected = [0 => 1, 1 => 3];
        $this->assertEquals($expected, $result);
    }

    public function testWrapKeys()
    {
        $array = [
            'key1' => 1,
            'key2' => 2
        ];

        $result = UArray::wrapKeys($array);
        $this->assertEquals($array, $result);

        $result = UArray::wrapKeys($array, 'prefix:');
        $this->assertArrayHasKey('prefix:key1', $result);
        $this->assertArrayHasKey('prefix:key2', $result);

        $expected = [
            'prefix:key1' => 1,
            'prefix:key2' => 2,
        ];
        $this->assertEquals($expected, $result);

        $result = UArray::wrapKeys($array, false, ':postfix');
        $this->assertArrayHasKey('key1:postfix', $result);
        $this->assertArrayHasKey('key2:postfix', $result);

        $expected = [
            'key1:postfix' => 1,
            'key2:postfix' => 2,
        ];
        $this->assertEquals($expected, $result);

        $result = UArray::wrapKeys($array, 'prefix:', ':postfix');
        $this->assertArrayHasKey('prefix:key1:postfix', $result);
        $this->assertArrayHasKey('prefix:key2:postfix', $result);

        $expected = [
            'prefix:key1:postfix' => 1,
            'prefix:key2:postfix' => 2,
        ];
        $this->assertEquals($expected, $result);
    }

    public function testWrapValues()
    {
        $array = [
            'key1' => '1',
            'key2' => '2',
        ];

        $result = UArray::wrapValues($array);
        $this->assertEquals($array, $result);

        $result = UArray::wrapValues($array, 'prefix:');
        $expected = [
            'key1' => 'prefix:1',
            'key2' => 'prefix:2',
        ];
        $this->assertEquals($expected, $result);

        $result = UArray::wrapValues($array, false, ':postfix');
        $expected = [
            'key1' => '1:postfix',
            'key2' => '2:postfix',
        ];
        $this->assertEquals($expected, $result);

        $result = UArray::wrapValues($array, 'prefix:', ':postfix');
        $expected = [
            'key1' => 'prefix:1:postfix',
            'key2' => 'prefix:2:postfix',
        ];
        $this->assertEquals($expected, $result);
    }

    public function testFilterValues()
    {
        $array = [1, 3, 'val1', 'val2', 7, 14, 'val3', null, false];

        $result = UArray::filterValues($array, 'abc');
        $expected = [1, 3, 'val1', 'val2', 7, 14, 'val3'];
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_INTEGER, true);
        $expected = [0 => 1, 1 => 3, 4 => 7, 5 => 14];
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_INTEGER, false);
        $expected = [1, 3, 7, 14];
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_STRING, true);
        $expected = [2 => 'val1', 3 => 'val2', 6 => 'val3'];
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_STRING, false);
        $expected = ['val1', 'val2', 'val3'];
        $this->assertEquals($expected, $result);
    }

    public function testSearchKey()
    {
        $array = [
            1 => 'value1',
            2 => 'value2',
            '3' => 'value3',
            4 => 'value4',
        ];

        $result = UArray::searchKey($array, 5);
        $this->assertEquals(false, $result);

        $result = UArray::searchKey($array, 3);
        $this->assertEquals(2, $result);

        $result = UArray::searchKey($array, 1);
        $this->assertEquals(0, $result);
    }

    public function testInsertBefore()
    {
        $array = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        try {
            UArray::insertBefore($array, 'key5', 'value5');
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "key5" not found.', $e->getMessage());
        }

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            0 => 'value5',
            'key3' => 'value3',
        ];
        $result = UArray::insertBefore($array, 'key3', 'value5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertBefore($array, 'key3', ['value5']);
        $this->assertEquals($expected, $result);

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key5' => 'value5',
            'key3' => 'value3',
        ];
        $result = UArray::insertBefore($array, 'key3', 'value5', 'key5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertBefore($array, 'key3', ['value5'], 'key5');
        $this->assertEquals($expected, $result);
    }

    public function testInsertAfter()
    {
        $array = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            0 => 'value4',
        ];
        $result = UArray::insertAfter($array, 'key4', 'value4');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key4', ['value4']);
        $this->assertEquals($expected, $result);

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key5' => 'value4',
        ];
        $result = UArray::insertAfter($array, 'key4', 'value4', 'key5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key4', ['value4'], 'key5');
        $this->assertEquals($expected, $result);

        $expected = [
            'key1' => 'value1',
            0 => 'value4',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        $result = UArray::insertAfter($array, 'key1', 'value4');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key1', ['value4']);
        $this->assertEquals($expected, $result);

        $expected = [
            'key1' => 'value1',
            'key5' => 'value4',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        $result = UArray::insertAfter($array, 'key1', 'value4', 'key5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key1', ['value4'], 'key5');
        $this->assertEquals($expected, $result);
    }

    public function testMergeRecursive()
    {
        $array1 = [
            'key1' => 'value1',
            'key2' => [
                'key21' => [
                    'value21',
                    'value22',
                ],
                'value221'
            ]
        ];
        $array2 = [
            'key3' => 'value3',
            'key2' => [
                'value222',
                'value223',
                'key21' => [
                    'value23'
                ]
            ],
            'value4'
        ];
        $result = UArray::mergeRecursive($array1, $array2);
        $expected = [
            'key1' => 'value1',
            'key2' => [
                'key21' => [
                    'value21',
                    'value22',
                    'value23',
                ],
                'value221',
                'value222',
                'value223',
            ],
            'key3' => 'value3',
            'value4'
        ];
        $this->assertEquals($expected, $result);
    }

    public function testFlatten()
    {
        $array = [
            'key1' => 'value1',
            'key2' => [
                'value2',
                'value3'
            ]
        ];
        $expected = [
            'value1',
            'value2',
            'value3'
        ];
        $result = UArray::flatten($array);
        $this->assertEquals($expected, $result);
    }

    public function testMap()
    {
        $array = [
            ['key1' => 'value11', 'key2' => 'value12'],
            ['key1' => 'value21', 'key2' => 'value22'],
            ['key1' => 'value31', 'key2' => 'value32'],
       ];

        $result = UArray::map($array, 'key1', 'key3');
        $expected = [];
        $this->assertEquals($expected, $result);

        $result = UArray::map($array, 'key3', 'key2');
        $expected = [];
        $this->assertEquals($expected, $result);

        $result = UArray::map($array, 'key1', 'key2');
        $expected = [
            'value11' => 'value12',
            'value21' => 'value22',
            'value31' => 'value32'
        ];
        $this->assertEquals($expected, $result);

        $result = UArray::map($array, 'key2');
        $expected = [
            'value12' => ['key1' => 'value11', 'key2' => 'value12'],
            'value22' => ['key1' => 'value21', 'key2' => 'value22'],
            'value32' => ['key1' => 'value31', 'key2' => 'value32'],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testMultisortException()
    {
        $array = [];
        $keys = ['key1', 'key2'];

        try {
            UArray::multisort($array, $keys);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Params $arr or $key is invalid for sorting.', $e->getMessage());
        }

        $array = [1, 2, 3, 4];
        $keys = [];

        try {
            UArray::multisort($array, $keys);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Params $arr or $key is invalid for sorting.', $e->getMessage());
        }

        $keys = ['key1', 'key2'];
        $direction = [SORT_ASC];

        try {
            UArray::multisort($array, $keys, $direction);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('The length of $direction and $keys params must be equal.', $e->getMessage());
        }

        $direction = [SORT_ASC, SORT_DESC];
        $sortFlag = [SORT_REGULAR];

        try {
            UArray::multisort($array, $keys, $direction, $sortFlag);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('The length of $sortFlag and $keys params must be equal.', $e->getMessage());
        }
    }

    public function testMultisort()
    {
        $array = [
            ["firstname" => "Mary", "lastname" => "Johnson", "age" => 25],
            ["firstname" => "Amanda", "lastname" => "Miller", "age" => 18],
            ["firstname" => "James", "lastname" => "Brown", "age" => 31],
            ["firstname" => "Patricia", "lastname" => "Williams", "age" => 7],
        ];

        $keys = 'firstname';
        UArray::multisort($array, $keys);

        $expected = [
            ["firstname" => "Amanda", "lastname" => "Miller", "age" => 18],
            ["firstname" => "James", "lastname" => "Brown", "age" => 31],
            ["firstname" => "Mary", "lastname" => "Johnson", "age" => 25],
            ["firstname" => "Patricia", "lastname" => "Williams", "age" => 7],
        ];
        $this->assertEquals($expected, $array);

        $keys = ['firstname', 'age'];
        $direction = [SORT_DESC, SORT_ASC];
        $sortFlag = [SORT_REGULAR, SORT_REGULAR];
        UArray::multisort($array, $keys, $direction, $sortFlag);

        $expected = [
            ["firstname" => "Patricia", "lastname" => "Williams", "age" => 7],
            ["firstname" => "Mary", "lastname" => "Johnson", "age" => 25],
            ["firstname" => "James", "lastname" => "Brown", "age" => 31],
            ["firstname" => "Amanda", "lastname" => "Miller", "age" => 18],
        ];
        $this->assertEquals($expected, $array);
    }

    public function testFirstKey()
    {
        $example = ['key1' => 45, 'red', 'flower', 5 => 'flatten', 'key3' => '45'];
        $result = UArray::firstKey($example);
        $this->assertEquals('key1', $result);
    }

    public function testLastKey()
    {
        $example = ['key1' => 45, 'red', 'flower', 'key3' => '45', 5 => 'flatten'];
        $result = UArray::lastKey($example);
        $this->assertEquals(5, $result);
    }

    public function testIsAssoc()
    {
        $example = [45, 'red', 'flower'];
        $result = UArray::isAssoc($example);
        $this->assertFalse($result);

        $example = ['key1' => 45, 'key3' => '45'];
        $result = UArray::isAssoc($example);
        $this->assertTrue($result);

        $example = ['key1' => 45, 'red', 'flower', 'key3' => '45', 5 => 'flatten'];
        $result = UArray::isAssoc($example);
        $this->assertTrue($result);
    }

    public function testLoadTranslations()
    {
        try {
            static::callMethod('\\Utility\\UString', 'loadTranslations', ['someMethod']);
            $this->fail('Expected exception not thrown');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Can not load translation for method.', $e->getMessage());
        }
    }
}