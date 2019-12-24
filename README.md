pop-utils
==========

[![Build Status](https://travis-ci.org/popphp/pop-utils.svg?branch=master)](https://travis-ci.org/popphp/pop-utils)
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
        "popphp/pop-utils" : "1.0.*"
    }


BASIC USAGE
-----------

### Array Objects

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

### String Helper

The `Pop\Utils\ArrayObject` class has a number of static methods to assist in
manipulating and generating strings.

##### Slugs

```php

use Pop\Utils\StringHelper;

echo StringHelper::createSlug('Hello World'); // hello-world

```

##### Links

```php

use Pop\Utils\StringHelper;

echo StringHelper::createLinks('Test Email test@test.com and Test Website http://www.test.com/');
// Test Email <a href="mailto:test@test.com">test@test.com</a> and
// Test Website < href="http://www.test.com/">http://www.test.com/</a>


```

##### Random Strings

```php

use Pop\Utils\StringHelper;

echo StringHelper::createRandom(10);                                  // 5.u9MHw{PC
echo StringHelper::createRandomAlpha(10, StringHelper::LOWERCASE);    // wvjvvsmnjw
echo StringHelper::createRandomAlphaNum(10, StringHelper::UPPERCASE); // 6S73HQ629R

```

##### Convert Case

The convert case feature allows for the followind case and string format types:

- TitleCase
- camelCase
- kebab-case (dash)
- snake_case (underscore)
- Name\Space
- folder/path
- url/path (uri)

And that can be utilized via static method calls:

```php

use Pop\Utils\StringHelper;

echo StringHelper::titleCaseToKebabCase('TitleCase');         // title-case
echo StringHelper::titleCaseToSnakeCase('TitleCase');         // title_case
echo StringHelper::camelCaseToDash('camelCase');              // camel-case
echo StringHelper::camelCaseToUnderscore('camelCase');        // camel_case
echo StringHelper::kebabCaseToTitleCase('kebab-string');      // KebabString
echo StringHelper::snakeCaseToCamelCase('snake_case_string'); // SnakeCaseString

```
