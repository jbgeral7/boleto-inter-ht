<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Interfaces\CustomerInterface',
            'App\Repositories\CustomerRepository',
          );
        
          $this->app->bind(
            'App\Interfaces\ServiceInterface',
            'App\Repositories\ServiceRepository',
          );

          $this->app->bind(
            'App\Interfaces\BoletoInterface',
            'App\Repositories\BoletoRepository'
          );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      \Carbon\Carbon::setLocale($this->app->getLocale());
    }
}
