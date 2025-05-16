<?php

namespace App\Domain\Template\Enum;

enum TemplateEnum: string
{
    case FAKTURA = 'faktura';
    case FAKTURA_PROFORMA = 'faktura pro-forma';
    case UMOWA = 'umowa';
}
