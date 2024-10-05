<?php

namespace App\UX\Components\LinkButton;

use App\UX\TwigComponent\Traits\DisableTurboPrefetchPropTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('LinkButton', '@components/LinkButton/LinkButton.html.twig')]
class LinkButton
{
    use DisableTurboPrefetchPropTrait;

    public ?string $href = null;
}
