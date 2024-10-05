<?php

namespace App\Cms\EmbeddedVideo;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface EmbeddedVideoUrlParserInterface
{
    public function parse(string $url): ParsedEmbeddedVideoUrl;

    public function supports(string $url): bool;
}
