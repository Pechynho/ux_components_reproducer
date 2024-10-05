<?php

namespace App\Cms;

use App\Cms\Enum\CmsContentBlockType;
use App\Cms\Model\CmsContent;
use App\Cms\Model\CmsContentBlock;

class CmsManager
{
    public function createBlock(?CmsContent $content = null): CmsContentBlock
    {
        $order = $content === null ? 0 : $content->getBlocks()->count();
        return new CmsContentBlock(CmsContentBlockType::TextEditor, $order);
    }

    public function createContent(): CmsContent
    {
        return new CmsContent();
    }
}
