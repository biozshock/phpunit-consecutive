<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Tests\Fixtures;

class Foo
{
    public function map(\stdClass $object, int $integer): \stdClass
    {
        $object = clone $object;
        $object->integer = $integer;

        return $object;
    }

    public function call(\stdClass $object, int $integer): void
    {
        $object->integer = $integer;
    }
}
