# Repository provides utility class: "replacement" for removed `withConsecutive` from PHPUnit. 

## Install
```bash
composer require --dev biozshock/phpunit-consecutive
```

## Usage
When you need to mock the return.
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
    ]))
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
    ]))
```
