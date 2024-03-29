<?php

namespace Pop\Utils\Test\TestAsset;

use PHPUnit\Util\Test;

class TestClass
{
    protected mixed $foo = null;

    public function __construct(mixed $foo = null)
    {
        if (null !== $foo) {
            $this->foo = $foo;
        }
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
