<?php

namespace Pop\Utils\Test\TestAsset;

use Pop\Utils\AbstractError;

class TestError extends AbstractError
{
    protected array $errorMessages = [
        1 => 'Error #1',
        2 => 'Error #2',
        3 => 'Error #3',
        4 => 'Error #4',
        5 => 'Error #5'
    ];
}
