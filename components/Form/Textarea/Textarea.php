<?php

namespace App\UX\Components\Form\Textarea;

use App\UX\LiveComponent\Traits\DataBindingPropTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Form:Textarea', '@components/Form/Textarea/Textarea.html.twig')]
class Textarea
{
    use DataBindingPropTrait;

    public bool $required = false;
    public bool $valid = true;
    public bool $disabled = false;
    public bool $readOnly = false;
    public ?string $id = null;
    public ?string $name = null;
}
