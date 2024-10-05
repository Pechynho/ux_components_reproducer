<?php

namespace App\Cms\EmbeddedVideo;

use App\Cms\EmbeddedVideo\Exception\VideoUrlParserException;
use App\Cms\Enum\CmsEmbeddedVideoType;

class VimeoParser extends AbstractEmbeddedEmbeddedVideoParser
{
    private const string VIMEO_DOMAIN = 'vimeo.com';

    /**
     * @throws VideoUrlParserException
     */
    public function parse(string $url): ParsedEmbeddedVideoUrl
    {
        $parsedUrl = parse_url($url);
        if (!is_array($parsedUrl)) {
            $this->throwInvalidUrlException($url);
        }
        $path = $parsedUrl['path'] ?? '';
        $pathParts = explode('/', $path);
        $videoId = $pathParts[array_key_last($pathParts)] ?? '';
        if (preg_match('/^\d+$/', $videoId) !== 1) {
            $this->throwInvalidUrlException($url);
        }
        return new ParsedEmbeddedVideoUrl($url, $videoId, CmsEmbeddedVideoType::Vimeo);
    }

    public function supports(string $url): bool
    {
        $parsedUrl = parse_url($url);
        if (!is_array($parsedUrl)) {
            return false;
        }
        $host = $parsedUrl['host'] ?? '';
        return str_contains($host, static::VIMEO_DOMAIN);
    }
}
