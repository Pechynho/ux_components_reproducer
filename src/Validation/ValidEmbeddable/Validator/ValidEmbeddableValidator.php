<?php

namespace App\Validation\ValidEmbeddable\Validator;

use App\Exception\RuntimeException;
use App\Utils\Strings;
use App\Validation\ValidEmbeddable\Constraint\ValidEmbeddable;
use App\Validation\ValidEmbeddable\Contracts\ValidEmbeddableGroupsProviderInterface as GroupsProvider;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\GroupProviderInterface;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Throwable;

class ValidEmbeddableValidator extends ConstraintValidator
{
    public function __construct(
        /** @var iterable<GroupsProvider> */
        #[TaggedIterator(GroupsProvider::class)]
        private readonly iterable $groupsProviders,
        #[TaggedLocator('validator.group_provider')]
        private readonly ContainerInterface $groupProviderLocator,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidEmbeddable) {
            throw new UnexpectedTypeException($constraint, ValidEmbeddable::class);
        }
        if (null === $value) {
            return;
        }
        if ($constraint->traverseIfPossible && is_iterable($value)) {
            foreach ($value as $key => $item) {
                $this->context
                    ->getValidator()
                    ->inContext($this->context)
                    ->atPath('[' . $key . ']')
                    ->validate($item, null, $this->getGroups($item, $constraint));
            }
            return;
        }
        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->validate($value, null, $this->getGroups($value, $constraint));
    }

    private function getGroupsProvider(ValidEmbeddable $validEmbeddable): ?GroupsProvider
    {
        if (Strings::isNullOrWhiteSpace($validEmbeddable->groupsProvider)) {
            return null;
        }
        foreach ($this->groupsProviders as $groupsProvider) {
            if (is_a($groupsProvider, $validEmbeddable->groupsProvider)) {
                return $groupsProvider;
            }
        }
        return null;
    }

    private function getGroupsViaClassMetadata(object $value): array | Constraints\GroupSequence | false
    {
        $metadata = $this->context->getValidator()->getMetadataFor($value);
        if (!$metadata instanceof ClassMetadataInterface) {
            throw new RuntimeException(
                sprintf(
                    'Expected metadata of type %s, got %s',
                    ClassMetadataInterface::class,
                    Strings::varToString($metadata),
                ),
            );
        }
        if ($metadata->hasGroupSequence()) {
            return $metadata->getGroupSequence();
        }
        if ($metadata->isGroupSequenceProvider() && $value instanceof GroupSequenceProviderInterface) {
            return $value->getGroupSequence();
        }
        if ($metadata->isGroupSequenceProvider() && null !== $providerClass = $metadata->getGroupProvider()) {
            try {
                $provider = $this->groupProviderLocator->get($providerClass);
            } catch (Throwable $e) {
                throw new RuntimeException($e);
            }
            if ($provider instanceof GroupProviderInterface) {
                return $provider->getGroups($value);
            }
        }
        return false;
    }

    private function getGroups(
        mixed $value,
        ValidEmbeddable $constraint,
    ): array | Constraints\GroupSequence | string | null {
        $fallback = $constraint->embeddedGroups;
        $groupsProvider = $this->getGroupsProvider($constraint);
        if ($groupsProvider !== null) {
            return $groupsProvider->getValidationGroups($value, $this->context, $constraint);
        }
        if ($constraint->useClassMetadata) {
            $groups = $this->getGroupsViaClassMetadata($value) ?? $fallback;
            return $groups === false ? $fallback : $groups;
        }
        return $fallback;
    }
}
