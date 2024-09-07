<?php

namespace vallerydelexy\LaravelWizard;

use Illuminate\Support\ServiceProvider;
use vallerydelexy\LaravelWizard\Console\StepMakeCommand;
use vallerydelexy\LaravelWizard\Console\TableCommand;
use vallerydelexy\LaravelWizard\Console\WizardControllerMakeCommand;
use vallerydelexy\LaravelWizard\Console\WizardMakeCommand;

class WizardServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('wizard', function ($app) {
            return new WizardFactory($app);
        });

        $this->app->alias('wizard', WizardFactory::class);

        $this->mergeConfigFrom(__DIR__.'/../config/wizard.php', 'wizard');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            WizardMakeCommand::class,
            WizardControllerMakeCommand::class,
            StepMakeCommand::class,
            TableCommand::class,
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views-bs5', 'wizard');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wizard');

        $this->publishes([
            __DIR__.'/../config/wizard.php' => config_path('wizard.php'),
        ], 'wizard-config');

        $this->publishes([
            __DIR__.'/../resources/views-bs4' => resource_path('views/vendor/wizard'),
        ], 'wizard-views-bs4');

        $this->publishes([
            __DIR__.'/../resources/views-bs5' => resource_path('views/vendor/wizard'),
        ], 'wizard-views-bs5');

        $this->publishes([
            __DIR__.'/../resources/views-tailwind' => resource_path('views/vendor/wizard'),
        ], 'wizard-views-tailwind');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/wizard'),
        ], 'wizard-languages');
    }
}
