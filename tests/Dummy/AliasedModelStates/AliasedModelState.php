<?php

namespace Spatie\ModelStates\Tests\Dummy\AliasedModelStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AliasedModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class);
    }
}
