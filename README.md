# Repository provides utility class: "replacement" for removed `withConsecutive` from PHPUnit. 

## Why?
[In the issue of PHPUnit](https://github.com/sebastianbergmann/phpunit/issues/4026) there are several 
possibilities to replace missing functionality `withConsecutive` of PHPUnit.
Most of the comments use some sort of the callback:
```php
$expectedArguments = [
    ...
]
->withConsecutive(function ($arg1, $arg2) use (&$index) {
    if ($index === 0) {
        self::assertEquals('some', $arg1);
        ...
    }
});
```
Eventually i've got sick of writing such code. Writing boilerplate and repeating yourself is no fun.

The class solve also the issue, where you need to operate with arguments <-> return value relation.
Giving the developer an ability to define what's returned with each argument set.

## Install
```bash
composer require --dev biozshock/phpunit-consecutive
```

## Usage
When you need to mock the method which returns a value.
```php
$mock->method('add')
    ->withConsecutive($a, $b)
    ->willReturn(1, 2);
```
Is replaced by 
```php
$mock->method('add')
    ->willRecturnCallback(Consecutive::consecutiveMap([
        [$a, 1],
        [$b, 2]
    ]));
```
Or return callback, which accepts given arguments:
```php
$mock->method('add')
    ->willRecturnCallback(Consecutive::consecutiveMap([
        [$a, $b, static function (int $a, string $b): bool {
            return $a === (int) $b;
        }],
        [$c, $d, static function (int $c, string $d): bool {
            return str_starts_with($d, (string) $c);
        }]
    ]));
```
Also, you can test methods that return one of arguments. In this example the test expects zero-index argument `$a` to be returned:
```php
$mock->method('add')
    ->willRecturnCallback(Consecutive::consecutiveMap([
        [$a, $b],
        [$a, $d]
    ], 0));
```

Otherwise, when mocked method returns `void`.
```php
$mock->method('add')
    ->withConsecutive($a, $b);
```
Is replaced by
```php
$mock->method('add')
    ->willRecturnCallback(Consecutive::consecutiveCall([
        [$a],
        [$b]
    ]));
```
