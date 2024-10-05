<?php

namespace App\Form\Type;

use App\UX\Components\Form\TextEditor\TextEditor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextEditorType extends AbstractType
{
    public function getParent(): string
    {
        return TextareaType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['mode'] = $options['mode'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'sanitize_html' => true,
                    'sanitizer' => 'app.text_editor',
                    'mode' => TextEditor::MODE_STANDARD,
                ],
            )
            ->setAllowedValues('mode', TextEditor::MODES);
    }
}
