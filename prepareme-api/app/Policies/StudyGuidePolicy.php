<?php

namespace App\Policies;

use App\Models\StudyGuide;
use App\Models\User;

class StudyGuidePolicy
{
    public function view(?User $user, StudyGuide $guide): bool
    {
        if ($guide->status->isPublished()) {
            return true;
        }

        return $user !== null && $user->isAdmin();
    }
}
