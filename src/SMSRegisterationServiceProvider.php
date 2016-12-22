<?php

namespace Sliverwing\Registration;

use Sliverwing\Registration\Http\Middleware\SMSRegistrationMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Validator;


class SMSRegisterationServiceProvider extends ServiceProvider
{
    protected $session;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router, Session $session)
    {
        $this->session = $session;
        $router->middleware('smsregistration', SMSRegistrationMiddleware::class);
        Validator::extend('smsverificationcode', function ($attribute, $value, $parameters, $validator) {
            return $this->session->get('VerificationCode') == $value;
        });
        Validator::extend('smsverificationphone', function($attribute, $value, $parameters, $validator) {
            return $this->session->get('VerificationPhoneNumber') == $value;
        });
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
                $basePath . '/views/' => resource_path('views'),
            ]);
        }
    }
}
