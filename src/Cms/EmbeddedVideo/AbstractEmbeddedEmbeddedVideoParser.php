<?php

namespace App\Cms\EmbeddedVideo;

use App\Cms\EmbeddedVideo\Exception\VideoUrlParserException;

abstract class AbstractEmbeddedEmbeddedVideoParser implements EmbeddedVideoUrlParserInterface
{
    /**
     * @throws VideoUrlParserException
     */
    protected function throwInvalidUrlException(string $url): void
    {
        throw new VideoUrlParserException(sprintf('Invalid URL: %s', $url));
    }
}
