<?php

namespace Pop\Utils\Test\TestAsset;

class TestClass
{
    protected $foo = null;

    public function __construct($foo = null)
    {
        if (null !== $foo) {
            $this->foo = $foo;
        }
    }

    public function printFoo()
    {
        echo $this->foo . PHP_EOL;
    }

    public static function sayHello($name)
    {
        echo 'Hello, ' . $name . PHP_EOL;
    }
}
