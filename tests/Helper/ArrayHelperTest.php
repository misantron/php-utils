<?php

namespace Utility\Test\Helper;

use Utility\Helper\ArrayHelper;

class ArrayHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $array = array();

        try {
            ArrayHelper::get($array, 'key');
            $this->setExpectedException('\InvalidArgumentException');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "key" not found.', $e->getMessage());
        }

        $result = ArrayHelper::get($array, 'key', 'default');
        $this->assertEquals('default', $result);

        $array = array('key1' => 1, 'key2' => 2);
        $result = ArrayHelper::get($array, 'key1', 'default');
        $this->assertEquals(1, $result);
    }

    public function testExtractColumn()
    {
        $array = false;
        $result = ArrayHelper::extractColumn($array, 'key1');
        $this->isNull($result);

        $array = '';
        $result = ArrayHelper::extractColumn($array, 'key1');
        $this->isNull($result);

        $array = array();
        $result = ArrayHelper::extractColumn($array, 'key1');
        $this->isNull($result);

        $array = array(
            10 => array('key1' => 1, 'key2' => 2),
            20 => array('key1' => 3, 'key2' => 4),
        );

        $result = ArrayHelper::extractColumn($array, 'key3');
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::extractColumn($array, 'key1', true);
        $expected = array(10 => 1, 20 => 3);
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::extractColumn($array, 'key1', false);
        $expected = array(0 => 1, 1 => 3);
        $this->assertEquals($expected, $result);
    }

    public function testWrapKeys()
    {
        $array = array(
            'key1' => 1,
            'key2' => 2,
        );

        $result = ArrayHelper::wrapKeys($array);
        $this->assertEquals($array, $result);

        $result = ArrayHelper::wrapKeys($array, 'prefix:');
        $this->assertArrayHasKey('prefix:key1', $result);
        $this->assertArrayHasKey('prefix:key2', $result);

        $expected = array(
            'prefix:key1' => 1,
            'prefix:key2' => 2,
        );
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::wrapKeys($array, false, ':postfix');
        $this->assertArrayHasKey('key1:postfix', $result);
        $this->assertArrayHasKey('key2:postfix', $result);

        $expected = array(
            'key1:postfix' => 1,
            'key2:postfix' => 2,
        );
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::wrapKeys($array, 'prefix:', ':postfix');
        $this->assertArrayHasKey('prefix:key1:postfix', $result);
        $this->assertArrayHasKey('prefix:key2:postfix', $result);

        $expected = array(
            'prefix:key1:postfix' => 1,
            'prefix:key2:postfix' => 2,
        );
        $this->assertEquals($expected, $result);
    }

    public function testWrapValues()
    {
        $array = array(
            'key1' => '1',
            'key2' => '2',
        );

        $result = ArrayHelper::wrapValues($array);
        $this->assertEquals($array, $result);

        $result = ArrayHelper::wrapValues($array, 'prefix:');
        $expected = array(
            'key1' => 'prefix:1',
            'key2' => 'prefix:2',
        );
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::wrapValues($array, false, ':postfix');
        $expected = array(
            'key1' => '1:postfix',
            'key2' => '2:postfix',
        );
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::wrapValues($array, 'prefix:', ':postfix');
        $expected = array(
            'key1' => 'prefix:1:postfix',
            'key2' => 'prefix:2:postfix',
        );
        $this->assertEquals($expected, $result);
    }

    public function testFilterValues()
    {
        $array = array(1, 3, 'val1', 'val2', 7, 14, 'val3', null, false);

        $result = ArrayHelper::filterValues($array, 'abc');
        $expected = array(1, 3, 'val1', 'val2', 7, 14, 'val3');
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::filterValues($array, ArrayHelper::TYPE_INTEGER, true);
        $expected = array(0 => 1, 1 => 3, 4 => 7, 5 => 14);
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::filterValues($array, ArrayHelper::TYPE_INTEGER, false);
        $expected = array(1, 3, 7, 14);
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::filterValues($array, ArrayHelper::TYPE_STRING, true);
        $expected = array(2 => 'val1', 3 => 'val2', 6 => 'val3');
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::filterValues($array, ArrayHelper::TYPE_STRING, false);
        $expected = array('val1', 'val2', 'val3',);
        $this->assertEquals($expected, $result);
    }

    public function testSearchKey()
    {
        $array = array(
            1 => 'value1',
            2 => 'value2',
            '3' => 'value3',
            4 => 'value4',
        );

        $result = ArrayHelper::searchKey($array, 5);
        $this->assertEquals(false, $result);

        $result = ArrayHelper::searchKey($array, 3);
        $this->assertEquals(2, $result);

        $result = ArrayHelper::searchKey($array, 1);
        $this->assertEquals(0, $result);
    }

    public function testInsertBefore()
    {
        $array = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        );

        try {
            ArrayHelper::insertBefore($array, 'key5', 'value5');
            $this->setExpectedException('\InvalidArgumentException');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "key5" not found.', $e->getMessage());
        }

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            0 => 'value5',
            'key3' => 'value3',
        );
        $result = ArrayHelper::insertBefore($array, 'key3', 'value5');
        $this->assertEquals($expected, $result);
        $result = ArrayHelper::insertBefore($array, 'key3', array('value5'));
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key5' => 'value5',
            'key3' => 'value3',
        );
        $result = ArrayHelper::insertBefore($array, 'key3', 'value5', 'key5');
        $this->assertEquals($expected, $result);
        $result = ArrayHelper::insertBefore($array, 'key3', array('value5'), 'key5');
        $this->assertEquals($expected, $result);
    }

    public function testInsertAfter()
    {
        $array = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        );

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            0 => 'value4',
        );
        $result = ArrayHelper::insertAfter($array, 'key4', 'value4');
        $this->assertEquals($expected, $result);
        $result = ArrayHelper::insertAfter($array, 'key4', array('value4'));
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key5' => 'value4',
        );
        $result = ArrayHelper::insertAfter($array, 'key4', 'value4', 'key5');
        $this->assertEquals($expected, $result);
        $result = ArrayHelper::insertAfter($array, 'key4', array('value4'), 'key5');
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            0 => 'value4',
            'key2' => 'value2',
            'key3' => 'value3',
        );
        $result = ArrayHelper::insertAfter($array, 'key1', 'value4');
        $this->assertEquals($expected, $result);
        $result = ArrayHelper::insertAfter($array, 'key1', array('value4'));
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            'key5' => 'value4',
            'key2' => 'value2',
            'key3' => 'value3',
        );
        $result = ArrayHelper::insertAfter($array, 'key1', 'value4', 'key5');
        $this->assertEquals($expected, $result);
        $result = ArrayHelper::insertAfter($array, 'key1', array('value4'), 'key5');
        $this->assertEquals($expected, $result);
    }

    public function testMergeRecursive()
    {
        $array1 = array(
            'key1' => 'value1',
            'key2' => array(
                'key21' => array(
                    'value21',
                    'value22',
                ),
                'value221'
            )
        );
        $array2 = array(
            'key3' => 'value3',
            'key2' => array(
                'value222',
                'value223',
                'key21' => array(
                    'value23'
                )
            )
        );
        $result = ArrayHelper::mergeRecursive($array1, $array2);
        $expected = array(
            'key1' => 'value1',
            'key2' => array(
                'key21' => array(
                    'value21',
                    'value22',
                    'value23',
                ),
                'value221',
                'value222',
                'value223',
            ),
            'key3' => 'value3',
        );
        $this->assertEquals($expected, $result);
    }

    public function testFlatten()
    {
        $array = array(
            'key1' => 'value1',
            'key2' => array(
                'value2',
                'value3'
            )
        );
        $expected = array(
            'value1',
            'value2',
            'value3'
        );
        $result = ArrayHelper::flatten($array);
        $this->assertEquals($expected, $result);
    }

    public function testMap()
    {
        $array = array(
            array('key1' => 'value11', 'key2' => 'value12'),
            array('key1' => 'value21', 'key2' => 'value22'),
            array('key1' => 'value31', 'key2' => 'value32'),
        );

        $result = ArrayHelper::map($array, 'key1', 'key3');
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::map($array, 'key3', 'key2');
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = ArrayHelper::map($array, 'key1', 'key2');
        $expected = array(
            'value11' => 'value12',
            'value21' => 'value22',
            'value31' => 'value32'
        );
        $this->assertEquals($expected, $result);
    }

    public function testMultisort()
    {
        $array = array(1, 2, 3, 4);
        $keys = array('key1', 'key2');
        $direction = array(SORT_ASC);

        try {
            ArrayHelper::multisort($array, $keys, $direction);
            $this->setExpectedException('\InvalidArgumentException');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\InvalidArgumentException', $e);
            $this->assertEquals('The length of $direction and $keys params must be equal.', $e->getMessage());
        }

        $direction = array(SORT_ASC, SORT_DESC);
        $sortFlag = array(SORT_REGULAR);

        try {
            ArrayHelper::multisort($array, $keys, $direction, $sortFlag);
            $this->setExpectedException('\InvalidArgumentException');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\InvalidArgumentException', $e);
            $this->assertEquals('The length of $sortFlag and $keys params must be equal.', $e->getMessage());
        }
    }
}