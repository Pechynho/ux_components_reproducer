<?php

namespace App\UX\Components\Popover\Confirm\Generic;

use App\UX\Components\Popover\Popover\Popover;
use App\UX\TwigComponent\Traits\ConfirmTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Popover:Confirm:Generic', '@components/Popover/Confirm/Generic/Generic.html.twig')]
class Generic extends Popover
{
    use ConfirmTrait;
}
