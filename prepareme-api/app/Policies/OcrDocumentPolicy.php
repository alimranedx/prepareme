<?php

namespace App\Policies;

use App\Models\OcrDocument;
use App\Models\User;

class OcrDocumentPolicy
{
    public function view(User $user, OcrDocument $document): bool
    {
        return $user->id === $document->user_id;
    }

    public function update(User $user, OcrDocument $document): bool
    {
        return $user->id === $document->user_id;
    }

    public function delete(User $user, OcrDocument $document): bool
    {
        return $user->id === $document->user_id;
    }
}
