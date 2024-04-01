<?php

namespace Pop\Utils\Test;

use Pop\Utils\ArrayObject;
use Pop\Utils\Test\TestAsset\MockIterator;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{

    public function testConstructor()
    {
        $arrayObject = new ArrayObject(['foo' => 'bar']);
        $this->assertInstanceOf('Pop\Utils\ArrayObject', $arrayObject);
    }

    public function testConstructorException()
    {
        $this->expectException('Pop\Utils\Exception');
        $arrayObject = new ArrayObject('bad');
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

    public function testToArray1()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject($ary);
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testToArray2()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject(new ArrayObject(['foo' => 'bar']));
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testToArray3()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject(new \ArrayObject(['foo' => 'bar'], \ArrayObject::ARRAY_AS_PROPS));
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testToArray4()
    {
        $ary = ['foo' => 'bar'];
        $arrayObject = new ArrayObject(new MockIterator(['foo' => 'bar']));
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testToArrayNested()
    {
        $arrayObject = new ArrayObject(['foo' => new ArrayObject(['bar' => 123])]);
        $ary = $arrayObject->toArray();
        $this->assertTrue(isset($ary['foo']));
        $this->assertTrue(isset($ary['foo']['bar']));
        $this->assertEquals(123, $ary['foo']['bar']);
    }

    public function testCount()
    {
        $arrayObject = new ArrayObject(['foo' => 'bar']);
        $this->assertEquals(1, count($arrayObject));
        $this->assertEquals(1, $arrayObject->count());
    }

    public function testSplit()
    {
        $arrayObject = ArrayObject::split('foo-bar', '-');
        $this->assertEquals('foo', $arrayObject[0]);
        $this->assertEquals('bar', $arrayObject[1]);
    }

    public function testJoin1()
    {
        $arrayObject = new ArrayObject(['foo', 'bar']);
        $this->assertEquals('foo-bar', $arrayObject->join('-'));
    }

    public function testJoin2()
    {
        $arrayObject = new ArrayObject([]);
        $this->assertEquals('', $arrayObject->join('-', '.'));
    }

    public function testJoin3()
    {
        $arrayObject = new ArrayObject(['foo']);
        $this->assertEquals('foo', $arrayObject->join('-', '.'));
    }

    public function testJoin4()
    {
        $arrayObject = new ArrayObject(['foo', 'bar', 'baz']);
        $this->assertEquals('foo-bar.baz', $arrayObject->join('-', '.'));
    }

    public function testKsort()
    {
        $arrayObject = new ArrayObject([2 => 3, 1 => 2, 0 => 1]);
        $arrayObject->ksort();
        $this->assertEquals(1, $arrayObject[0]);
        $this->assertEquals(2, $arrayObject[1]);
        $this->assertEquals(3, $arrayObject[2]);
    }

    public function testKsortDesc()
    {
        $arrayObject = new ArrayObject([3, 2, 1]);
        $arrayObject->ksortDesc();
        $this->assertEquals(3, $arrayObject[0]);
        $this->assertEquals(2, $arrayObject[1]);
        $this->assertEquals(1, $arrayObject[2]);
    }

    public function testUsort()
    {
         $arrayObject = new ArrayObject([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $arrayObject->usort(function($a, $b){
            return ($a > $b) ? -1 : 1;
        }, false);
        $this->assertEquals(2, $arrayObject->count());
        $this->assertEquals('Jane', $arrayObject[0]['name']);
    }

    public function testUasort()
    {
        $arrayObject = new ArrayObject([
            'a' => [
                'id'   => 1,
                'name' => 'John'
            ],
            'b' => [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $arrayObject->uksort(function($a, $b){
            return ($a > $b) ? -1 : 1;
        });
        $this->assertEquals(2, $arrayObject->count());
        $this->assertEquals('Jane', $arrayObject['b']['name']);
    }

    public function testUksort()
    {
        $arrayObject = new ArrayObject([
            'a' => [
                'id'   => 1,
                'name' => 'John'
            ],
            'b' => [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $arrayObject->usort(function($a, $b){
            return ($a > $b) ? -1 : 1;
        });
        $this->assertEquals(2, $arrayObject->count());
        $this->assertEquals('Jane', $arrayObject['b']['name']);
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
        $this->assertEquals('O:21:"Pop\Utils\ArrayObject":1:{s:3:"foo";s:3:"bar";}', $arrayObject->serialize(true));
        $arrayObject = ArrayObject::createFromSerialized('O:21:"Pop\Utils\ArrayObject":1:{s:3:"foo";s:3:"bar";}');
        $this->assertTrue(($ary === $arrayObject->toArray()));
    }

    public function testSerializeException()
    {
        $this->expectException('Pop\Utils\Exception');
        $arrayObject = ArrayObject::createFromSerialized('bad-string');
    }

}
