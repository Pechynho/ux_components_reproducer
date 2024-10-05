<?php

namespace App\Twig;

use App\Utils\Strings;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class AppExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('empty_or_white_space', Strings::isEmptyOrWhiteSpace(...)),
            new TwigTest('null_or_white_space', Strings::isNullOrWhiteSpace(...)),
        ];
    }
}
