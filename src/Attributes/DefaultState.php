<?php

namespace Spatie\ModelStates\Attributes;

use Attribute;
use JetBrains\PhpStorm\Immutable;

#[Attribute(Attribute::TARGET_CLASS)]
class DefaultState
{
    public function __construct(
        #[Immutable] public string $defaultStateClass,
    ) {
    }
}
