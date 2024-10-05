<?php

namespace App\UX\Components\Alert;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Alert', '@components/Alert/Alert.html.twig')]
class Alert
{
    public ?string $title = null;
    public string $theme = 'info';
    public bool $autoIcon = true;
    public ?string $icon = null;
    public bool $withCloseButton = true;
    public ?int $autoCloseTimeout = null;
    public bool $removeAfterClose = true;
}
