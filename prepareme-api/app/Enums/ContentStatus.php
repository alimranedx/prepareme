<?php

namespace App\Enums;

enum ContentStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function isPublished(): bool
    {
        return $this === self::PUBLISHED;
    }
}
