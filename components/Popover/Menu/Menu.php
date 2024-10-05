<?php

namespace App\UX\Components\Popover\Menu;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Popover:Menu', '@components/Dropdown/Menu/Menu.html.twig')]
class Menu extends \App\UX\Components\Dropdown\Menu\Menu
{
}
