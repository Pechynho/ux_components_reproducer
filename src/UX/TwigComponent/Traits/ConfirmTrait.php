<?php

namespace App\UX\TwigComponent\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\PreMount;

trait ConfirmTrait
{
    public string $text;
    public string $declineButton;
    public string $confirmButton;
    public ?string $declineUrl;
    public ?string $confirmUrl;
    public bool $textHtml;
    public bool $declineButtonHtml;
    public bool $confirmButtonHtml;

    #[PreMount]
    public function preMount(array $data): array
    {
        return (new OptionsResolver())
                ->setIgnoreUndefined()
                ->setDefaults(
                    [
                        'text' => $this->getDefaultText(),
                        'declineButton' => $this->getDefaultDeclineButton(),
                        'confirmButton' => $this->getDefaultConfirmButton(),
                        'declineUrl' => null,
                        'confirmUrl' => null,
                        'textHtml' => false,
                        'declineButtonHtml' => false,
                        'confirmButtonHtml' => false,
                    ]
                )
                ->setAllowedTypes('text', 'string')
                ->setAllowedTypes('declineButton', 'string')
                ->setAllowedTypes('confirmButton', 'string')
                ->setAllowedTypes('declineUrl', ['string', 'null'])
                ->setAllowedTypes('confirmUrl', ['string', 'null'])
                ->setAllowedTypes('textHtml', 'bool')
                ->setAllowedTypes('declineButtonHtml', 'bool')
                ->setAllowedTypes('confirmButtonHtml', 'bool')
                ->resolve($data) + $data;
    }

    protected function getDefaultText(): string
    {
        return 'Do you really want to do this?';
    }

    protected function getDefaultDeclineButton(): string
    {
        return 'Cancel';
    }

    protected function getDefaultConfirmButton(): string
    {
        return 'Continue';
    }
}
