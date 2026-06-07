<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Horizon::auth(function ($request) {
            if (app()->environment('local')) {
                return true;
            }

            return $request->user()?->hasRole('admin') ?? false;
        });
    }

    protected function gate(): void
    {
        Gate::define('viewHorizon', function (?User $user) {
            return $user?->hasRole('admin') ?? false;
        });
    }
}
