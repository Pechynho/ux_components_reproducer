<?php

namespace App\Cms\Validation\GroupProvider;

use App\Cms\Model\CmsContentBlock;
use App\Exception\InvalidArgumentException;
use App\Exception\NotImplementedException;
use App\Utils\Strings;
use ReflectionClass;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\GroupProviderInterface;

class CmsContentBlockGroupProvider implements GroupProviderInterface
{
    public function getGroups(object $object): GroupSequence
    {
        if (!$object instanceof CmsContentBlock) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected object of type "%s", but got "%s".',
                    CmsContentBlock::class,
                    Strings::varToString($object),
                ),
            );
        }
        $type = $object->getType();
        $shortName = (new ReflectionClass($object))->getShortName();
        if ($type->isTextEditor()) {
            die;
            return new GroupSequence([$shortName, 'Text']);
        }
        if ($type->isEmbeddedVideo()) {
            return new GroupSequence([$shortName, ['EmbeddedVideoURL', 'EmbeddedVideoId']]);
        }
        throw new NotImplementedException();
    }
}
