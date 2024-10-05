<?php

namespace App\Cms\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CmsContentBlockType: string implements TranslatableInterface
{
    case TextEditor = 'TEXT_EDITOR';
    case EmbeddedVideo = 'EMBEDDED_VIDEO';

    public function isTextEditor(): bool
    {
        return $this->value === self::TextEditor->value;
    }

    public function isEmbeddedVideo(): bool
    {
        return $this->value === self::EmbeddedVideo->value;
    }

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            self::TextEditor => 'Text editor',
            self::EmbeddedVideo => 'Embedded video',
        };
    }
}
