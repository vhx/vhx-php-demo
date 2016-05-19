<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \VHX\API::setKey(env('VHX_API_KEY'));
        
        view()->share('config', [
          'typekit_id' => env('TYPEKIT_ID')
        ]);

        $random = rand(100, 1000000);

        view()->share('demo', [
          'email' => 'vhx.demo+' . $random . '@gmail.com',
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
