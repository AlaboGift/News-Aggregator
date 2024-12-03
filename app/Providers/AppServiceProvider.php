<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
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
        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
    }

    /**
     * Configure the applications command
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    /**
     * Configure the applications models
     */
    private function configureModels()
    {
        // Model::shouldBeStrict();

        Model::unguard();
    }


    /**
     * Configure url
     * ensure that the url is https
     */
    private function configureUrl()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

}
