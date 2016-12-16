<?php

namespace Sliverwing\Registration;

use Sliverwing\Registration\Http\Middleware\SMSRegistrationMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;


class SMSRegisterationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middleware('smsregistration', SMSRegistrationMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $basePath = dirname(__DIR__);
            $this->publishes([
                $basePath . '/config/smsregistration.php' => config_path('smsregistration.php'),
                $basePath . '/migrations/' => database_path('migrations'),
            ]);
        }
    }
}
