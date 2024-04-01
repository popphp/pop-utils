<?php

namespace Pop\Utils\Test;

use Pop\Utils\Arr;
use PHPUnit\Framework\TestCase;
use Pop\Utils\ArrayObject;

class ArrTest extends TestCase
{

    public function testIsMethods()
    {
        $array = ['foo', 'bar'];
        $this->assertTrue(Arr::isArray($array));
        $this->assertFalse(Arr::isAssoc($array));
        $this->assertTrue(Arr::isNumeric($array));
    }

    public function testExists1()
    {
        $array = ['foo' => 'bar'];
        $this->assertTrue(Arr::exists($array, 'foo'));
    }

    public function testExists2()
    {
        $array = new ArrayObject(['foo' => 'bar']);
        $this->assertTrue(Arr::exists($array, 'foo'));
    }

    public function testKey1()
    {
        $array = ['foo' => 'bar'];
        $this->assertEquals('foo', Arr::key($array, 'bar'));
    }

    public function testKey2()
    {
        $array = new ArrayObject(['foo' => 'bar']);
        $this->assertEquals('foo', Arr::key($array, 'bar'));
    }

    public function testCollapse()
    {
        $array = [
            ['foo' => 'bar'],
            ['baz' => 123, 'blah' => 456],
            'test',
            new ArrayObject(['something' => 'else'])
        ];

        $this->assertCount(4, $array);

        $collapsed = Arr::collapse($array);

        $this->assertCount(4, $collapsed);
        $this->assertEquals('bar', $collapsed['foo']);
        $this->assertEquals(123, $collapsed['baz']);
        $this->assertEquals(456, $collapsed['blah']);
        $this->assertEquals('else', $collapsed['something']);
    }

    public function testFlatten()
    {
        $array = [
            ['foo' => 'bar'],
            ['baz' => 123, 'blah' => 456],
            'test',
            new ArrayObject(['something' => 'else'])
        ];

        $this->assertCount(4, $array);

        $flatten = Arr::flatten($array);

        $this->assertCount(5, $flatten);
        $this->assertEquals('bar', $flatten[0]);
        $this->assertEquals(123, $flatten[1]);
        $this->assertEquals(456, $flatten[2]);
        $this->assertEquals('test', $flatten[3]);
        $this->assertEquals('else', $flatten[4]);
    }

    public function testDivide()
    {
        $array = new ArrayObject(['foo' => 'bar', 'bar' => 123]);
        [$keys, $values] = Arr::divide($array);
        $this->assertEquals(count($keys), count($values));
        $this->assertEquals('foo', $keys[0]);
        $this->assertEquals('bar', $keys[1]);
        $this->assertEquals('bar', $values[0]);
        $this->assertEquals(123, $values[1]);
    }

    public function testSlice1()
    {
        $array  = new ArrayObject([1, 2, 3, 4, 5, 6]);
        $sliced = Arr::slice($array, 2);

        $this->assertCount(2, $sliced);
        $this->assertEquals(1, $sliced[0]);
        $this->assertEquals(2, $sliced[1]);
    }

    public function testSlice2()
    {
        $array  = new ArrayObject([1, 2, 3, 4, 5, 6]);
        $sliced = Arr::slice($array, -2);

        $this->assertCount(2, $sliced);
        $this->assertEquals(5, $sliced[0]);
        $this->assertEquals(6, $sliced[1]);
    }

    public function testSplit()
    {
        $array = Arr::split('some-string', '-');
        $this->assertCount(2, $array);
        $this->assertEquals('some', $array[0]);
        $this->assertEquals('string', $array[1]);
    }

    public function testJoin1()
    {
        $array = new ArrayObject(['foo', 'bar']);
        $string = Arr::join($array, '-');
        $this->assertEquals('foo-bar', $string);
    }

    public function testJoin2()
    {
        $array = new ArrayObject(['foo', 'bar', 'baz']);
        $string = Arr::join($array, '-', '.');
        $this->assertEquals('foo-bar.baz', $string);
    }

    public function testJoin3()
    {
        $array = new ArrayObject([]);
        $string = Arr::join($array, '-', '.');
        $this->assertEquals('', $string);
    }

