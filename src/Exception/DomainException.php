<?php

namespace App\Exception;

use App\Exception\Traits\ExceptionTrait;

class DomainException extends \DomainException
{
    use ExceptionTrait;

    protected const ?string DEFAULT_MESSAGE = null;
}
