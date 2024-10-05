<?php

namespace App\UX\Components\Dropdown\Dropdown;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Dropdown', '@components/Dropdown/Dropdown/Dropdown.html.twig')]
class Dropdown
{
    public function getController(): string
    {
        return 'dropdown';
    }
}
