<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Tests\Fixtures;

class Bar
{
    public function __construct(private readonly Foo $foo)
    {
    }

    public function stub(\stdClass $object, int $integer): void
    {
        $object = $this->foo->map($object, $integer);
        \assert(is_int($object->integer));
        $this->foo->call($object, $object->integer);
        \assert(is_int($object->integer));
        $object = $this->foo->map($object, $object->integer);
        \assert(is_int($object->integer));
        $this->foo->call($object, $object->integer);
    }
}
