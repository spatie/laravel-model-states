<?php

namespace Spatie\ModelStates\Attributes;

use Attribute;
use JetBrains\PhpStorm\Immutable;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class RegisterState
{
    public function __construct(
        #[Immutable] public string|array $stateClass,
    ) {
    }
}
