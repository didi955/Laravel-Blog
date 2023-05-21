<?php

namespace App\Providers;

use App\Utilities\Role;
use App\Utilities\sanitize\EscapeScriptTag;
use App\Utilities\sanitize\EscapeStyleTag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->role->isHigherEqualThan(Role::ADMIN);
        });
        Blade::if('admin', function () {
            return auth()->check() && Gate::allows('admin');
        });

        Carbon::setLocale(app()->getLocale());

        \Sanitizer::extend('escape_script_tag', EscapeScriptTag::class);
        \Sanitizer::extend('escape_style_tag', EscapeStyleTag::class);
    }
}
