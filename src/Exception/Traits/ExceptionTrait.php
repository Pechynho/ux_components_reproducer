<?php

namespace App\Exception\Traits;

use ReflectionObject;
use Throwable;

trait ExceptionTrait
{
    public function __construct(string | Throwable $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $defaultMessageConstantName = 'DEFAULT_MESSAGE';
        $refObj = new ReflectionObject($this);
        $defaultMessage = $refObj->hasConstant($defaultMessageConstantName)
            ? $refObj->getConstant($defaultMessageConstantName)
            : null;
        if ($message instanceof Throwable) {
            $throwable = $message;
            $message = $throwable->getMessage();
            $code = $throwable->getCode();
            $previous = $throwable;
        }
        if (is_string($defaultMessage) && ($message === null || trim($message) === '')) {
            $message = $defaultMessage;
        }
        parent::__construct($message, $code, $previous);
    }
}
