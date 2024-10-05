<?php

namespace App\Page\Validation;

use App\Exception\InvalidArgumentException;
use App\Page\Model\DynamicPage;
use App\Utils\Strings;
use ReflectionObject;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\GroupProviderInterface;

class DynamicPageGroupProvider implements GroupProviderInterface
{
    public function getGroups(object $object): GroupSequence
    {
        if (!$object instanceof DynamicPage) {
            throw new InvalidArgumentException(
                sprintf('Expected instance of %s, got %s', DynamicPage::class, Strings::varToString($object)),
            );
        }
        $groups = [(new ReflectionObject($object))->getShortName()];
        if ($object->isRestrictAccess()) {
            $groups[] = 'AccessibleFrom';
            $groups[] = 'AccessibleTo';
        }
        return new GroupSequence([$groups]);
    }
}
