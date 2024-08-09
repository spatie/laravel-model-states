<?php

namespace Spatie\ModelStates;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\ModelStates\Commands\MakeAbstractStateCommand;

class ModelStatesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-states')
            ->hasCommands([
                MakeAbstractStateCommand::class,
            ])
            ->hasConfigFile();
    }
}
