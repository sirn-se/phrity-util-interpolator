<p align="center"><img src="docs/logotype.png" alt="Phrity Util Interpolator" width="100%"></p>

[![Build Status](https://github.com/sirn-se/phrity-util-interpolator/actions/workflows/acceptance.yml/badge.svg)](https://github.com/sirn-se/phrity-util-interpolator/actions)
[![Coverage Status](https://coveralls.io/repos/github/sirn-se/phrity-util-interpolator/badge.svg?branch=main)](https://coveralls.io/github/sirn-se/phrity-util-interpolator?branch=main)

# Introduction

Class and trait to perform string interpolation.

## Installation

Install with [Composer](https://getcomposer.org/);
```
composer require phrity/util-interpolator
```

# How to use

## Using the Interpolator class

The interpolator will replace `{$key}` in input string with corresponding data in replacer array.

```php
$interpolator = new Phrity\Util\Interpolator\Interpolator();
$result = $interpolator->interpolate('Interpolating {a} and {b}.', [
    'a' => 'first',
    'b' => 'second',
];
// $result -> 'Interpolating first and second.'
```

When replacers are nested in array or object, they can be accessed using path notation.

```php
$interpolator = new Phrity\Util\Interpolator\Interpolator();
$result = $interpolator->interpolate('Interpolating {a.a} and {a.b} from {a}.', [
    'a' => [
        'a' => 'first',
        'b' => 'second',
    ],
];
// $result -> 'Interpolating first and second from array.'
```

## Using the Interpolator trait

Interpolator is also available as trait method to be used in any class.

```php
class MyClass
{
    use Phrity\Util\Interpolator\InterpolatorTrait;
    // ...
}

$myClass = new MyClass();
$result = $myClass->interpolate('Interpolating {a}', ['a' => 'b']);
```

## Defining path separator

By default, paths are separated using `.` but it is possible to define another separator.

```php
$separator = '/';
$input = 'Interpolating {a.b} and {a.c}.';
$replacers = ['a' => '{"b": "test", "c": 1234}'];

// Class
$interpolator = new Phrity\Util\Interpolator\Interpolator(separator: $separator);
$result = $interpolator->interpolate($input, $replacers);
// $result -> 'Interpolating test.'

// Trait
$myClass = new MyClass();
$result = $myClass->interpolate($input, $replacers, separator: $separator);
// $result -> 'Interpolating test.'
```

## Defining value transformer

To convert replacer values to strings the library uses [Phrity Transformers](https://phrity.sirn.se/util-transformer).

The default configuration uses
```php
$transformer = new Phrity\Util\Transformer\FirstMatchResolver([
    new Phrity\Util\Transformer\ReadableConverter(),
    new Phrity\Util\Transformer\ThrowableConverter(),
    new Phrity\Util\Transformer\BasicTypeConverter(),
]);
```

You can also set another transformer or set of transformers.

```php
$transformer = new Phrity\Util\Transformer\JsonDecoder();
$input = 'Interpolating {a.b} and {a.c}.';
$replacers = ['a' => '{"b": "test", "c": 1234}'];

// Class
$interpolator = new Phrity\Util\Interpolator\Interpolator(transformer: $transformer);
$result = $interpolator->interpolate($input, $replacers);
// $result -> Interpolating test and 1234.

// Trait
$myClass = new MyClass();
$result = $myClass->interpolate($input, $replacers, transformer: $transformer);
// $result -> Interpolating test and 1234.
```

# Versions

| Version | PHP | |
| --- | --- | --- |
| `1.0` | `^8.1` | Initial version |
