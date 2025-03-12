<?php declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Tests\Fixtures;

class Qux
{
    public function __construct(private readonly Foo $foo)
    {
    }

    public function stub(\stdClass $object, int $integer): \stdClass
    {
        $object = $this->foo->map($object, $integer);
        \assert(is_int($object->integer));

        return $this->foo->map($object, $object->integer);
    }
}
