<?php

namespace App\Cms\Form;

use App\Cms\Enum\CmsContentBlockType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsContentBlockSelectType extends AbstractType
{
    public function getParent(): string
    {
        return EnumType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => CmsContentBlockType::class,
        ]);
    }
}
