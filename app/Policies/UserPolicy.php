<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * A user can only update their own profile.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        return $authUser->id === $targetUser->id;
    }

    /**
     * Only employers can post jobs (example gate usage).
     */
    public function postJob(User $user): bool
    {
        return $user->isEmployer();
    }

    /**
     * Only seekers can apply to jobs.
     */
    public function applyJob(User $user): bool
    {
        return $user->isSeeker();
    }
}
