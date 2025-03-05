<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Tests;

use Biozshock\PhpunitConsecutive\Consecutive;
use Biozshock\PhpunitConsecutive\Tests\Fixtures\Bar;
use Biozshock\PhpunitConsecutive\Tests\Fixtures\Foo;
use PHPUnit\Framework\TestCase;

class ConsecutiveTest extends TestCase
{
    public function testConsecutive(): void
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
}
