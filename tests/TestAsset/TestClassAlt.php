<?php

namespace Pop\Utils\Test\TestAsset;

use PHPUnit\Util\Test;

class TestClassAlt
{
    protected mixed $foo = 'bar';

    public function __construct()
    {

    }

    public function setFoo($foo): TestClass
    {
        $this->foo = $foo;
        return $this;
    }

    public function addFoo($foo): TestClass
    {
        $this->foo .= $foo;
        return $this;
    }

    public function getFoo(): mixed
    {
        return $this->foo;
    }

    public function printFoo(): mixed
    {
        return $this->foo;
    }

    public static function sayHello($name): string
    {
        return 'Hello, ' . $name;
    }
}
