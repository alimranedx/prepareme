<?php

namespace App\Policies;

use App\Models\PersonalQuestion;
use App\Models\User;

class PersonalQuestionPolicy
{
    public function view(User $user, PersonalQuestion $question): bool
    {
        return $user->id === $question->user_id;
    }

    public function update(User $user, PersonalQuestion $question): bool
    {
        return $user->id === $question->user_id;
    }

    public function delete(User $user, PersonalQuestion $question): bool
    {
        return $user->id === $question->user_id;
    }
}