    public function testJoin4()
    {
        $array = new ArrayObject(['foo']);
        $string = Arr::join($array, '-', '.');
        $this->assertEquals('foo', $string);
    }

    public function testPrepend1()
    {
        $array = new ArrayObject(['foo', 'bar', 'baz']);
        $array = Arr::prepend($array, 123);
        $this->assertCount(4, $array);
        $this->assertEquals(123, $array[0]);
    }

    public function testPrepend2()
    {
        $array = new ArrayObject(['foo', 'bar', 'baz']);
        $array = Arr::prepend($array, 123, 'test');
        $this->assertCount(4, $array);
        $this->assertEquals(123, $array['test']);
    }

    public function testPull()
    {
        $array = ['foo', 'bar', 'baz'];
        $value = Arr::pull($array, 1);
        $this->assertCount(2, $array);
        $this->assertEquals('bar', $value);
        $this->assertFalse(isset($array[1]));
    }

    public function testSort()
    {
        $array = new ArrayObject(['foo', 'bar', 'baz']);
        $array = Arr::sort($array);
        $this->assertEquals('foo', array_pop($array));
        $this->assertEquals('baz', array_pop($array));
        $this->assertEquals('bar', array_pop($array));
    }

    public function testSortDesc()
    {
        $array = new ArrayObject(['foo', 'bar', 'baz']);
        $array = Arr::sortDesc($array);
        $this->assertEquals('bar', array_pop($array));
        $this->assertEquals('baz', array_pop($array));
        $this->assertEquals('foo', array_pop($array));
    }

    public function testKsort()
    {
        $array = new ArrayObject(['foo' => 1, 'bar' => 2, 'baz' => 3]);
        $array = Arr::ksort($array);
        $this->assertEquals(1, array_pop($array));
        $this->assertEquals(3, array_pop($array));
        $this->assertEquals(2, array_pop($array));
    }

    public function testKSortDesc()
    {
        $array = new ArrayObject(['foo' => 1, 'bar' => 2, 'baz' => 3]);
        $array = Arr::ksortDesc($array);
        $this->assertEquals(2, array_pop($array));
        $this->assertEquals(3, array_pop($array));
        $this->assertEquals(1, array_pop($array));
    }

    public function testUsort()
    {
        $array = new ArrayObject([3, 2, 1]);
        $array = Arr::usort($array, function($a, $b){
            return ($a < $b) ? -1 : 1;
        }, false);
        $this->assertEquals(3, array_pop($array));
        $this->assertEquals(2, array_pop($array));
        $this->assertEquals(1, array_pop($array));
    }

    public function testUsortAssoc()
    {
        $array = new ArrayObject([3, 2, 1]);
        $array = Arr::usort($array, function($a, $b){
            return ($a < $b) ? -1 : 1;
        });
        $this->assertEquals(3, array_pop($array));
        $this->assertEquals(2, array_pop($array));
        $this->assertEquals(1, array_pop($array));
    }

    public function testUksort()
    {
        $array = new ArrayObject(['foo' => 1, 'bar' => 2, 'baz' => 3]);
        $array = Arr::uksort($array, function($a, $b){
            return ($a < $b) ? -1 : 1;
        });
        $this->assertEquals(1, array_pop($array));
        $this->assertEquals(3, array_pop($array));
        $this->assertEquals(2, array_pop($array));
    }

    public function testMap()
    {
        $array  = new ArrayObject([' foo ', '  bar', 'baz   ']);
        $array1 = Arr::map($array, 'trim');
        $array2 = Arr::trim($array);

        $this->assertEquals('foo', $array1[0]);
        $this->assertEquals('foo', $array2[0]);
        $this->assertEquals('bar', $array1[1]);
        $this->assertEquals('bar', $array2[1]);
        $this->assertEquals('baz', $array1[2]);
        $this->assertEquals('baz', $array2[2]);
    }

    public function testFilter()
    {
        $array = new ArrayObject(['foo', '', null, 'baz']);
        $array = Arr::filter($array);
        $this->assertCount(2, $array);
        $this->assertEquals('foo', $array[0]);
        $this->assertEquals('baz', $array[3]);
    }

    public function testMake()
    {
        $value = 123;
        $array = Arr::make($value);
        $this->assertIsArray($array);
        $this->assertEquals(123, $array[0]);
    }

}
