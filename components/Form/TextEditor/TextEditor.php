<?php

namespace App\UX\Components\Form\TextEditor;

use App\UX\LiveComponent\Traits\DataBindingPropTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('Form:TextEditor', '@components/Form/TextEditor/TextEditor.html.twig')]
class TextEditor
{
    use DataBindingPropTrait;

    public bool $required = false;
    public bool $valid = true;
    public bool $disabled = false;
    public bool $readOnly = false;
    public ?string $id = null;
    public ?string $name = null;
    public string $mode;

    public const string MODE_SIMPLE = 'simple';
    public const string MODE_STANDARD = 'standard';
    public const array MODES = [self::MODE_SIMPLE, self::MODE_STANDARD];

    #[PreMount]
    public function preMount(array $data): array
    {
        return (new OptionsResolver())
                   ->setIgnoreUndefined()
                   ->setDefaults(
                       [
                           'mode' => static::MODE_STANDARD,
                       ],
                   )
                   ->setAllowedValues('mode', static::MODES)
                   ->resolve($data) + $data;
    }
}
