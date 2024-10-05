<?php

namespace App\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class IntegerTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [
            IntegerType::class,
        ];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['type'] = 'text';
        $attr = $view->vars['attr'] ?? [];
        $attr['inputmode'] = 'numeric';
        $attr['pattern'] = '[0-9]*';
        $view->vars['attr'] = $attr;
    }
}
