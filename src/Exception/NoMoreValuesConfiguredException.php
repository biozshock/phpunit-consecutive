<?php

declare(strict_types=1);

namespace Biozshock\PhpunitConsecutive\Exception;

class NoMoreValuesConfiguredException extends \RuntimeException
{
    public function __construct(int $index, int $count)
    {
        parent::__construct(
            sprintf(
                'Only %d return values have been configured. This is %d call.',
                $count,
                $index
            ),
        );
    }
}
