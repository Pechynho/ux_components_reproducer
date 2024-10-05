<?php

namespace App\Cms\EmbeddedVideo;

use App\Cms\Enum\CmsEmbeddedVideoType;

readonly class ParsedEmbeddedVideoUrl
{
    public function __construct(
        public string $url,
        public string $videoId,
        public CmsEmbeddedVideoType $type,
    ) {}

    public function __toString(): string
    {
        return $this->url;
    }
}
