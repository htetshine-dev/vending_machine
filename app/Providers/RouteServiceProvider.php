<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const ADMINHOME = '/admin/dashboard';

    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            ///Web
            Route::middleware(['web', 'role:client'])
                // ->prefix('client')
                // ->name('client.')
                ->group(base_path('routes/client/web.php'));

            Route::middleware(['web', 'role:admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin/web.php'));

            //Api
            Route::prefix('api/')
                ->middleware('auth:api')
                ->namespace('App\Http\Controllers\Api\Client')
                // ->prefix('client')
                ->name('api.')
                ->group(base_path('routes/client/api.php'));

            // Route::middleware(['api', 'auth:sanctum'])
            // Route::prefix('api/admin')
            //     ->namespace('App\Http\Controllers\Admin')
            //     ->name('api.admin.')
            //     ->group(base_path('routes/admin/api.php'));

            
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
