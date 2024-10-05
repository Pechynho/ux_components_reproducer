<?php

namespace App\UX\Components\Form\Input;

use App\UX\LiveComponent\Traits\DataBindingPropTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Form:Input', '@components/Form/Input/Input.html.twig')]
class Input
{
    use DataBindingPropTrait;

    public string $type = 'text';
    public string | int | float | bool | null $value = null;
    public bool $required = false;
    public bool $valid = true;
    public bool $disabled = false;
    public bool $readOnly = false;
    public ?string $id = null;
    public ?string $name = null;
    public ?string $icon = null;
    public bool $iconAutodetect = true;
}
