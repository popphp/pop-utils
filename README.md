pop-utils
==========

[![Build Status](https://github.com/popphp/pop-utils/workflows/phpunit/badge.svg)](https://github.com/popphp/pop-utils/actions)
[![Coverage Status](https://cc.popphp.org/coverage.php?comp=pop-utils)](https://cc.popphp.org/pop-utils/)

[![Join the chat at https://discord.gg/TZjgT74U7E](https://media.popphp.org/img/discord.svg)](https://discord.gg/TZjgT74U7E)

* [Overview](#overview)
* [Install](#install)
* [Array Object](#array-object)
* [Abstract Model](#abstract-model)
* [Collection](#collection)
* [Callable Object](#callable-object)
* [DateTime Object](#datetime-object)
* [File Helper](#file-helper)
* [Array Helper](#array-helper)
* [String Helper](#string-helper)
* [Number Helper](#number-helper)
* [UUID Helper](#uuid-helper)
* [Helper Functions](#helper-functions)

Overview
--------
`pop-utils` is a basic utilities component of the [Pop PHP Framework](https://www.popphp.org/). It comes with
a number of utility and helper classes that can be useful when building applications with Pop.

Where a class in this component throws an exception (e.g. an invalid constructor argument, or an
unresolvable callable), it throws `Pop\Utils\Exception`.

[Top](#pop-utils)

Install
-------

Requires PHP 8.4 or greater, and the `ext-json` extension.

Install `pop-utils` using Composer.

    composer require popphp/pop-utils
    
Or, require it in your composer.json file

    "require": {
        "popphp/pop-utils" : "^3.0.0"
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

`ArrayObject` also inherits a set of cursor and sorting methods from its parent `AbstractArray` class:

- `first(): mixed`
- `next(): mixed`
- `current(): mixed`
- `last(): mixed`
- `key(): mixed`
- `contains(mixed $key, bool $strict = false): bool`
- `sort(int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): static`
- `sortDesc(int $flags = SORT_REGULAR, bool $assoc = true): static`
- `ksort(int $flags = SORT_REGULAR, bool $descending = false): static`
- `ksortDesc(int $flags = SORT_REGULAR): static`
- `usort(mixed $callback, bool $assoc = true): static`
- `uksort(mixed $callback): static`
- `join(string $glue, string $finalGlue = ''): string`
- `static::split(string $string, string $separator, int $limit = PHP_INT_MAX): static`

Note that, unlike `Collection`'s methods, the sort methods (`sort`, `sortDesc`, `ksort`, `ksortDesc`, `usort`, `uksort`)
mutate the object's data in place and return `$this`, rather than returning a new instance:

```php
use Pop\Utils\ArrayObject;

$arrayObject = new ArrayObject(['b' => 2, 'a' => 1]);
$arrayObject->ksort(); // $arrayObject is now sorted by key in place
```

[Top](#pop-utils)

### Abstract Model

The `Pop\Utils\AbstractModel` class is an empty stub class that extends `ArrayObject`. It's meant to be
used as a common base class for an application's data model classes, so they can share one ancestor (for
type-checking, etc.) while getting all of `ArrayObject`'s array/object-access, iteration, and
serialize/unserialize behavior for free.

```php
use Pop\Utils\AbstractModel;

class User extends AbstractModel
{

}

$user = new User(['id' => 1, 'name' => 'Nick']);
echo $user->name; // 'Nick'
echo $user['name']; // 'Nick'
```

[Top](#pop-utils)

### Collection

The collection object is a array-like object with a tremendous amount of array-like functionality
built into it. This allows you to call any number of methods on the object to perform operations on the
array and its data.

```php
use Pop\Utils\Collection;

$collection = new Collection(['first' => 'Nick', 'last' => 'Sagona']);

// Collection also accepts plain arrays of arrays, another Collection, an \ArrayObject,
// any \Traversable, or any object that exposes its own toArray() method.
$people = new Collection([
    ['name' => 'Nick', 'age' => 40],
    ['name' => 'Jane', 'age' => 35],
]);

$names = $people->column('name')->values()->toArray(); // ['Nick', 'Jane']
```

Unlike `AbstractArray`'s own sort methods (shared with `ArrayObject`, see above), most `Collection`
methods return a **new** `Collection` instance rather than mutating the original in place — so they can
be chained freely without affecting earlier references.

Its available API includes:

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
- `sort(int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): static`
- `sortDesc(int $flags = SORT_REGULAR, bool $assoc = true): static`
- `ksort(int $flags = SORT_REGULAR, bool $descending = false): static`
- `ksortDesc(int $flags = SORT_REGULAR): static`
- `usort(mixed $callback, bool $assoc = true): static`
- `uksort(mixed $callback): static`
- `join(string $glue, string $finalGlue = ''): string`
- `toArray(): array`

The `sort`/`sortDesc`/`ksort`/`ksortDesc`/`usort`/`uksort` methods are inherited directly from `AbstractArray`
(the same ones documented under Array Object above) and, unlike every other method listed here, they
**mutate the collection in place** and return `$this` rather than a new `Collection`.

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

The `'new MyClass'` form is an equivalent spelling of the same thing, and takes constructor parameters
the same way:

```php
$callable = new CallableObject('new MyClass', 'Hello World');
$myInstance = $callable->call();
$myInstance->printString();
```

##### Managing Parameters

Beyond passing parameters into the constructor or `call()`, `CallableObject` has a fluent API for
building up and inspecting a call's parameters (and, for instance/constructor calls, the constructor
parameters) over its lifecycle:

- `setParameters(array $parameters): CallableObject`
- `addParameters(array $parameters): CallableObject`
- `addParameter(mixed $parameter): CallableObject`
- `addNamedParameter(string $name, mixed $parameter): CallableObject`
- `getParameters(): array`
- `getParameter(string $key): mixed`
- `hasParameters(): bool`
- `hasParameter(string $key): bool`
- `removeParameter(string $key): CallableObject`
- `removeParameters(): CallableObject`
- `setConstructorParams(array $constructorParams): CallableObject`
- `getConstructorParams(): array`
- `getConstructorParam(string $key): mixed`
- `hasConstructorParams(): bool`
- `hasConstructorParam(string $key): bool`
- `removeConstructorParam(string $key): CallableObject`
- `removeConstructorParams(): CallableObject`
- `getCallableType(): ?string` — one of the `AbstractCallable::*` type constants (e.g. `FUNCTION`, `CLOSURE`, `STATIC_CALL`, `INSTANCE_CALL`, `CONSTRUCTOR_CALL`), set once `prepare()` has run
- `isCallable(): bool` — triggers `prepare()` if it hasn't run yet
- `wasCalled(): bool`

```php
use Pop\Utils\CallableObject;

$callable = new CallableObject('MyClass->someMethod');
$callable->addNamedParameter('id', 123)
    ->addParameter('active');

echo $callable->call(); // Executes 'someMethod(id: 123, 'active')' on a new instance of 'MyClass'
```

Any parameter that is itself callable — a `CallableObject`, a plain callable, or a `[callable, ...args]`
array — is resolved by invoking it at call time, and its return value is passed in as the actual
parameter value.

[Top](#pop-utils)

### DateTime Object

The `Pop\Utils\DateTime` class extends the native `DateTime` class and adds some helper functions:

- Add HH:MM:SS formatted times together for a total time in the HH:MM:SS format.
- Average HH:MM:SS formatted times together for an average time in the HH:MM:SS format.
- Get the dates of any week in any year.
- Determine whether a given date/time falls within daylight saving time.
- Store a default date and/or time format so the object can be cast directly to a string.

The constructor also accepts a wider range of common date/time string formats than native `\DateTime`
does out of the box (e.g. `m/d/Y`, `d.m.Y`, 2- or 4-digit years, bare time strings, 12-hour time) by
auto-detecting the format before parsing it.

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

To determine whether a given date/time falls within daylight saving time (defaults to U.S. DST rules —
2nd Sunday of March through 1st Sunday of November — if no explicit window is given):

```php
use Pop\Utils\DateTime;

DateTime::isDst();                                       // true/false for right now
DateTime::isDst('2023-07-04');                            // true
DateTime::isDst('2023-01-01', '2023-04-01', '2023-10-01'); // custom DST window
```

You can also give a `DateTime` object default date and/or time formats, which are then used
automatically by `__toString()` (casting a plain native `\DateTime`, or a `DateTime` with no default
format set, to a string produces an empty string):

```php
use Pop\Utils\DateTime;

$dateTime = DateTime::create('now', null, 'Y-m-d', 'H:i:s');
// equivalent to:
$dateTime = new DateTime();
$dateTime->setDefaultDateFormat('Y-m-d')
    ->setDefaultTimeFormat('H:i:s');

echo $dateTime; // e.g. '2026-08-06 14:32:10'
```

[Top](#pop-utils)

### File Helper

The `Pop\Utils\File` class can quickly parse and return information about a file,
including the mime type for common file types

```php
use Pop\Utils\File;

$file = new File(__DIR__ . '/tmp/test.txt');

echo $file->getBasename();  // 'test.txt'
echo $file->getFilename();  // 'test'
echo $file->getExtension(); // 'txt'
echo $file->getMimeType();  // 'text/plain'
echo $file->getPath();      // __DIR__ . '/tmp'
echo $file->getSize();      // 13
echo $file;                 // __toString() outputs the full path, e.g. __DIR__ . '/tmp/test.txt'
```

`File` only parses path/size/mime-type information — it does not read the file's contents unless you
explicitly ask it to:

```php
$file->exists();       // bool - does the file actually exist on disk
$file->getContents();  // the raw file contents (file_get_contents())
$file->toArray();      // ['basename' => ..., 'filename' => ..., 'extension' => ..., 'path' => ..., 'size' => ..., 'mime_type' => ...]
```

Each property also has a corresponding setter and a `has*()` presence check, so a `File` object can be
built up manually instead of (or in addition to) parsing an existing path:

- `setBasename(string $basename): File` / `hasBasename(): bool`
- `setFilename(string $filename): File` / `hasFilename(): bool`
- `setExtension(string $extension): File` / `hasExtension(): bool` — also updates the mime type based on the extension
- `setPath(string $path): File` / `hasPath(): bool`
- `setSize(int $size): File` / `hasSize(): bool`
- `setMimeType(string $mimeType): File` / `hasMimeType(): bool`
- `setDefaultMimeType(): File` / `getDefaultMimeType(): string` / `isDefaultMimeType(): bool` — the fallback mime type (`application/octet-stream`) used for unrecognized extensions

You can quickly get just the mime type of a file like this:

```php
use Pop\Utils\File;

echo File::getFileMimeType(__DIR__ . '/tmp/image.jpg'); // 'image/jpeg'
```

`File::getMimeTypes()` (static) / `$file->getAllMimeTypes()` (instance) return the full lookup table of
known extension-to-mime-type mappings, and `File::formatFileSize(int $filesize, int $round = 2, ?bool $case = null, string $space = ' '): string`
/ `$file->formatSize(...)` format a byte count into a human-readable string (e.g. `1.5 MB`); pass `$case`
as `true` for Title case (`Mb`) or `false` for lowercase (`mb`) — the default (`null`) is uppercase (`MB`).

There are also static classifier methods that check a filename's mime type against common groupings —
they inspect the extension via `getFileMimeType()`, not the file's actual contents:

```php
use Pop\Utils\File;

File::isImage('photo.jpg');       // true — bmp, gif, ico, jpe/jpg/jpeg, png, psd, svg, tif/tiff
File::isWebImage('photo.jpg');    // true — the subset of image formats suitable for the web
File::isVideo('clip.mp4');        // true — avi, mov, mp4, mpeg, ogv, ogx, wmv
File::isAudio('track.mp3');       // true — aiff, flac, mid/midi, mp3, m4a, ogg/oga/ogx, wav
File::isText('notes.txt');        // true — csv, log, tsv, txt
File::isCompressed('archive.zip'); // true — bz/bz2, gz, jar, rar, tar, zip
File::isWord('doc.docx');         // true — doc, docx, rtf
File::isPdf('doc.pdf');           // true
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
Test Website <a href="http://www.test.com/">http://www.test.com/</a>
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
echo Str::snakeCaseToCamelCase('snake_case_string'); // snakeCaseString
echo Str::snakeCaseToNamespace('snake_case_string'); // Snake\Case\String
echo Str::kebabCaseToPath('kebab-string');           // Kebab/String (Kebab\String on Windows)
echo Str::camelCaseToUrl('camelCase');               // camel/Case
```

The path, namespace and URL conversions preserve the source string's casing by default. Pass `false` as
the second argument for a lowercased result, which is usually what a URL or a filesystem path wants:

```php
use Pop\Utils\Str;

echo Str::kebabCaseToPath('kebab-string', false);    // kebab/string
echo Str::camelCaseToUrl('camelCase', false);        // camel/case
```

##### Stripping Special Characters

```php
use Pop\Utils\Str;

echo Str::stripSpecialCharacters('Hello_World! #1'); // 'Hello_World 1'

// Alphanumeric only (no dashes or underscores), and disallow spaces
echo Str::stripSpecialCharacters('Hello_World! #1', alphaNumOnly: true, spaces: false); // 'HelloWorld1'
```

##### Matching Strings

`Str::matches()` checks a string against one or more source strings, first via a direct substring
check (`str_contains()`), falling back to a `similar_text()` percentage comparison (controlled by
the `$accuracy` argument, default `75`) if the substring check fails:

```php
use Pop\Utils\Str;

var_dump(Str::matches('John Doe', 'foo bar John Doe baz pow'));            // bool(true) - direct substring match
var_dump(Str::matches('John Doe', 'foo bar John Q. Doe, Esq pow'));        // bool(false) - not a substring, and below 75% similar
var_dump(Str::matches('John Doe', 'foo bar John Q. Doe, Esq pow', accuracy: 40)); // bool(true) - ~44% similar
```

`$sources` may also be an array of strings. By default, `$strict = false` means only one source has
to match; pass `$strict = true` to require all of the sources to match:

```php
use Pop\Utils\Str;

$sources = ['goodbye', 'hello', 'farewell'];

var_dump(Str::matches('hello', $sources));               // bool(true)  - matches 'hello'
var_dump(Str::matches('hello', $sources, strict: true));  // bool(false) - doesn't match 'goodbye' or 'farewell'
```

[Top](#pop-utils)

### Number Helper

The `Pop\Utils\Num` class has a number of static methods to assist in
manipulating and formatting numbers:

- `Num::float(mixed $number, string $separator = '', string $decimal = '.', int $precision = 2): string`
- `Num::currency(mixed $number, string $currency = '$', string $separator = ',', string $decimal = '.', int $precision = 2): string`
- `Num::percentage(mixed $number, int $precision = 2, string $decimal = '.'): string`
- `Num::convertPercentage(mixed $number, int $precision = 2, string $decimal = '.'): string`
- `Num::abbreviate(mixed $number, int $precision = 2, bool $uppercase = true, string $space = ''): string`
- `Num::readable(mixed $number,  bool $case = true): string`

```php
use Pop\Utils\Num;

echo Num::float(1234.5);              // '1234.50'
echo Num::currency(1234.5);           // '$1,234.50'
echo Num::percentage(12.345);         // '12.35%'
echo Num::convertPercentage(0.1234);  // '12.34%'
echo Num::abbreviate(1234567);        // '1.23M'
echo Num::readable(1234567);          // '1 Million'
```

[Top](#pop-utils)

### UUID Helper

The `Pop\Utils\Uuid` class has a few static methods to assist in the generation of V4 and V7 UUIDs:

- `Uuid::v4(): string`
- `Uuid::v4Linux(): string`
- `Uuid::v4LinuxAvailable(): bool`
- `Uuid::v7(): string`

```php
use Pop\Utils\Uuid;

// Generate a v4 UUID (random) using native PHP
echo Uuid::v4();         

// Generate a v4 UUID (random) using the Linux random/uuid file
if (Uuid::v4LinuxAvailable()) {
    echo Uuid::v4Linux();
}

// Generate a v7 UUID (time-based) using native PHP
echo Uuid::v7();  
```

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
- `app_time(?string $timestamp = null, string $env = 'APP_TIMEZONE', mixed $envDefault = null): string|null` - same as `app_date()`, but returns a Unix timestamp
- `is_json(mixed $json): bool`
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
