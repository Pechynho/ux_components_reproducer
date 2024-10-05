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
            return new GroupSequence([$shortName, 'Text']);
        }
        if ($type->isEmbeddedVideo()) {
            return new GroupSequence([$shortName, ['EmbeddedVideoURL', 'EmbeddedVideoId']]);
        }
        if ($type->isImage()) {
            return new GroupSequence([$shortName, ['File', 'Image']]);
        }
        if ($type->isVideo()) {
            return new GroupSequence([$shortName, ['File', 'Video']]);
        }
        if ($type->isGallery()) {
            return new GroupSequence([$shortName, ['FilesCount', 'Images']]);
        }
        if ($type->isFiles()) {
            return new GroupSequence([$shortName, ['FilesCount', 'Files']]);
        }
        throw new NotImplementedException();
    }
}
