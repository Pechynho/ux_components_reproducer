<?php

namespace App\UX\Components\Page\DynamicPage\CreateForm;

use App\Cms\Form\CmsContentType;
use App\Cms\Model\CmsContent;
use App\Cms\UX\LiveComponent\Traits\ComponentWithCmsContentTrait;
use App\Page\DynamicPageManager;
use App\UX\LiveComponent\Traits\ComponentWithFormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('Page:DynamicPage:CreateForm', '@components/Page/DynamicPage/CreateForm/CreateForm.html.twig')]
class CreateForm extends AbstractController
{
    use ComponentWithFormTrait;
    use ComponentWithCmsContentTrait;
    use DefaultActionTrait;

    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly DynamicPageManager $dynamicPageManager,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        $data = $this->dynamicPageManager->createOne();
        $builder = $this->formFactory->createNamedBuilder('page_dynamic_page_create_form', data: $data);
        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'empty_data' => '',
        ]);
        $builder->add('slug', TextType::class, [
            'label' => 'Slug',
            'empty_data' => '',
        ]);
        $builder->add('restrictAccess', CheckboxType::class, [
            'label' => 'Restrict access',
            'required' => false,
        ]);
        $builder->add('accessibleFrom', DateTimeType::class, [
            'label' => 'Accessible from',
            'required' => false,
        ]);
        $builder->add('accessibleTo', DateTimeType::class, [
            'label' => 'Accessible to',
            'required' => false,
        ]);
        $builder->add('content', CmsContentType::class, [
            'live_component' => [
                'enabled' => true,
            ],
        ]);
        return $builder->getForm();
    }

    #[LiveAction]
    public function save(): ?RedirectResponse
    {
        $this->submitForm();
        return $this->redirectToRoute('app_homepage');
    }

    private function getCmsContent(): ?CmsContent
    {
        return null;
    }
}
