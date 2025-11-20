<?php

namespace Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class RegisterStatesFromCustomDirectories extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->registerStatesFromDirectory(__DIR__ . '/Directory1' , __DIR__ . '/Directory2')
            ->default(DefaultState::class)
            ->allowAllTransitions();
    }
}
