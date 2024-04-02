pop-utils
==========

[![Build Status](https://github.com/popphp/pop-utils/workflows/phpunit/badge.svg)](https://github.com/popphp/pop-utils/actions)
[![Coverage Status](http://cc.popphp.org/coverage.php?comp=pop-utils)](http://cc.popphp.org/pop-utils/)

[![Join the chat at https://popphp.slack.com](https://media.popphp.org/img/slack.svg)](https://popphp.slack.com)
[![Join the chat at https://discord.gg/TZjgT74U7E](https://media.popphp.org/img/discord.svg)](https://discord.gg/TZjgT74U7E)

* [Overview](#overview)
* [Install](#install)
* [Array Object](#array-object)
* [Collection](#collection)
* [Callable Object](#callable-object)
* [DateTime Object](#datetime-object)
* [File Helper](#file-helper)
* [String Helper](#string-helper)
* [Array Helper](#array-helper)
* [Helper Functions](#helper-functions)

Overview
--------
`pop-utils` is a basic utilities component of the [Pop PHP Framework](http://www.popphp.org/). It comes with
a number of utility and helper classes that can be useful when building applications with Pop.

[Top](#pop-utils)

Install
-------

Install `pop-utils` using Composer.

    composer require popphp/pop-utils
    
Or, require it in your composer.json file

    "require": {
        "popphp/pop-utils" : "^2.0.0"
    }

[Top](#pop-utils)

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

[Top](#pop-utils)

### Collection

The collection object is a array-like object with a tremendous amount of array-like functionality
built into it. This allows you to call any number of methods on the object to perform operations on the
array and its data. It's available API includes:

- `count(): int`
- `first(): mixed`
- `next(): mixed`
- `current(): mixed`
- `last(): mixed`
- `key(): mixed`
- `contains(mixed $key, bool $strict = false): bool`
- `each(callable $callback): Collection`
- `every(int $step, int $offset = 0): Collection`
- `filter(?callable $callback = null, int $flag = 0): Collection`
- `map(callable $callback): Collection`
- `flip(): Collection`
- `has(mixed $key): bool`
- `isEmpty(): bool`
- `keys(): Collection`
- `column(string $column): Collection`
- `values(): Collection`
- `merge(mixed $data, $recursive = false): Collection`
- `forPage(int $page, int $perPage): Collection`
- `pop(): mixed`
- `push(mixed $value): Collection`
- `shift(): mixed`
- `slice(int $offset, int $length = null): Collection`
- `splice(int $offset, ?int $length = null, mixed $replacement = []): Collection`
- `sort(?callable $callback = null, int $flags = SORT_REGULAR): Collection`
- `sortByAsc(int $flags = SORT_REGULAR): Collection`
- `sortByDesc(int $flags = SORT_REGULAR): Collection`
- `toArray(): array`

[Top](#pop-utils)

### Callable Object

The `Pop\Utils\CallableObject` class helps to manage callable objects and their parameters.
This includes functions, closures, classes and their methods (both static and instance.)
This is useful for wiring up something that needs to be called or triggered by the application
at a later time.

The parameters can be set anytime in the callable object's life cycle, from the time of
instantiation via the constructor, via the set/add methods or at the time of calling the object.
Parameters passed into the callable object can be callable themselves and will be invoked
at the time the parent callable object is called.

##### Function Callable

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject('trim', ' Hello World!');
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
$myInstance->printString();
```

[Top](#pop-utils)

### DateTime Object

The `Pop\Utils\DateTime` class extend the native `DateTime` class and adds some helper functions:

- Add HH:MM:SS formatted times together for a total time in the HH:MM:SS format.
- Average HH:MM:SS formatted times together for an average time in the HH:MM:SS format.
- Get the dates of any week in any year.

```php

use Pop\Utils\DateTime;

$times = ['08:45:18', '15:13:58', '09:05:09'];

$totalTime = Pop\Utils\DateTime::getTotal($times, '%H:%I:%S');
echo $totalTime . PHP_EOL; // 33:04:25

$averageTime = Pop\Utils\DateTime::getAverage($times, '%H:%I:%S');
echo $averageTime . PHP_EOL; // 11:01:28

$weekDates = DateTime::getWeekDates(40, 2023, 'Y-m-d'); // 40th week of the year 2023
print_r($weekDates);

/**
Array
(
    [0] => 2023-10-01
    [1] => 2023-10-02
    [2] => 2023-10-03
    [3] => 2023-10-04
    [4] => 2023-10-05
    [5] => 2023-10-06
    [6] => 2023-10-07
)
*/
```

[Top](#pop-utils)

### File Helper

The `Pop\Utils\File` class get quickly parse and return information about a file,
including the mime type for common file types

```php
use Pop\Utils\File

$file = new File(__DIR__ . '/tmp/test.txt');

echo $file->getBasename());  // 'test.txt'
echo $file->getFilename());  // 'test'
echo $file->getExtension()); // 'txt'
echo $file->getMimeType());  // 'text/plain'
echo $file->getPath());      // __DIR__ . '/tmp
echo $file->getSize());      // 13
```

You can quickly get just the mime type of a file like this:

```php
use Pop\Utils\File

echo File::getFileMimeType(__DIR__ . '/tmp/image.jpg'); // 'image/jpeg'
```

[Top](#pop-utils)

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
```

```text
Test Email <a href="mailto:test@test.com">test@test.com</a> and
Test Website <href="http://www.test.com/">http://www.test.com/</a>
```

##### Random Strings

```php
use Pop\Utils\Str;

echo Str::createRandom(10);                         // 5.u9MHw{PC
echo Str::createRandomAlpha(10, Str::LOWERCASE);    // wvjvvsmnjw
echo Str::createRandomAlphaNum(10, Str::UPPERCASE); // 6S73HQ629R
echo Str::createRandomAlphaNum(10, Str::MIXEDCASE); // Yfd35M3T92
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

[Top](#pop-utils)

### Array Helper

The `Pop\Utils\Arr` class has a number of static methods to assist in
manipulating arrays:

- `Arr::isArray(mixed $value): bool`
- `Arr::isNumeric(array $array): bool`
- `Arr::isAssoc(array $array): bool`
- `Arr::exists(array|ArrayAccess $array, string|int $key): bool`
- `Arr::key(array|AbstractArray $array, string|int $value, bool $strict = false): mixed`
- `Arr::collapse(array|AbstractArray $array): array`
- `Arr::flatten(array|AbstractArray $array, int|float $depth = INF): array`
- `Arr::divide(array|AbstractArray $array): array`
- `Arr::slice(array|AbstractArray $array, int $limit, int $offset = 0): array`
- `Arr::split(string $string, string $separator, int $limit = PHP_INT_MAX): array`
- `Arr::join(array|AbstractArray $array, string $glue, string $finalGlue = ''): string`
- `Arr::prepend(array|AbstractArray $array, mixed $value, mixed $key = null): array`
- `Arr::pull(array &$array, mixed $key): mixed`
- `Arr::sort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): array`
- `Arr::sortDesc(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true): array`
- `Arr::ksort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $descending = false): array`
- `Arr::ksortDesc(array|AbstractArray $array, int $flags = SORT_REGULAR): array`
- `Arr::usort(array|AbstractArray $array, mixed $callback, bool $assoc = true): array`
- `Arr::uksort(array|AbstractArray $array, mixed $callback): array`
- `Arr::map(array|AbstractArray $array, mixed $callback): array`
- `Arr::trim(array|AbstractArray $array): array`
- `Arr::filter(array|AbstractArray $array, mixed $callback = null, int $mode = ARRAY_FILTER_USE_BOTH): array`
- `Arr::make(mixed $value): array`

[Top](#pop-utils)

### Helper Functions

There is a set of "helper" functions to assist with quick manipulation of data like
strings, arrays, and dates. The functions themselves can be loaded manually by including the
`functions.php` file in this repository, or they can be loaded using the `Pop\Utils\Helper`
class functions:

```php
use Pop\Utils\Helper;

if (!Helper::isLoaded()) {
    Helper::loadFunctions();
}
```

The above is automatically executed in a `Pop\Application` object's bootstrap method, unless
it is disabled by the config setting of `'helper_functions' => false`.

The included functions are:

- `app_date(string $format, ?int $timestamp = null, string $env = 'APP_TIMEZONE', mixed $envDefault = null): string|null`
- `str_slug(string $string, string $separator = '-'): string`
- `str_random(int $length, int $case = Str::MIXEDCASE): string`
- `str_random_alpha(int $length, int $case = Str::MIXEDCASE): string`
- `str_random_num(int $length): string`
- `str_random_alphanum(int $length, int $case = Str::MIXEDCASE): string`
- `str_from_camel(string $string, ?string $separator = '-', bool $preserveCase = false): string`
- `str_to_camel(string $string): string`
- `str_title_case(string $string): string`
- `str_snake_case(string $string, bool $preserveCase = false): string`
- `str_kebab_case(string $string, bool $preserveCase = false): string`
- `array_collapse(array|AbstractArray $array): array`
- `array_flatten(array|AbstractArray $array, int|float $depth = INF): array`
- `array_divide(array|AbstractArray $array): array`
- `array_join(array|AbstractArray $array, string $glue, string $finalGlue = ''): string`
- `array_prepend(array|AbstractArray $array, mixed $value, mixed $key = null): array`
- `array_pull(array &$array, mixed $key): mixed`
- `array_sort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): array`
- `array_sort_desc(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true): array`
- `array_ksort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $descending = false): array`
- `array_ksort_desc(array|AbstractArray $array, int $flags = SORT_REGULAR): array`
- `array_usort(array|AbstractArray $array, mixed $callback, bool $assoc = true): array`
- `array_uksort(array|AbstractArray $array, mixed $callback): array`
- `array_make(mixed $value): array`
