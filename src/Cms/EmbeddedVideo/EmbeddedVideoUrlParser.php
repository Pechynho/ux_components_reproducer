<?php

namespace App\Cms\EmbeddedVideo;

use App\Cms\EmbeddedVideo\Exception\VideoUrlParserException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Throwable;

#[AsAlias(EmbeddedVideoUrlParserInterface::class)]
readonly class EmbeddedVideoUrlParser implements EmbeddedVideoUrlParserInterface
{
    public function __construct(
        #[TaggedIterator(EmbeddedVideoUrlParserInterface::class, excludeSelf: true)] private iterable $parsers,
    ) {}

    /**
     * @throws VideoUrlParserException
     */
    public function parse(string $url): ParsedEmbeddedVideoUrl
    {
        try {
            foreach ($this->parsers as $parser) {
                if ($parser->supports($url)) {
                    return $parser->parse($url);
                }
            }
        } catch (Throwable $e) {
            throw new VideoUrlParserException($e);
        }
        throw new VideoUrlParserException(sprintf('No parser found for url "%s"', $url));
    }

    public function supports(string $url): bool
    {
        return true;
    }
}
