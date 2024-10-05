<?php

namespace App\Cms\UX\LiveComponent\Traits;

use App\Cms\CmsManager;
use App\Cms\Model\CmsContent;
use App\UX\LiveComponent\Traits\ComponentWithFormTrait;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;

trait ComponentWithCmsContentTrait
{
    use ComponentWithFormTrait;

    protected readonly CmsManager $cmsManager;
    protected readonly PropertyAccessorInterface $propertyAccessor;

    #[Required]
    public function setCmsManager(CmsManager $cmsManager): void
    {
        if (!isset($this->cmsManager)) {
            $this->cmsManager = $cmsManager;
        }
    }

    #[Required]
    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor): void
    {
        if (!isset($this->propertyAccessor)) {
            $this->propertyAccessor = $propertyAccessor;
        }
    }

    #[LiveAction]
    public function addContentBlock(#[LiveArg] string $type): void
    {
        $path = $this->getCmsContentBlocksPath();
        $blocks = $this->propertyAccessor->getValue($this->formValues, $path);
        if ($blocks === null) {
            $blocks = [];
        }
        $content = $this->getCmsContent();
        $block = $this->cmsManager->createBlock($content);
        $order = $content !== null ? $block->getOrder() : count($blocks);
        $blocks[] = ['type' => $type, 'order' => $order];
        $this->propertyAccessor->setValue($this->formValues, $path, $blocks);
    }

    #[LiveAction]
    public function removeContentBlock(#[LiveArg] int $index): void
    {
        $path = $this->getCmsContentBlocksPath();
        $blocks = $this->propertyAccessor->getValue($this->formValues, $path);
        if ($blocks === null) {
            return;
        }
        unset($blocks[$index]);
        $this->propertyAccessor->setValue($this->formValues, $path, $blocks);
    }

    abstract private function getCmsContent(): ?CmsContent;

    private function getCmsContentBlocksPath(): PropertyPathInterface
    {
        return new PropertyPath('[content][blocks]');
    }
}
