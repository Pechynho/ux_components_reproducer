<?php

namespace App\Page;

use App\Cms\CmsManager;
use App\Page\Model\DynamicPage;

readonly class DynamicPageManager
{
    public function __construct(
        private CmsManager $cmsManager,
    ) {}

    public function createOne(): DynamicPage
    {
        return new DynamicPage(
            '',
            '',
            false,
            null,
            null,
            $this->cmsManager->createContent(),
        );
    }
}
