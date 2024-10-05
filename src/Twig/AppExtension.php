<?php

namespace App\Twig;

use App\Exception\RuntimeException;
use App\Utils\Arrays;
use App\Utils\Strings;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\StimulusBundle\Dto\StimulusAttributes;
use Symfony\UX\TwigComponent\ComponentAttributes;
use Throwable;
use Traversable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;
use UnitEnum;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generate_element_id', $this->generateElementId(...)),
            new TwigFunction('html_attributes', $this->htmlAttributes(...)),
            new TwigFunction('component_attributes', $this->componentAttributes(...)),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('translate_enum', $this->translateEnum(...)),
            new TwigFilter('array_prefix_keys', $this->arrayPrefixKeys(...)),
            new TwigFilter('html_attributes', $this->htmlAttributes(...)),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('empty_or_white_space', Strings::isEmptyOrWhiteSpace(...)),
            new TwigTest('null_or_white_space', Strings::isNullOrWhiteSpace(...)),
        ];
    }

    public function translateEnum(UnitEnum $enum): string
    {
        if ($enum instanceof TranslatableInterface) {
            return $enum->trans($this->translator, 'en');
        }
        return $enum->value;
    }

    public function generateElementId(string $prefix = 'element'): string
    {
        try {
            return $prefix . '_' . random_int(0, 1000000);
        } catch (Throwable $e) {
            throw new RuntimeException($e);
        }
    }

    public function htmlAttributes(iterable...$args): array
    {
        $result = [];
        $mergeKeys = ['class' => true, 'data-controller' => true, 'data-action' => true];
        foreach ($args as $arg) {
            if ($arg instanceof StimulusAttributes) {
                $arg = $arg->toEscapedArray();
            } elseif ($arg instanceof ComponentAttributes) {
                $arg = $arg->all();
            } elseif ($arg instanceof Traversable) {
                $arg = iterator_to_array($arg);
            }
            foreach ($arg as $key => $value) {
                if (isset($result[$key], $mergeKeys[$key])) {
                    $result[$key] = sprintf('%s %s', $result[$key], $value);
                    continue;
                }
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function componentAttributes(iterable ...$arts): ComponentAttributes
    {
        return new ComponentAttributes($this->htmlAttributes(...$arts));
    }

    private function arrayPrefixKeys(iterable $subject, string $prefix): array
    {
        return Arrays::prefixKeys($this->htmlAttributes($subject), $prefix);
    }
}
