<?php

namespace Spatie\ModelStates\Attributes;

use ReflectionClass;
use Spatie\ModelStates\StateConfig;

class AttributeLoader
{
    private ReflectionClass $reflectionClass;

    public function __construct(string $stateClass)
    {
        $this->reflectionClass = new ReflectionClass($stateClass);
    }

    public function load(StateConfig $stateConfig): StateConfig
    {
        $transitionAttributes = $this->reflectionClass->getAttributes(AllowTransition::class);

        foreach ($transitionAttributes as $attribute) {
            /** @var \Spatie\ModelStates\Attributes\AllowTransition $transitionAttribute */
            $transitionAttribute = $attribute->newInstance();

            $stateConfig->allowTransition(
                $transitionAttribute->from,
                $transitionAttribute->to,
                $transitionAttribute->transition,
            );
        }

        if ($attribute = $this->reflectionClass->getAttributes(DefaultState::class)[0] ?? null) {
            /** @var \Spatie\ModelStates\Attributes\DefaultState $transitionAttribute */
            $defaultStateAttribute = $attribute->newInstance();

            $stateConfig->default($defaultStateAttribute->defaultStateClass);
        }

        if ($this->reflectionClass->getAttributes(IgnoreSameState::class)[0] ?? null) {
            /** @var \Spatie\ModelStates\Attributes\IgnoreSameState $transitionAttribute */

            $stateConfig->ignoreSameState();
        }
	
	    $registerStateAttributes = $this->reflectionClass->getAttributes(RegisterState::class);
		
		foreach($registerStateAttributes as $attribute) {
			/** @var \Spatie\ModelStates\Attributes\RegisterState $registerStateAttribute */
			$registerStateAttribute = $attribute->newInstance();
			
			$stateConfig->registerState($registerStateAttribute->stateClass);
		}
	
	
	    return $stateConfig;
    }
}
