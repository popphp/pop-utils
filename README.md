pop-utils
==========

[![Build Status](https://github.com/popphp/pop-utils/workflows/phpunit/badge.svg)](https://github.com/popphp/pop-utils/actions)
[![Coverage Status](http://cc.popphp.org/coverage.php?comp=pop-utils)](http://cc.popphp.org/pop-utils/)

OVERVIEW
--------
`pop-utils` is a basic utilities component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-utils` using Composer.

    composer require popphp/pop-utils
    
Or, require it in your composer.json file

    "require": {
        "popphp/pop-utils" : "1.3.*"
    }


BASIC USAGE
-----------

### Array Object

The `Pop\Utils\ArrayObject` class implements a number of interfaces to allow it to behave like
array, but with much more functionality built in. With it, you can access the array data within
the object via standard array notation (`$ary['item']`) or via object notation (`$ary->item`).

You can iterate over the array object and it is countable. Also you can cast it to an native
array using the `toArray()` method.

```php

use Pop\Utils\ArrayObject;

$arrayObject = new ArrayObject(['foo' => 'bar']);

echo $arrayObject->foo;
echo $arrayObject['foo'];

echo count($arrayObject);

foreach ($arrayObject as $key => $value) {
    echo $key . ': ' . $value;
}

$array = $arrayObject->toArray();

```

There are also additional serialize/unserialize methods that allow you to work with the
array object as JSON-string or PHP-serialized string

```php

use Pop\Utils\ArrayObject;

$arrayObject = ArrayObject::createFromJson('{"foo":"bar"}');
echo $arrayObject->jsonSerialize(JSON_PRETTY_PRINT);

```
```php

use Pop\Utils\ArrayObject;

$arrayObject = ArrayObject::createFromSerialized('a:1:{s:3:"foo";s:3:"bar";}');
echo $arrayObject->serialize();

```

### DateTime Object

The `Pop\Utils\DateTime` class extend the native `DateTime` class and adds functionality
to support adding and averaging times together in the HH:MM:SS format.

```php

use Pop\Utils\DateTime;

$times = ['08:45:18', '15:13:58', '09:05:09'];

$totalTime = Pop\Utils\DateTime::getTotal($times, '%H:%I:%S');
echo $totalTime . PHP_EOL; // 33:04:25

$averageTime = Pop\Utils\DateTime::getAverage($times, '%H:%I:%S');
echo $averageTime . PHP_EOL; // 11:01:28

```

### Callable Object

The `Pop\Utils\CallableObject` class helps to manage callable objects and their parameters.
This includes functions, closures, classes and their methods (both static and instance.)

The parameters can be set anytime in the callable object's life cycle, from the time of
instantiation via the constructor, via the set/add methods or at the time of calling the object.
Parameters passed into the callable object can be callable themselves and will be invoked
at the time the parent callable object is called.

##### Function Callable

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject('trim', ' Hello World! ');
echo $callable->call(); // Outputs 'Hello World!'
```

##### Closure Callable

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject(function ($var) { echo strtoupper($var) . '!';});
$callable->addParameter('hello world');
echo $callable->call(); // Outputs 'HELLO WORLD!'
```

Here's an alternate way to call by passing the parameter in at the time of the call:

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject(function ($var) { echo strtoupper($var) . '!';});
echo $callable->call('hello world'); // Outputs 'HELLO WORLD!'
```

##### Static Method Callable

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject('MyClass::someMethod');
echo $callable->call(); // Executes the static 'someMethod()' from class 'MyClass'
```

##### Instance Method Callable

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject('MyClass->someMethod');
echo $callable->call(); // Executes the 'someMethod()' in an instance of 'MyClass'
```

##### Constructor Callable

```php
use Pop\Utils\CallableObject;

class MyClass
{

    protected $str = null;

    public function __construct($str)
    {
        $this->str = $str;
    }

    public function printString()
    {
        echo $this->str;
    }

}

// Creates an instance of 'MyClass' with the string 'Hello World' passed into the constructor
$callable = new CallableObject('MyClass', 'Hello World');
$myInstance = $callable->call();
$myInstance->printString() ;
```


### String Helper

The `Pop\Utils\Str` class has a number of static methods to assist in
manipulating and generating strings.

##### Slugs

```php

use Pop\Utils\Str;

echo Str::createSlug('Hello World | Home Page'); // hello-world-home-page

```

##### Links

```php

use Pop\Utils\Str;

echo Str::createLinks('Test Email test@test.com and Test Website http://www.test.com/');
// Test Email <a href="mailto:test@test.com">test@test.com</a> and
// Test Website <href="http://www.test.com/">http://www.test.com/</a>


```

##### Random Strings

```php

use Pop\Utils\Str;

echo Str::createRandom(10);                                  // 5.u9MHw{PC
echo Str::createRandomAlpha(10, Str::LOWERCASE);    // wvjvvsmnjw
echo Str::createRandomAlphaNum(10, Str::UPPERCASE); // 6S73HQ629R

```

##### Convert Case

The convert case feature allows for the following case and string format types:

- TitleCase
- camelCase
- kebab-case (dash)
- snake_case (underscore)
- Name\Space
- folder/path
- url/path (uri)

And can be utilized via a variety of dynamic static method calls:

```php

use Pop\Utils\Str;

echo Str::titleCaseToKebabCase('TitleCase');         // title-case
echo Str::titleCaseToSnakeCase('TitleCase');         // title_case
echo Str::camelCaseToDash('camelCase');              // camel-case
echo Str::camelCaseToUnderscore('camelCase');        // camel_case
echo Str::kebabCaseToTitleCase('kebab-string');      // KebabString
echo Str::snakeCaseToCamelCase('snake_case_string'); // SnakeCaseString
echo Str::snakeCaseToNamespace('snake_case_string'); // Snake\Case\String
echo Str::kebabCaseToPath('kebab-string');           // kebab/string (kebab\string on Windows)
echo Str::camelCaseToUrl('camelCase');               // camel/case

```
