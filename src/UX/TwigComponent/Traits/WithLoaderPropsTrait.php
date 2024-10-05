<?php

namespace App\UX\TwigComponent\Traits;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\PreMount;

trait WithLoaderPropsTrait
{
    public bool $withLoader = false;
    public bool $onlyOpacityLoader = false;
    public array $loaderActions = [];

    #[PreMount]
    public function withLoaderPropsPreMount(array $data): array
    {
        return (new OptionsResolver())
                   ->setIgnoreUndefined()
                   ->setDefaults(
                       [
                           'loaderActions' => [],
                       ],
                   )
                   ->setNormalizer('loaderActions', static function (Options $options, array | string $value) {
                       return is_string($value) ? explode(' ', $value) : $value;
                   })
                   ->setAllowedTypes('loaderActions', ['string[]', 'string'])
                   ->resolve($data) + $data;
    }
}
