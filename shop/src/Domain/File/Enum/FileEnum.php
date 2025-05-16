<?php

namespace App\Domain\File\Enum;

enum FileEnum: string
{
    case DRAFT       = 'draft';
    case PUBLISHED   = 'published';
    case ARCHIVED    = 'archived';
}
