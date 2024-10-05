<?php

namespace App\Exception;

use App\Exception\Traits\ExceptionTrait;

class LogicException extends \LogicException
{
    use ExceptionTrait;

    protected const ?string DEFAULT_MESSAGE = null;
}
