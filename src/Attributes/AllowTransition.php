<?php

namespace Spatie\ModelStates\Attributes;

use Attribute;
use JetBrains\PhpStorm\Immutable;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class AllowTransition
{
    public function __construct(
        #[Immutable] public array | string $from,
        #[Immutable] public string $to,
        #[Immutable] public ?string $transition = null
    ) {
    }
}
