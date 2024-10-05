<?php

namespace App\Cms\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CmsContent
{
    /** @var Collection<int, CmsContentBlock> */
    private Collection $blocks;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    /**
     * @return Collection<int, CmsContentBlock>
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * @param Collection<int, CmsContentBlock> $blocks
     * @return $this
     */
    public function setBlocks(Collection $blocks): static
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @return $this
     */
    public function addBlock(CmsContentBlock $block): static
    {
        $this->blocks->add($block);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeBlock(CmsContentBlock $block): static
    {
        $this->blocks->removeElement($block);
        return $this;
    }
}
