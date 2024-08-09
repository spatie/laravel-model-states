<?php

namespace Spatie\ModelStates;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\ModelStates\Commands\MakeAbstractStateCommand;
use Spatie\ModelStates\Commands\MakeStateCommand;

class ModelStatesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-states')
            ->hasCommands([
                MakeAbstractStateCommand::class,
                MakeStateCommand::class,
            ])
            ->hasConfigFile();
    }
}
