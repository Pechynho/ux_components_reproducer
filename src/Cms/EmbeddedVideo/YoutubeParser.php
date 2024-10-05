<?php

namespace App\Cms\EmbeddedVideo;

use App\Cms\EmbeddedVideo\Exception\VideoUrlParserException;
use App\Cms\Enum\CmsEmbeddedVideoType;

class YoutubeParser extends AbstractEmbeddedEmbeddedVideoParser
{
    private const array YOUTUBE_DOMAINS = [
        'youtube.com',
        'youtu.be',
    ];

    /**
     * @throws VideoUrlParserException
     */
    public function parse(string $url): ParsedEmbeddedVideoUrl
    {
        $parsedUrl = parse_url($url);
        if (!is_array($parsedUrl)) {
            $this->throwInvalidUrlException($url);
        }
        $host = $parsedUrl['host'] ?? '';
        $path = $parsedUrl['path'] ?? '';
        $query = $parsedUrl['query'] ?? '';
        if (str_contains($host, 'youtube.com')) {
            $videoId = '';
            if (str_contains($path, '/watch')) {
                parse_str($query, $queryParts);
                $videoId = $queryParts['v'] ?? '';
            } elseif (str_contains($path, '/embed/')) {
                $videoId = str_replace('/embed/', '', $path);
            }
            return new ParsedEmbeddedVideoUrl($url, $videoId, CmsEmbeddedVideoType::Youtube);
        }
        if (str_contains($host, 'youtu.be')) {
            $videoId = str_replace('/', '', $path);
            return new ParsedEmbeddedVideoUrl($url, $videoId, CmsEmbeddedVideoType::Youtube);
        }
        $this->throwInvalidUrlException($url);
    }

    public function supports(string $url): bool
    {
        $parsedUrl = parse_url($url);
        if (!is_array($parsedUrl)) {
            return false;
        }
        $host = $parsedUrl['host'] ?? '';
        foreach (self::YOUTUBE_DOMAINS as $domain) {
            if (str_contains($host, $domain)) {
                return true;
            }
        }
        return false;
    }
}
