<?php

namespace App\Domain\FileProcessing\Enum;

enum TextFileAcceptedEnum: string
{
    case PDF = 'pdf';
    case DOC = 'doc';
    case DOCX = 'docx';
    case ODT = 'odt';
    case RTF = 'rtf';
    case PAGES = 'pages';

    public function createReader(): FileReaderInterface
    {
        return match ($this) {
            self::PDF => new PdfReader(),
            self::DOC => new DocReader(),
//            self::DOCX => new DocxReader(),
//            self::ODT => new OdtReader(),
//            self::RTF => new RtfReader(),
//            self::PAGES => new PagesReader(),
        };
    }
}
