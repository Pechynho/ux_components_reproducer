<?php

namespace App\UX\Components\Form\Label;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Form:Label', '@components/Form/Label/Label.html.twig')]
class Label
{
    public bool $required = false;
    public bool $valid = true;
}
