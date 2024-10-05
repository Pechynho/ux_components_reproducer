<?php

namespace App\Cms\Model;

use App\Cms\Enum\CmsContentBlockType;
use App\Cms\Enum\CmsEmbeddedVideoType;
use App\Cms\Validation\Constraint\EmbeddedVideoUrl;
use App\Cms\Validation\GroupProvider\CmsContentBlockGroupProvider;
use Symfony\Component\Validator\Constraints;

#[Constraints\GroupSequenceProvider(CmsContentBlockGroupProvider::class)]
class CmsContentBlock
{
    private CmsContentBlockType $type;

    #[Constraints\Sequentially([
        new Constraints\NotBlank(),
        new Constraints\Length(min: 1, max: 25000),
    ], ['Text'])]
    private ?string $text;

    #[Constraints\Sequentially([
        new Constraints\NotBlank(),
        new Constraints\Length(min: 1, max: 255),
        new EmbeddedVideoUrl(),
    ], ['EmbeddedVideoURL'])]
    private ?string $embeddedVideoUrl = null;

    #[Constraints\Sequentially([
        new Constraints\NotBlank(),
        new Constraints\Length(min: 1, max: 255),
    ], ['EmbeddedVideoId'])]
    private ?string $embeddedVideoId = null;

    private ?CmsEmbeddedVideoType $embeddedVideoType = null;

    private int $order;

    public function __construct(CmsContentBlockType $type, int $order)
    {
        $this->type = $type;
        $this->order = $order;
    }

    public function getType(): CmsContentBlockType
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function setType(CmsContentBlockType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return $this
     */
    public function setText(?string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getEmbeddedVideoUrl(): ?string
    {
        return $this->embeddedVideoUrl;
    }

    /**
     * @return $this
     */
    public function setEmbeddedVideoUrl(?string $embeddedVideoUrl): static
    {
        $this->embeddedVideoUrl = $embeddedVideoUrl;
        return $this;
    }

    public function getEmbeddedVideoId(): ?string
    {
        return $this->embeddedVideoId;
    }

    /**
     * @return $this
     */
    public function setEmbeddedVideoId(?string $embeddedVideoId): static
    {
        $this->embeddedVideoId = $embeddedVideoId;
        return $this;
    }

    public function getEmbeddedVideoType(): ?CmsEmbeddedVideoType
    {
        return $this->embeddedVideoType;
    }

    /**
     * @return $this
     */
    public function setEmbeddedVideoType(?CmsEmbeddedVideoType $embeddedVideoType): static
    {
        $this->embeddedVideoType = $embeddedVideoType;
        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return $this
     */
    public function setOrder(int $order): static
    {
        $this->order = $order;
        return $this;
    }
}
