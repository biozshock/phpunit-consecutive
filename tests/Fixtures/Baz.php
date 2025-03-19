<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Tests\Fixtures;

class Baz
{
    public function __construct(private readonly Foo $foo)
    {
    }

    public function call(\stdClass $object, int $integer): int
    {
        $object = $this->foo->map($object, $integer);
        \assert(is_int($object->integer));

        return $object->integer;
    }
}
