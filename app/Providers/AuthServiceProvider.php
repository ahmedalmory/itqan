<?php

namespace App\Providers;

use App\Models\DailyReport;
use App\Models\StudentSubscription;
use App\Policies\DailyReportPolicy;
use App\Policies\StudentSubscriptionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        DailyReport::class => DailyReportPolicy::class,
        StudentSubscription::class => StudentSubscriptionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a role-based gate
        Gate::define('role', function ($user, ...$roles) {
            return in_array($user->role, $roles);
        });
    }
} 