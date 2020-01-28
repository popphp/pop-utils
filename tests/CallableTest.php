<?php

namespace Pop\Utils\Test;

use Pop\Utils\CallableObject;
use PHPUnit\Framework\TestCase;

class CallableTest extends TestCase
{

    public function testConstructor()
    {
        $callable = new CallableObject('trim', ' Hello World! ');
        $this->assertInstanceOf('Pop\Utils\CallableObject', $callable);
        $this->assertEquals('trim', $callable->getCallable());
        $this->assertEquals(' Hello World! ', $callable->getParameter(0));
        $this->assertNull($callable->getCallableType());
        $this->assertTrue($callable->isCallable());
        $this->assertFalse($callable->wasCalled());
    }

    public function testParameters()
    {
        $callable = new CallableObject('some_function', [123, 456]);
        $this->assertTrue($callable->hasParameters());
        $this->assertEquals(2, count($callable->getParameters()));
        $callable->addNamedParameter('foo', 'bar');
        $this->assertTrue($callable->hasParameter('foo'));
        $this->assertEquals(3, count($callable->getParameters()));
        $this->assertEquals(123, $callable->getParameter(0));
        $this->assertEquals(456, $callable->getParameter(1));
        $this->assertEquals('bar', $callable->getParameter('foo'));
        $callable->removeParameter('foo');
        $this->assertFalse($callable->hasParameter('foo'));
        $this->assertEquals(2, count($callable->getParameters()));
        $callable->removeParameters();
        $this->assertFalse($callable->hasParameters());
        $callable->addParameters([789, 'test' => 987]);
        $this->assertTrue($callable->hasParameters());
        $this->assertEquals(2, count($callable->getParameters()));
    }

    public function testConstructorParams()
    {
        $callable = new CallableObject('Foo');
        $callable->setConstructorParams([123, 456]);
        $this->assertTrue($callable->hasConstructorParams());
        $this->assertEquals(2, count($callable->getConstructorParams()));
        $this->assertEquals(123, $callable->getConstructorParam(0));
        $this->assertEquals(456, $callable->getConstructorParam(1));
        $callable->removeConstructorParam(1);
        $this->assertFalse($callable->hasConstructorParam(1));
        $this->assertEquals(1, count($callable->getConstructorParams()));
        $callable->removeConstructorParams();
        $this->assertFalse($callable->hasConstructorParams());
    }

    public function testFunctionCall()
    {
        $callable1 = new CallableObject('trim', ' Hello World! ');
        $this->assertEquals('Hello World!', $callable1->call());
        $callable2 = new CallableObject('uniqid');
        $this->assertEquals(13, strlen($callable2->call()));
    }

    public function testClosureCall()
    {
        $callable = new CallableObject(function($name) {
            return 'This is another way to say hello, ' . $name .'!';
        }, 'Nick');
        $this->assertEquals('This is another way to say hello, Nick!', $callable->call());
    }

    public function testStaticCall()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass::sayHello', 'Nick');
        $this->assertTrue($callable->isCallable());
        $this->assertEquals('Hello, Nick', $callable->call());
    }

    public function testInstanceCall()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass->printFoo');
        $callable->setConstructorParams(['foo' => 'HI!']);
        $this->assertEquals('HI!', $callable->call());
    }

    public function testInstanceCallWithParams1()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass->setFoo', ['foo' => 'HI!']);
        $result = $callable->call();
        $this->assertEquals('HI!', $result->getFoo());
    }

    public function testInstanceCallWithParams2()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass->addFoo', ['foo' => 'HOW ARE YOU?']);
        $callable->setConstructorParams(['foo' => 'HI! ']);
        $result = $callable->call();
        $this->assertEquals('HI! HOW ARE YOU?', $result->getFoo());
    }

    public function testConstructorCall()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass');
        $result = $callable->call();
        $result->setFoo('HI!!!');
        $this->assertInstanceOf('Pop\Utils\Test\TestAsset\TestClass', $result);
        $this->assertEquals('HI!!!', $result->printFoo());
    }

    public function testConstructorCallWithParams()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass', 'HI BACK!');
        $result = $callable->call();
        $this->assertInstanceOf('Pop\Utils\Test\TestAsset\TestClass', $result);
        $this->assertEquals('HI BACK!', $result->printFoo());
    }

    public function testCallWithParams1()
    {
        $callable = new CallableObject('trim');
        $this->assertEquals('Hello World!', $callable->call(' Hello World! '));
    }

    public function testCallWithParams2()
    {
        $callable = new CallableObject(function($var1, $var2){ return $var1 . ' ' . $var2;});
        $this->assertEquals('Hello World!', $callable->call(['Hello', 'World!']));
    }

    public function testPrepareNoClassException()
    {
        $this->expectException('Pop\Utils\Exception');
        $callable = new CallableObject('BadClass->badMethod');
        $result = $callable->call();
    }

    public function testPrepareNoMethodException()
    {
        $this->expectException('Pop\Utils\Exception');
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass->badMethod');
        $result = $callable->call();
    }

    public function testPrepareNoCallableException()
    {
        $this->expectException('Pop\Utils\Exception');
        $callable = new CallableObject('badFunction');
        $result = $callable->call();
    }

    public function testPrepareParameters1()
    {
        $param    = new CallableObject(function(){ return ' Hello World! ';});
        $callable = new CallableObject('trim', $param);
        $this->assertEquals('Hello World!', $callable->call());
    }

    public function testPrepareParameters2()
    {
        $param    = function(){ return ' Hello World! ';};
        $callable = new CallableObject('trim', $param);
        $this->assertEquals('Hello World!', $callable->call());
    }

    public function testPrepareParameters3()
    {
        $param    = [[function($foo){ return $foo;}, ' Hello World! ']];
        $callable = new CallableObject('trim', $param);
        $this->assertEquals('Hello World!', $callable->call());
    }


}