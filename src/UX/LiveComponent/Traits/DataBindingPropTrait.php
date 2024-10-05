<?php

namespace App\UX\LiveComponent\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\PreMount;

trait DataBindingPropTrait
{
    public ?string $dataBinding;

    #[PreMount]
    public function dataBindingPreMount(array $data): array
    {
        return (new OptionsResolver())
                   ->setIgnoreUndefined()
                   ->setDefaults(
                       [
                           'dataBinding' => null,
                       ],
                   )
                   ->setAllowedTypes('dataBinding', ['string', 'null'])
                   ->resolve($data) + $data;
    }
}
