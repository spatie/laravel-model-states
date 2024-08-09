<?php

namespace Spatie\ModelStates\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeAbstractStateCommand extends GeneratorCommand
{
    public $name = 'make:abstract-state';

    public $description = 'Create an abstract state class';

    public $type = 'Abstract State';

    protected function getStub(): string
    {
        return __DIR__ . '/../../stubs/abstract-state.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Models\States';
    }
}
