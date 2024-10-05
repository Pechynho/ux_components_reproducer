<?php

namespace App\UX\Components\Form\Checkbox;

use App\UX\LiveComponent\Traits\DataBindingPropTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Form:Checkbox', '@components/Form/Checkbox/Checkbox.html.twig')]
class Checkbox
{
    use DataBindingPropTrait;

    public ?string $value = null;
    public bool $required = false;
    public bool $valid = true;
    public bool $disabled = false;
    public ?string $id = null;
    public ?string $name = null;
    public bool $checked = false;
    public string|false|null $label = false;
    public bool $labelHtml = false;
    public string|bool|null $help = false;
    public bool $helpHtml = false;
}
