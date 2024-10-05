<?php

namespace App\UX\LiveComponent\Traits;

use Symfony\UX\LiveComponent\ComponentWithFormTrait as CoreComponentWithFormTrait;

trait ComponentWithFormTrait
{
    use CoreComponentWithFormTrait;

    private function getDataModelValue(): ?string
    {
        return 'debounce(300)|on(change)|*';
    }
}
