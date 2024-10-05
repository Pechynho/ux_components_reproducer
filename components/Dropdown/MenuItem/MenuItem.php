<?php

namespace App\UX\Components\Dropdown\MenuItem;

use App\UX\TwigComponent\Traits\DisableTurboPrefetchPropTrait;
use App\UX\TwigComponent\Traits\WithLoaderPropsTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Dropdown:MenuItem', '@components/Dropdown/MenuItem/MenuItem.html.twig')]
class MenuItem
{
    use DisableTurboPrefetchPropTrait;
    use WithLoaderPropsTrait;

    public bool $closeOnClick = false;
    public bool $isChecked = false;

    public function getCloseOnClickAttributeName(): string
    {
        return 'data-dropdown-close-on-click';
    }
}
