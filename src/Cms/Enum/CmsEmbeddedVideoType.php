<?php

namespace App\Cms\Enum;

enum CmsEmbeddedVideoType: string
{
    case Youtube = 'Youtube';
    case Vimeo = 'Vimeo';

    public function isYoutube(): bool
    {
        return $this->value === self::Youtube->value;
    }

    public function isVimeo(): bool
    {
        return $this->value === self::Vimeo->value;
    }
}
