<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Tests;

use Biozshock\PhpunitConsecutive\Consecutive;
use Biozshock\PhpunitConsecutive\Tests\Fixtures\Bar;
use Biozshock\PhpunitConsecutive\Tests\Fixtures\Baz;
use Biozshock\PhpunitConsecutive\Tests\Fixtures\Foo;
use Biozshock\PhpunitConsecutive\Tests\Fixtures\Qux;
use PHPUnit\Framework\TestCase;

class ConsecutiveTest extends TestCase
{
    public function testConsecutiveReal(): void
    {
        $object1 = new \stdClass();
        $object1->integer = 1;
        $object2 = new \stdClass();
        $object2->integer = 2;

        $foo = $this->createMock(Foo::class);
        $foo->expects($this->exactly(2))
            ->method('map')
            ->willReturnCallback(Consecutive::consecutiveMap([
                [new \stdClass(), 0, $object1],
                [$object1, 1, $object2],
            ]));
        $foo->expects($this->exactly(2))
            ->method('call')
            ->willReturnCallback(Consecutive::consecutiveCall([
                [$object1, $object1->integer],
                [$object2, $object2->integer],
            ]));

        $bar = new Bar($foo);
        $bar->stub(new \stdClass(), 0);
    }

    public function testConsecutiveConstraint(): void
    {
        $object1 = new \stdClass();
        $object1->integer = 1;
        $object2 = new \stdClass();
        $object2->integer = 2;

        $foo = $this->createMock(Foo::class);
        $foo->expects($this->exactly(2))
            ->method('map')
            ->willReturnCallback(Consecutive::consecutiveMap([
                [self::equalTo(new \stdClass()), self::logicalAnd(self::lessThan(1), self::greaterThanOrEqual(0)), $object1],
                [$object1, 1, $object2],
            ]));
        $foo->expects($this->exactly(2))
            ->method('call')
            ->willReturnCallback(Consecutive::consecutiveCall([
                [$object1, self::equalTo(1)],
                [$object2, $object2->integer],
            ]));

        $bar = new Bar($foo);
        $bar->stub(new \stdClass(), 0);
    }

    public function testConsecutiveCallbackReturn(): void
    {
        $foo = $this->createMock(Foo::class);
        $foo->expects($this->once())
            ->method('map')
            ->willReturnCallback(Consecutive::consecutiveMap([
                [new \stdClass(), 1, static function (\stdClass $object, int $integer): \stdClass {
                    $object->integer = $integer;

                    return $object;
                }],
            ]));

        $bar = new Baz($foo);
        $bar->call(new \stdClass(), 1);
    }

    public function testConsecutiveArgumentReturn(): void
    {
        $add = 8;
        $sequentialAdd = 15;
        $class = new \stdClass();
        $class->integer = 15;
        $foo = $this->createMock(Foo::class);
        $foo->expects($this->exactly(2))
            ->method('map')
            ->willReturnCallback(Consecutive::consecutiveMapReturn([
                [$class, $add],
                [$class, $sequentialAdd],
            ], 0));

        $qux = new Qux($foo);
        $result = $qux->stub($class, $add);
        self::assertSame($class, $result);
    }
}
