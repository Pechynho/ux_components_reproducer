<?php

namespace App\Exception;

class NotImplementedException extends LogicException
{
    protected const ?string DEFAULT_MESSAGE = 'You have reached code which is not implemented.';
}
