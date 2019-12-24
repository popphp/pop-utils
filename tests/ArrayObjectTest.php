<?php

namespace Pop\Utils\Test;

use Pop\Utils\ArrayObject;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{

    public function testConstructor()
    {
        $arrayObject = new ArrayObject(['foo' => 'bar']);
        $this->assertInstanceOf('Pop\Utils\ArrayObject', $arrayObject);
    }

    public function testGettersAndSetters()
    {
        $arrayObject = new ArrayObject(['foo' => 'bar']);
        $this->assertEquals('bar', $arrayObject->foo);
        $this->assertEquals('bar', $arrayObject['foo']);
        $this->assertTrue(isset($arrayObject->foo));
        $this->assertTrue(isset($arrayObject['foo']));
        unset($arrayObject['foo']);
        $arrayObject->foo = '123';
        $this->assertEquals('123', $arrayObject['foo']);
        unset($arrayObject->foo);
        $arrayObject['foo'] = '456';
        $this->assertEquals('456', $arrayObject['foo']);
        unset($arrayObject->foo);
        $this->assertFalse(isset($arrayObject->foo));
        $this->assertFalse(isset($arrayObject['foo']));
    }

    public function testToArray()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject($ary);
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testCount()
    {
        $arrayObject = new ArrayObject(['foo' => 'bar']);
        $this->assertEquals(1, count($arrayObject));
        $this->assertEquals(1, $arrayObject->count());
    }

    public function testIterator()
    {
        $arrayObject = new ArrayObject(['foo' => 'bar']);
        $string = '';
        foreach ($arrayObject as $key => $value) {
            $string = $key . $value;
        }
        $this->assertEquals('foobar', $string);
    }

    public function testJsonSerialize()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject($ary);
        $this->assertEquals('{"foo":"bar"}', $arrayObject->jsonSerialize());
        $arrayObject = ArrayObject::createFromJson('{"foo":"bar"}');
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testSerialize()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject($ary);
        $this->assertEquals('a:1:{s:3:"foo";s:3:"bar";}', $arrayObject->serialize());
        $arrayObject = ArrayObject::createFromSerialized('a:1:{s:3:"foo";s:3:"bar";}');
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testSerializeAsObject()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject($ary);
        $this->assertEquals('C:21:"Pop\Utils\ArrayObject":26:{a:1:{s:3:"foo";s:3:"bar";}}', $arrayObject->serialize(true));
        $arrayObject = ArrayObject::createFromSerialized('C:21:"Pop\Utils\ArrayObject":26:{a:1:{s:3:"foo";s:3:"bar";}}');
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testSerializeException()
    {
        $this->expectException('Pop\Utils\Exception');
        $arrayObject = ArrayObject::createFromSerialized('bad-string');
    }

}