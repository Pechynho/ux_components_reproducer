<?php

namespace App\UX\Components\Popover\Confirm\Delete;

use App\UX\Components\Popover\Confirm\Generic\Generic;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Popover:Confirm:Delete', '@components/Popover/Confirm/Delete/Delete.html.twig')]
class Delete extends Generic
{
    protected function getDefaultText(): string
    {
        return 'Do you really want to delete this item?';
    }

    protected function getDefaultConfirmButton(): string
    {
        return 'Delete';
    }
}
