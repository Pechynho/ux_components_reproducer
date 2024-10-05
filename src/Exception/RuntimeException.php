<?php

namespace App\Exception;

use App\Exception\Traits\ExceptionTrait;

class RuntimeException extends \RuntimeException
{
    use ExceptionTrait;

    protected const ?string DEFAULT_MESSAGE = null;
}
