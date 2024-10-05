<?php

namespace App\UX\Components\JavaScriptConfig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('JavaScriptConfig', '@components/JavaScriptConfig/JavaScriptConfig.html.twig')]
readonly class JavaScriptConfig
{
    public function getData(): array
    {
        return [
            'common' => [
                'locale' => 'en',
            ],
        ];
    }
}
