<?php

namespace App\Validation\ValidEmbeddable\Contracts;

use App\Validation\ValidEmbeddable\Constraint\ValidEmbeddable;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Context\ExecutionContextInterface as ContextInterface;

#[AutoconfigureTag]
interface ValidEmbeddableGroupsProviderInterface
{
    public function getValidationGroups(
        mixed $value,
        ContextInterface $context,
        ValidEmbeddable $constraint,
    ): GroupSequence | string | array | null;
}
