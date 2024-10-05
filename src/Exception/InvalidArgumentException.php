<?php

namespace App\Exception;

use App\Exception\Traits\ExceptionTrait;

class InvalidArgumentException extends \InvalidArgumentException
{
    use ExceptionTrait;

    protected const ?string DEFAULT_MESSAGE = null;
}
