<?php

namespace App\UX\Components\List\List;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('List', '@components/List/List/List.html.twig')]
class ListComponent
{
    public string $type = 'disc';
}
