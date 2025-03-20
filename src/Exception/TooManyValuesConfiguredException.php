<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Exception;

class TooManyValuesConfiguredException extends \RuntimeException
{
    public function __construct(int $index, int $countAvailable, int $countConfigured)
    {
        parent::__construct(
            sprintf(
                'Invocation #%d: Method has only %d arguments. %d have been configured.',
                $index,
                $countAvailable,
                $countConfigured
            )
        );
    }
}
