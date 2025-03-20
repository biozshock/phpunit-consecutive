<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive;

use Biozshock\PhpunitConsecutive\Exception\NoMoreValuesConfiguredException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;

class Consecutive
{
    /**
     * Provides a callback for testing consecutive call with return value. Last element in $map is return value.
     *
     * If the `void` expected to be returned, then use consecutiveCall
     *
     * @param array<array<mixed>> $map
     */
    public static function consecutiveMap(array $map): \Closure
    {
        $index = 0;

        return static function () use (&$index, $map) {
            if (!isset($map[$index])) {
                throw new NoMoreValuesConfiguredException($index, count($map));
            }

            $expectedParameters = $map[$index];
            $return = array_pop($expectedParameters);
            $arguments = func_get_args();
            // Move to the next index here, so in case of an Exception in the Constraint consecutive can catch next set.
            ++$index;
            foreach ($expectedParameters as $parameterIndex => $expectedParameter) {
                if ($expectedParameter instanceof Constraint) {
                    $expectedParameter->evaluate($arguments[$parameterIndex]);
                    continue;
                }

                Assert::assertEquals($expectedParameter, $arguments[$parameterIndex], 'Invocation #'.$index);
            }

            if (is_callable($return)) {
                return call_user_func_array($return, $arguments);
            }

            return $return;
        };
    }

    /**
     * Provides a callback for testing consecutive call with return value. Last element in $map is return value.
     *
     * If the `void` expected to be returned, then use consecutiveCall
     *
     * @param array<array<mixed>> $map
     * @param int                 $returnIndex the index of the returned argument
     */
    public static function consecutiveMapReturn(array $map, int $returnIndex): \Closure
    {
        $index = 0;

        return static function () use ($returnIndex, &$index, $map) {
            if (!isset($map[$index])) {
                throw new NoMoreValuesConfiguredException($index, count($map));
            }

            $expectedParameters = $map[$index];

            if (!isset($expectedParameters[$returnIndex])) {
                throw new \InvalidArgumentException('The return parameter at index "'.$returnIndex.'" does not exist.');
            }

            $arguments = func_get_args();
            $return = $arguments[$returnIndex];
            // Move to the next index here, so in case of an Exception in the Constraint consecutive can catch next set.
            ++$index;
            foreach ($expectedParameters as $parameterIndex => $expectedParameter) {
                if ($expectedParameter instanceof Constraint) {
                    $expectedParameter->evaluate($arguments[$parameterIndex]);
                    continue;
                }

                Assert::assertEquals($expectedParameter, $arguments[$parameterIndex], 'Invocation #'.$index);
            }

            return $return;
        };
    }

    /**
     * Provides a callback for testing consecutive call with `void` return value.
     *
     * If the expected return value is not `void`, then use consecutiveMap
     *
     * @param array<array<mixed>> $map
     */
    public static function consecutiveCall(array $map): \Closure
    {
        $index = 0;

        return static function () use (&$index, $map): void {
            if (!isset($map[$index])) {
                throw new NoMoreValuesConfiguredException($index, count($map));
            }

            $expectedParameters = $map[$index];
            $arguments = func_get_args();
            // Move to the next index here, so in case of an Exception in the Constraint consecutive can catch next set.
            ++$index;
            foreach ($expectedParameters as $parameterIndex => $expectedParameter) {
                if ($expectedParameter instanceof Constraint) {
                    $expectedParameter->evaluate($arguments[$parameterIndex]);
                    continue;
                }

                Assert::assertEquals($expectedParameter, $arguments[$parameterIndex], 'Invocation #'.$index);
            }
        };
    }
}
