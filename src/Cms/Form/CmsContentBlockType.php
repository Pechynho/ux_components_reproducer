<?php

namespace App\Cms\Form;

use App\Cms\CmsManager;
use App\Cms\EmbeddedVideo\EmbeddedVideoUrlParserInterface;
use App\Cms\Enum\CmsContentBlockType as CmsContentBlockTypeEnum;
use App\Cms\Model\CmsContentBlock;
use App\Exception\UnexpectedValueException;
use App\Form\Type\TextEditorType;
use Closure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

class CmsContentBlockType extends AbstractType
{
    public function __construct(
        private readonly CmsManager $cmsManager,
        private readonly EmbeddedVideoUrlParserInterface $embeddedVideoUrlParser,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addViewTransformer(
            new CallbackTransformer(
                static function (CmsContentBlock | Closure | null $value): ?CmsContentBlock {
                    return $value instanceof Closure ? $value() : $value;
                },
                static fn(mixed $v): mixed => $v,
            ),
        );
        $builder->add('type', CmsContentBlockSelectType::class, [
            'label' => 'Type',
        ]);
        $builder->add('order', HiddenType::class);
        $builder->add('text', TextEditorType::class, [
            'label' => false,
        ]);
        $builder->add('embeddedVideoUrl', TextType::class, [
            'label' => 'URL',
        ]);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            $block = $event->getData();
            if (!$block instanceof CmsContentBlock) {
                throw UnexpectedValueException::unexpectedType(CmsContentBlock::class, $block);
            }
            if ($block->getType()->isEmbeddedVideo()) {
                $embeddedVideoUrl = $block->getEmbeddedVideoUrl();
                try {
                    $parsed = $this->embeddedVideoUrlParser->parse($embeddedVideoUrl);
                    $block->setEmbeddedVideoId($parsed->videoId);
                    $block->setEmbeddedVideoType($parsed->type);
                } catch (Throwable) {
                }
            }
            $event->setData($block);
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['live_component'] = $options['live_component'];
        $types = CmsContentBlockTypeEnum::all();
        $view->vars['types'] = $types;
        foreach ($types as $type) {
            $view->vars[sprintf('CMS_CONTENT_BLOCK_TYPE_%s', strtoupper($type->value))] = $type->value;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'data_class' => CmsContentBlock::class,
                    'empty_data' => fn(): CmsContentBlock => $this->cmsManager->createBlock(),
                    'live_component' => function (OptionsResolver $liveComponentResolver): void {
                        $liveComponentResolver
                            ->setDefaults(
                                [
                                    'enabled' => false,
                                    'remove_block_action_name' => 'removeContentBlock',
                                    'index' => 0,
                                ],
                            )
                            ->setAllowedTypes('enabled', 'bool')
                            ->setAllowedTypes('remove_block_action_name', 'string')
                            ->setAllowedTypes('index', ['int', 'string']);
                    },
                ],
            );
    }
}
