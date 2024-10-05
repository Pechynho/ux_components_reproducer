<?php

namespace App\UX\Stimulus\Twig;
;
use Symfony\UX\StimulusBundle\Dto\StimulusAttributes as Attributes;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class StimulusExtension extends AbstractExtension
{
    public function __construct(
        private readonly StimulusHelper $stimulusHelper,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'stimulus_controller_popover',
                function (
                    string $root = 'body',
                    bool $removeOnHide = true,
                    bool $dropdownBehaviour = false,
                    string $placement = 'top',
                ): Attributes {
                    return $this->popover($root, $removeOnHide, $dropdownBehaviour, $placement);
                },
                ['is_safe' => ['html_attr']],
            ),
            new TwigFunction(
                'stimulus_controller_dropdown',
                function (string $root = 'body', bool $removeOnHide = true, ?string $placement = null): Attributes {
                    return $this->dropdown($root, $removeOnHide, $placement);
                },
                ['is_safe' => ['html_attr']],
            ),
            new TwigFunction(
                'stimulus_controller_event_dispatcher',
                function (array $events): Attributes {
                    return $this->eventDispatcher($events);
                },
                ['is_safe' => ['html_attr']],
            ),
            new TwigFunction(
                'stimulus_controller_value_changed_by_morphdom',
                function (): Attributes {
                    return $this->valueChangedByMorphdom();
                },
                ['is_safe' => ['html_attr']],
            ),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'stimulus_controller_popover',
                function (
                    Attributes $attr,
                    string $root = 'body',
                    bool $removeOnHide = true,
                    bool $dropdownBehaviour = false,
                    string $placement = 'top',
                ): Attributes {
                    return $this->popover($root, $removeOnHide, $dropdownBehaviour, $placement, $attr);
                },
                ['is_safe' => ['html_attr']],
            ),
            new TwigFilter(
                'stimulus_controller_dropdown',
                function (
                    Attributes $attr,
                    string $root = 'body',
                    bool $removeOnHide = true,
                    ?string $placement = null,
                ): Attributes {
                    return $this->dropdown($root, $removeOnHide, $placement, $attr);
                },
                ['is_safe' => ['html_attr']],
            ),
            new TwigFilter(
                'stimulus_controller_event_dispatcher',
                function (Attributes $attr, array $events): Attributes {
                    return $this->eventDispatcher($events, $attr);
                },
                ['is_safe' => ['html_attr']],
            ),
            new TwigFilter(
                'stimulus_controller_value_changed_by_morphdom',
                function (Attributes $attr): Attributes {
                    return $this->valueChangedByMorphdom($attr);
                },
                ['is_safe' => ['html_attr']],
            ),
        ];
    }

    public function popover(
        string $root = 'body',
        bool $removeOnHide = true,
        bool $dropdownBehaviour = false,
        string $placement = 'top',
        ?Attributes $attr = null,
    ): Attributes {
        $attr = $attr ?? $this->stimulusHelper->createStimulusAttributes();
        $attr->addController(
            'popover',
            [
                'root' => $root,
                'removeOnHide' => $removeOnHide,
                'dropdownBehaviour' => $dropdownBehaviour,
                'placement' => $placement,
            ],
            [
                'caretTop' => 'caret-top',
                'caretBottom' => 'caret-bottom',
                'isOpen' => 'is-open',
            ],
        );
        $attr->addAction('popover', 'computePosition', 'resize@window');
        $attr->addAction('popover', 'hide', 'turbo:visit@window');
        if ($dropdownBehaviour) {
            $attr->addAction('popover', 'toggle', 'click');
            $attr->addAction('popover', 'onGlobalClick', 'click@window');
        } else {
            $attr->addAction('popover', 'show', 'mouseenter');
            $attr->addAction('popover', 'hide', 'mouseleave');
            $attr->addAction('popover', 'show', 'focus');
            $attr->addAction('popover', 'hide', 'blur');
        }
        return $attr;
    }

    public function dropdown(
        string $root = 'body',
        bool $removeOnHide = true,
        ?string $placement = null,
        ?Attributes $attr = null,
    ): Attributes {
        $attr = $attr ?? $this->stimulusHelper->createStimulusAttributes();
        $attr->addController(
            'dropdown',
            [
                'root' => $root,
                'removeOnHide' => $removeOnHide,
                'placement' => $placement,
            ],
            [
                'isOpen' => 'is-open',
            ],
        );
        $attr->addAction('dropdown', 'toggle', 'click');
        $attr->addAction('dropdown', 'computePosition', 'resize@window');
        $attr->addAction('dropdown', 'onGlobalClick', 'click@window');
        $attr->addAction('dropdown', 'close', 'turbo:visit@window');
        return $attr;
    }

    /**
     * @param array<string, array<string>> $events
     */
    public function eventDispatcher(array $events, ?Attributes $attr = null): Attributes
    {
        $attr = $attr ?? $this->stimulusHelper->createStimulusAttributes();
        $attr->addController('event-dispatcher', [
            'events' => $events,
        ]);
        return $attr;
    }

    public function valueChangedByMorphdom(?Attributes $attr = null): Attributes
    {
        $attr = $attr ?? $this->stimulusHelper->createStimulusAttributes();
        $attr->addController('value-changed-by-morphdom', [
            'timestamp' => time(),
        ]);
        $attr->addAction('value-changed-by-morphdom', 'storeValue', 'change');
        $attr->addAction('value-changed-by-morphdom', 'storeValue', 'input');
        return $attr;
    }
}
