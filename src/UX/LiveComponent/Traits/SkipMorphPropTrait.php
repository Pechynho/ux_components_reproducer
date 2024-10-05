<?php

namespace App\UX\LiveComponent\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\PreMount;

trait SkipMorphPropTrait
{
    public bool $skipMorph;

    #[PreMount]
    public function skipMorphPreMount(array $array): array
    {
        return (new OptionsResolver())
                   ->setIgnoreUndefined()
                   ->setDefaults(
                       [
                           'skipMorph' => false,
                       ],
                   )
                   ->setAllowedTypes('skipMorph', 'bool')
                   ->resolve($array) + $array;
    }
}
