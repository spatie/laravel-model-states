<?php

namespace Spatie\ModelStates\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeStateCommand extends GeneratorCommand
{
    public $name = 'make:state';

    public $description = 'Create a state class';

    public $type = 'State';

    protected function getStub(): string
    {
        return __DIR__ . '/../../stubs/state.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Models\States';
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
            ['parent', InputArgument::REQUIRED, 'The name of the parent abstract state'],
        ];
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $parent = $this->getParent();

        return $this
            ->replaceNamespace($stub, $name)
            ->replaceParent($stub, $parent)
            ->replaceClass($stub, $name);
    }

    protected function replaceParent(&$stub, $name): self
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace(['DummyParent', '{{ parent }}', '{{parent}}'], $class, $stub);

        return $this;
    }

    protected function getParent(): string
    {
        return trim($this->argument('parent'));
    }
}
