<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Conversation;
use App\Policies\ConversationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Model → Policy mapping.
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Conversation::class => ConversationPolicy::class,
    ];

    /**
     * Register Gates and Policies.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ── Custom Gates ──────────────────────────────────────────────────────
        Gate::define('employer-only', fn (User $user) => $user->isEmployer());
        Gate::define('seeker-only',   fn (User $user) => $user->isSeeker());
    }
}
