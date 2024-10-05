<?php

namespace App\Cms\Form;

use App\Cms\CmsManager;
use App\Cms\Model\CmsContent;
use App\Cms\Model\CmsContentBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsContentType extends AbstractType
{
    public function __construct(
        private readonly CmsManager $cmsManager,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $content = $builder->getData();
        $content = $content instanceof CmsContent ? $content : null;
        $builder->add('blocks', CollectionType::class, [
            'label' => false,
            'entry_type' => CmsContentBlockType::class,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'prototype' => true,
            'prototype_data' => fn(): CmsContentBlock => $this->cmsManager->createBlock($content),
            'entry_options' => [
                'required' => false,
                'label' => false,
            ],
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['content'] = $form->getData();
        $view->vars['live_component'] = $options['live_component'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'label' => 'cms.content.blocks.label',
                    'help' => 'cms.content.blocks.help',
                    'help_placement' => 'before_widget',
                    'help_alert' => true,
                    'data_class' => CmsContent::class,
                    'empty_data' => fn(): CmsContent => $this->cmsManager->createContent(),
                    'live_component' => function (OptionsResolver $liveComponentResolver): void {
                        $liveComponentResolver
                            ->setDefaults(
                                [
                                    'enabled' => false,
                                    'add_block_action_name' => 'addContentBlock',
                                    'remove_block_action_name' => 'removeContentBlock',
                                ],
                            )
                            ->setAllowedTypes('enabled', 'bool')
                            ->setAllowedTypes('add_block_action_name', 'string');
                    },
                ],
            );
    }
}
