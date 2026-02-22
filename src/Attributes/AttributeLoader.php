<?php

namespace Spatie\ModelStates\Attributes;

use Spatie\Attributes\Attributes;
use Spatie\ModelStates\StateConfig;

class AttributeLoader
{
    public function __construct(
        private string $stateClass,
    ) {
    }

    public function load(StateConfig $stateConfig): StateConfig
    {
        $transitionAttributes = Attributes::getAll($this->stateClass, AllowTransition::class);

        foreach ($transitionAttributes as $transitionAttribute) {
            $stateConfig->allowTransition(
                $transitionAttribute->from,
                $transitionAttribute->to,
                $transitionAttribute->transition,
            );
        }

        $defaultStateAttribute = Attributes::get($this->stateClass, DefaultState::class);

        if ($defaultStateAttribute) {
            $stateConfig->default($defaultStateAttribute->defaultStateClass);
        }

        if (Attributes::has($this->stateClass, IgnoreSameState::class)) {
            $stateConfig->ignoreSameState();
        }

        $registerStateAttributes = Attributes::getAll($this->stateClass, RegisterState::class);

        foreach ($registerStateAttributes as $registerStateAttribute) {
            $stateConfig->registerState($registerStateAttribute->stateClass);
        }

        return $stateConfig;
    }
}
