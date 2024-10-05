<?php

namespace App\Exception;

use App\Exception\Traits\ExceptionTrait;
use App\Utils\Strings;
use Throwable;

class UnexpectedValueException extends \UnexpectedValueException
{
    use ExceptionTrait;

    protected const ?string DEFAULT_MESSAGE = null;

    /**
     * @param string|array<string> $expectedType
     */
    public static function unexpectedType(
        string|array $expectedType,
        mixed $value,
        int $code = 0,
        ?Throwable $previous = null
    ): static {
        $providedType = Strings::varToString($value);
        if (is_array($expectedType)) {
            $expectedType = implode(' | ', $expectedType);
        }
        return new static(
            sprintf(
                'Value of type "%s" was expected. Actual type of value is "%s".',
                $expectedType,
                $providedType
            ),
            $code,
            $previous
        );
    }

    public static function unexpectedNull(
        string|array $expectedType,
        int $code = 0,
        ?Throwable $previous = null
    ): static {
        return static::unexpectedType($expectedType, null, $code, $previous);
    }

    public static function unexpectedEmptyString(int $code = 0, ?Throwable $previous = null): static
    {
        return static::unexpectedType('not-empty string', '', $code, $previous);
    }
}
