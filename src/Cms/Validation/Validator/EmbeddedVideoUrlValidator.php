<?php

namespace App\Cms\Validation\Validator;

use App\Cms\EmbeddedVideo\EmbeddedVideoUrlParserInterface;
use App\Cms\Validation\Constraint\EmbeddedVideoUrl;
use App\Utils\Strings;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Throwable;

class EmbeddedVideoUrlValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EmbeddedVideoUrlParserInterface $embeddedVideoUrlParser,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EmbeddedVideoUrl) {
            throw new UnexpectedTypeException($constraint, EmbeddedVideoUrl::class);
        }
        if ($value === null) {
            return;
        }
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        try {
            $url = $this->embeddedVideoUrlParser->parse($value);
            if (Strings::isNullOrWhiteSpace($url->url) || Strings::isNullOrWhiteSpace($url->videoId)) {
                $this->buildViolation($constraint, $value);
            }
        } catch (Throwable) {
            $this->buildViolation($constraint, $value);
        }
    }

    private function buildViolation(EmbeddedVideoUrl $constraint, string $value): void
    {
        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setCode(EmbeddedVideoUrl::IS_INVALID_ERROR)
            ->addViolation();
    }
}
