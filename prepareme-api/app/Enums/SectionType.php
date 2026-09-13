<?php

namespace App\Enums;

enum SectionType: string
{
    case EXPLANATION = 'explanation';
    case EXAMPLE = 'example';
    case FORMULA = 'formula';
    case SUMMARY = 'summary';
    case PRACTICE = 'practice';
}
