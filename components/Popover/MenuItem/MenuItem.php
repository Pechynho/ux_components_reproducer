<?php

namespace App\UX\Components\Popover\MenuItem;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Popover:MenuItem', '@components/Dropdown/MenuItem/MenuItem.html.twig')]
class MenuItem extends \App\UX\Components\Dropdown\MenuItem\MenuItem
{
    public function getCloseOnClickAttributeName(): string
    {
        return 'data-popover-close-on-click';
    }
}
