<?php

namespace App\Validation\ValidEmbeddable\Constraint;

use App\Validation\ValidEmbeddable\Contracts\ValidEmbeddableGroupsProviderInterface;
use App\Validation\ValidEmbeddable\Validator\ValidEmbeddableValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class ValidEmbeddable extends Constraint
{
    public array | GroupSequence $embeddedGroups = [Constraint::DEFAULT_GROUP];
    public ?string $groupsProvider;
    public bool $useClassMetadata = false;
    public bool $traverseIfPossible = true;

    /**
     * @param array<string> $embeddedGroups
     * @param class-string<ValidEmbeddableGroupsProviderInterface>|null $groupsProvider
     */
    public function __construct(
        array | GroupSequence | null $embeddedGroups = null,
        ?string $groupsProvider = null,
        ?bool $useClassMetadata = null,
        ?bool $traverseIfPossible = null,
        array $options = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        $this->embeddedGroups = $embeddedGroups ?? $this->embeddedGroups;
        $this->groupsProvider = $groupsProvider;
        $this->useClassMetadata = $useClassMetadata ?? $this->useClassMetadata;
        $this->traverseIfPossible = $traverseIfPossible ?? $this->traverseIfPossible;
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): array
    {
        return [
            static::CLASS_CONSTRAINT,
            static::PROPERTY_CONSTRAINT,
        ];
    }

    public function validatedBy(): string
    {
        return ValidEmbeddableValidator::class;
    }
}
