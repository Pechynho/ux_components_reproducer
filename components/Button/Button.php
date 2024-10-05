<?php

namespace App\UX\Components\Button;

use App\UX\LiveComponent\Traits\SkipMorphPropTrait;
use App\UX\TwigComponent\Traits\WithLoaderPropsTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Button', '@components/Button/Button.html.twig')]
class Button
{
    use SkipMorphPropTrait;
    use WithLoaderPropsTrait;

    public string $theme = 'brand';
    public string $size = 'default';
    public bool $formNoValidate = false;
    public bool $square = false;
}
