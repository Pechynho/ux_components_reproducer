<?php

namespace App\Form\Extensions;

use App\UX\Stimulus\Twig\StimulusExtension;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly StimulusExtension $stimulusExtension,
    ) {}

    public static function getExtendedTypes(): iterable
    {
        return [DateTimeType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr = $this->stimulusExtension->valueChangedByMorphdom();
        $attr->addController('datetime-picker', [
            'format' => 'Y-m-d H:i',
            'enableTime' => true,
            'alternativeFormat' => sprintf('%s H:i', 'd.m.Y'),
        ]);
        $attr->addAction('datetime-picker', 'setupStyles', 'dark-mode:changed@window');
        $attr->addAction('datetime-picker', 'synchronize', 'value-changed-by-morphdom');
        $view->vars['attr'] = array_merge($view->vars['attr'], $attr->toArray());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'html5' => false,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'format' => 'yyyy-MM-dd HH:mm',
            ],
        );
    }
}
