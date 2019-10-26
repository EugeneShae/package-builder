<?php

namespace Shae\PackageBuilder;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Shae\PackageBuilder\Commands\PackageCreateCommand;
use Shae\PackageBuilder\Facades\PackageBuilderFacade;

class PackageBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('PackageBuilder', PackageBuilderFacade::class);

        $this->app->singleton('engine', function ()
        {
            return new PackageBuilder();
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                PackageCreateCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/packageBuilder.php', 'packageBuilder');
    }
}