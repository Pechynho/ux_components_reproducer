<?php

namespace App\Exception;

use App\Exception\Traits\ExceptionTrait;

class Exception extends \Exception
{
    use ExceptionTrait;

    protected const ?string DEFAULT_MESSAGE = null;
}
