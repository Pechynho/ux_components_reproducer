<?php

namespace App\Page\Model;

use App\Cms\Model\CmsContent;
use App\Page\Validation\DynamicPageGroupProvider;
use App\Validation\ValidEmbeddable\Constraint\ValidEmbeddable;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints;

#[Constraints\GroupSequenceProvider(DynamicPageGroupProvider::class)]
class DynamicPage
{
    #[Constraints\Sequentially([
        new Constraints\NotBlank(),
        new Constraints\Length(max: 255),
    ])]
    private string $title;

    #[Constraints\Sequentially([
        new Constraints\NotBlank(),
        new Constraints\Length(max: 255),
    ])]
    private string $slug;

    private bool $restrictAccess;

    #[Constraints\NotBlank(groups: ['AccessibleFrom'])]
    private ?DateTimeImmutable $accessibleFrom;

    #[Constraints\NotBlank(groups: ['AccessibleTo'])]
    private ?DateTimeImmutable $accessibleTo;

    #[ValidEmbeddable]
    private CmsContent $content;

    public function __construct(
        string $title,
        string $slug,
        bool $restrictAccess,
        ?DateTimeImmutable $accessibleFrom,
        ?DateTimeImmutable $accessibleTo,
        CmsContent $content,
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->restrictAccess = $restrictAccess;
        $this->accessibleFrom = $accessibleFrom;
        $this->accessibleTo = $accessibleTo;
        $this->content = $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function isRestrictAccess(): bool
    {
        return $this->restrictAccess;
    }

    /**
     * @return $this
     */
    public function setRestrictAccess(bool $restrictAccess): static
    {
        $this->restrictAccess = $restrictAccess;
        return $this;
    }

    public function getAccessibleFrom(): ?DateTimeImmutable
    {
        return $this->accessibleFrom;
    }

    /**
     * @return $this
     */
    public function setAccessibleFrom(?DateTimeImmutable $accessibleFrom): static
    {
        $this->accessibleFrom = $accessibleFrom;
        return $this;
    }

    public function getAccessibleTo(): ?DateTimeImmutable
    {
        return $this->accessibleTo;
    }

    /**
     * @return $this
     */
    public function setAccessibleTo(?DateTimeImmutable $accessibleTo): static
    {
        $this->accessibleTo = $accessibleTo;
        return $this;
    }

    public function getContent(): CmsContent
    {
        return $this->content;
    }

    /**
     * @return $this
     */
    public function setContent(CmsContent $content): static
    {
        $this->content = $content;
        return $this;
    }
}
