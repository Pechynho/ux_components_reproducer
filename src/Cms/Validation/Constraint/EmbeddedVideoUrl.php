<?php

namespace App\Cms\Validation\Constraint;

use App\Cms\Validation\Validator\EmbeddedVideoUrlValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class EmbeddedVideoUrl extends Constraint
{
    public string $message = 'Value "{{ value }}" is not a valid embedded video URL.';

    public const string IS_INVALID_ERROR = '139b55d4-0a9d-4ee4-9e6c-713c7c5348e6';
    protected const array ERROR_NAMES = [
        self::IS_INVALID_ERROR => 'IS_INVALID_ERROR',
    ];

    public function __construct(
        ?string $message = null,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        $this->message = $message ?? $this->message;
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): array
    {
        return [
            static::PROPERTY_CONSTRAINT,
            static::CLASS_CONSTRAINT,
        ];
    }

    public function validatedBy(): string
    {
        return EmbeddedVideoUrlValidator::class;
    }
}
