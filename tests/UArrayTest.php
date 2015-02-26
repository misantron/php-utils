<?php

use Utility\Exception\InvalidArgumentException;
use Utility\Exception\NonStaticCallException;
use Utility\UArray;

class UArrayTest extends \PHPUnit_Framework_TestCase
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
            new UArray();
            $this->setExpectedException('\\Utility\\Exception\\NonStaticCallException');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testGet()
    {
        $array = array();

        try {
            UArray::get($array, 'key');
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "key" not found.', $e->getMessage());
        }

        $result = UArray::get($array, 'key', 'default');
        $this->assertEquals('default', $result);

        $array = array('key1' => 1, 'key2' => 2);
        $result = UArray::get($array, 'key1', 'default');
        $this->assertEquals(1, $result);
    }

    public function testExtractColumn()
    {
        $array = array(
            10 => array('key1' => 1, 'key2' => 2),
            20 => array('key1' => 3, 'key2' => 4),
        );

        $result = UArray::extractColumn($array, 'key3');
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = UArray::extractColumn($array, 'key1', true);
        $expected = array(10 => 1, 20 => 3);
        $this->assertEquals($expected, $result);

        $result = UArray::extractColumn($array, 'key1', false);
        $expected = array(0 => 1, 1 => 3);
        $this->assertEquals($expected, $result);
    }

    public function testWrapKeys()
    {
        $array = array(
            'key1' => 1,
            'key2' => 2,
        );

        $result = UArray::wrapKeys($array);
        $this->assertEquals($array, $result);

        $result = UArray::wrapKeys($array, 'prefix:');
        $this->assertArrayHasKey('prefix:key1', $result);
        $this->assertArrayHasKey('prefix:key2', $result);

        $expected = array(
            'prefix:key1' => 1,
            'prefix:key2' => 2,
        );
        $this->assertEquals($expected, $result);

        $result = UArray::wrapKeys($array, false, ':postfix');
        $this->assertArrayHasKey('key1:postfix', $result);
        $this->assertArrayHasKey('key2:postfix', $result);

        $expected = array(
            'key1:postfix' => 1,
            'key2:postfix' => 2,
        );
        $this->assertEquals($expected, $result);

        $result = UArray::wrapKeys($array, 'prefix:', ':postfix');
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

        $result = UArray::wrapValues($array);
        $this->assertEquals($array, $result);

        $result = UArray::wrapValues($array, 'prefix:');
        $expected = array(
            'key1' => 'prefix:1',
            'key2' => 'prefix:2',
        );
        $this->assertEquals($expected, $result);

        $result = UArray::wrapValues($array, false, ':postfix');
        $expected = array(
            'key1' => '1:postfix',
            'key2' => '2:postfix',
        );
        $this->assertEquals($expected, $result);

        $result = UArray::wrapValues($array, 'prefix:', ':postfix');
        $expected = array(
            'key1' => 'prefix:1:postfix',
            'key2' => 'prefix:2:postfix',
        );
        $this->assertEquals($expected, $result);
    }

    public function testFilterValues()
    {
        $array = array(1, 3, 'val1', 'val2', 7, 14, 'val3', null, false);

        $result = UArray::filterValues($array, 'abc');
        $expected = array(1, 3, 'val1', 'val2', 7, 14, 'val3');
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_INTEGER, true);
        $expected = array(0 => 1, 1 => 3, 4 => 7, 5 => 14);
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_INTEGER, false);
        $expected = array(1, 3, 7, 14);
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_STRING, true);
        $expected = array(2 => 'val1', 3 => 'val2', 6 => 'val3');
        $this->assertEquals($expected, $result);

        $result = UArray::filterValues($array, UArray::TYPE_STRING, false);
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

        $result = UArray::searchKey($array, 5);
        $this->assertEquals(false, $result);

        $result = UArray::searchKey($array, 3);
        $this->assertEquals(2, $result);

        $result = UArray::searchKey($array, 1);
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
            UArray::insertBefore($array, 'key5', 'value5');
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "key5" not found.', $e->getMessage());
        }

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            0 => 'value5',
            'key3' => 'value3',
        );
        $result = UArray::insertBefore($array, 'key3', 'value5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertBefore($array, 'key3', array('value5'));
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key5' => 'value5',
            'key3' => 'value3',
        );
        $result = UArray::insertBefore($array, 'key3', 'value5', 'key5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertBefore($array, 'key3', array('value5'), 'key5');
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
        $result = UArray::insertAfter($array, 'key4', 'value4');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key4', array('value4'));
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key5' => 'value4',
        );
        $result = UArray::insertAfter($array, 'key4', 'value4', 'key5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key4', array('value4'), 'key5');
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            0 => 'value4',
            'key2' => 'value2',
            'key3' => 'value3',
        );
        $result = UArray::insertAfter($array, 'key1', 'value4');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key1', array('value4'));
        $this->assertEquals($expected, $result);

        $expected = array(
            'key1' => 'value1',
            'key5' => 'value4',
            'key2' => 'value2',
            'key3' => 'value3',
        );
        $result = UArray::insertAfter($array, 'key1', 'value4', 'key5');
        $this->assertEquals($expected, $result);
        $result = UArray::insertAfter($array, 'key1', array('value4'), 'key5');
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
        $result = UArray::mergeRecursive($array1, $array2);
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
        $result = UArray::flatten($array);
        $this->assertEquals($expected, $result);
    }

    public function testMap()
    {
        $array = array(
            array('key1' => 'value11', 'key2' => 'value12'),
            array('key1' => 'value21', 'key2' => 'value22'),
            array('key1' => 'value31', 'key2' => 'value32'),
        );

        $result = UArray::map($array, 'key1', 'key3');
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = UArray::map($array, 'key3', 'key2');
        $expected = array();
        $this->assertEquals($expected, $result);

        $result = UArray::map($array, 'key1', 'key2');
        $expected = array(
            'value11' => 'value12',
            'value21' => 'value22',
            'value31' => 'value32'
        );
        $this->assertEquals($expected, $result);

        $result = UArray::map($array, 'key2');
        $expected = array(
            'value12' => array('key1' => 'value11', 'key2' => 'value12'),
            'value22' => array('key1' => 'value21', 'key2' => 'value22'),
            'value32' => array('key1' => 'value31', 'key2' => 'value32'),
        );
        $this->assertEquals($expected, $result);
    }

    public function testMultisortException()
    {
        $array = array();
        $keys = array('key1', 'key2');

        try {
            UArray::multisort($array, $keys);
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Params $arr or $key is invalid for sorting.', $e->getMessage());
        }

        $array = array(1, 2, 3, 4);
        $keys = array();

        try {
            UArray::multisort($array, $keys);
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('Params $arr or $key is invalid for sorting.', $e->getMessage());
        }

        $keys = array('key1', 'key2');
        $direction = array(SORT_ASC);

        try {
            UArray::multisort($array, $keys, $direction);
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('The length of $direction and $keys params must be equal.', $e->getMessage());
        }

        $direction = array(SORT_ASC, SORT_DESC);
        $sortFlag = array(SORT_REGULAR);

        try {
            UArray::multisort($array, $keys, $direction, $sortFlag);
            $this->setExpectedException('\\Utility\\Exception\\InvalidArgumentException');
        } catch(InvalidArgumentException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\InvalidArgumentException', $e);
            $this->assertEquals('The length of $sortFlag and $keys params must be equal.', $e->getMessage());
        }
    }

    public function testMultisort()
    {
        $array = array(
            array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25),
            array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18),
            array("firstname" => "James", "lastname" => "Brown", "age" => 31),
            array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7),
        );

        $keys = 'firstname';
        UArray::multisort($array, $keys);

        $expected = array(
            array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18),
            array("firstname" => "James", "lastname" => "Brown", "age" => 31),
            array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25),
            array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7),
        );
        $this->assertEquals($expected, $array);

        $keys = array('firstname', 'age');
        $direction = array(SORT_DESC, SORT_ASC);
        $sortFlag = array(SORT_REGULAR, SORT_REGULAR);
        UArray::multisort($array, $keys, $direction, $sortFlag);

        $expected = array(
            array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7),
            array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25),
            array("firstname" => "James", "lastname" => "Brown", "age" => 31),
            array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18),
        );
        $this->assertEquals($expected, $array);
    }

    public function testLoadTranslations()
    {
        $result = self::callMethod('\\Utility\\UArray', 'loadTranslations');
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
    }

    /**
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