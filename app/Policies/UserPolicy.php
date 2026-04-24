<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    public function update(User $authUser, User $targetUser): bool
    {
        return $authUser->id === $targetUser->id;
    }

    public function postJob(User $user): bool
    {
        return $user->isEmployer();
    }

    public function applyJob(User $user): bool
    {
        return $user->isSeeker();
    }
}
