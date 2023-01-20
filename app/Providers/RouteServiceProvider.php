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
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';
    public const ADMINHOME = '/';
    public const MEMBERHOME = '/member/listings';

    /**
     * If specified, this namespace is automatically applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $apiNamespace = 'App\Http\Controllers\api';
    protected $adminNamespace = 'App\Http\Controllers\panel';
    protected $memberNamespace = 'App\Http\Controllers\user';
    protected $webNamespace = 'App\Http\Controllers\site';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->domain('admin.motovago.com')
                ->namespace($this->adminNamespace)
                ->group(base_path('routes/admin.php'));

            Route::prefix('member')
                ->middleware('web')
                ->namespace($this->memberNamespace)
                ->group(base_path('routes/member.php'));

            Route::middleware('web')
                ->namespace($this->webNamespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['api', 'cors'])
                ->domain('api.motovago.com')
                ->namespace($this->apiNamespace)
                ->group(base_path('routes/api.php'));
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
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
