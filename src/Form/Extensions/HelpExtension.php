<?php

namespace App\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HelpExtension extends AbstractTypeExtension
{
    public const string PLACEMENT_BEFORE_WIDGET = 'before_widget';
    public const string PLACEMENT_AFTER_WIDGET = 'after_widget';

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['help_popover'] = $options['help_popover'];
        $view->vars['help_alert'] = $options['help_alert'];
        $view->vars['help_placement'] = $options['help_placement'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'help_placement' => static::PLACEMENT_AFTER_WIDGET,
                    'help_popover' => false,
                    'help_alert' => false,
                ],
            )
            ->setAllowedTypes('help_popover', 'bool')
            ->setAllowedTypes('help_alert', 'bool')
            ->setAllowedValues('help_placement', [static::PLACEMENT_BEFORE_WIDGET, static::PLACEMENT_AFTER_WIDGET]);
    }
}
