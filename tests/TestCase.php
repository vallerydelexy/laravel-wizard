<?php

namespace vallerydelexy\LaravelWizard\Test;

use Illuminate\Http\UploadedFile;
use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use vallerydelexy\LaravelWizard\Facades\Wizard as WizardFacade;
use vallerydelexy\LaravelWizard\Test\Stubs\User;
use vallerydelexy\LaravelWizard\WizardServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // App
        $app['config']->set('app.debug', true);
        $app['config']->set('app.key', 'base64:tqASP1YzC4hhdT1nMEc+DFGMRq6WQmfMzYFW522Ce8g=');

        // Database
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        // Wizard
        $app['config']->set('wizard', require __DIR__.'/../config/wizard.php');

        // Views
        $app['view']->addLocation(__DIR__.'/Stubs/views');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            WizardServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Wizard' => WizardFacade::class,
        ];
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Stubs/database/migrations');
    }

    /**
     * Mock an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return object
     */
    protected function mock($abstract, $mock = null)
    {
        return $this->app->instance($abstract, Mockery::mock(...array_filter(func_get_args())));
    }

    /**
     * Create and authenticate user.
     *
     * @return void
     */
    protected function authenticate()
    {
        $user = User::create([
            'name' => 'Lucas Yang',
            'email' => 'yangchenshin77@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $this->app['request']->setUserResolver(function () use ($user) {
            return $user;
        });
    }

    /**
     * Assert that is temporary file.
     *
     * @param  mixed  $file
     * @param  string  $startsWith
     * @return void
     */
    protected function assertIsTemporaryFile($file, string $startsWith = 'php')
    {
        $this->assertInstanceOf(UploadedFile::class, $file);
        $this->assertStringStartsWith($startsWith, $file->getFilename());
    }
}
