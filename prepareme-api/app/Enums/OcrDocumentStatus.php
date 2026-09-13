<?php

namespace App\Enums;

enum OcrDocumentStatus: string
{
    case UPLOADED = 'uploaded';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
