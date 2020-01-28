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
        $this->assertFalse($callable->isCallable());
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
        $callable = new CallableObject('trim', ' Hello World! ');
        $this->assertEquals('Hello World!', $callable->call());
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
        $this->assertEquals('Hello, Nick', $callable->call());
    }

    public function testInstanceCall()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass->printFoo');
        $callable->setConstructorParams(['foo' => 'HI!']);
        $this->assertEquals('HI!', $callable->call());
    }

    public function testConstructorCall()
    {
        $callable = new CallableObject('Pop\Utils\Test\TestAsset\TestClass', 'HI BACK!');
        $result = $callable->call();
        $this->assertInstanceOf('Pop\Utils\Test\TestAsset\TestClass', $result);
        $this->assertEquals('HI BACK!', $result->printFoo());
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

}