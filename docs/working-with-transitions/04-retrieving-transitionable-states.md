---
title: Retrieving transitionable states
weight: 4
---

An array of transitionable states can be retrieved using the `transitionableStates()` on the state field.

```php

abstract class PaymentState extends State
{
    // …

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Paid::class, Refunded::class);
    }
}
```

```php
$transitionableStates = $payment->state->transitionableStates();
```

This will return an array with all transitionable states for the current state, for example `Pending`:

```php
[
    0 => "paid"
]
```

## Retrieving state instances

If you need the actual state instances instead of just their string representations, you can use the `transitionableStateInstances()` method:

```php
$stateInstances = $payment->state->transitionableStateInstances();
```

This will return an array of instantiated state objects:

```php
[
    0 => Paid {
        // State instance with model reference
    }
]
```

### Simple example in Blade

This method is particularly useful when you need to access state methods directly. For example, to display available transitions with their properties:

```php
@foreach($payment->state->transitionableStateInstances() as $stateInstance)
    <div>
        <span style="color: {{ $stateInstance->color() }}">{{ $stateInstance->label() }}</span>
        <i class="{{ $stateInstance->icon() }}"></i>
    </div>
@endforeach
```

With this approach, you can directly call any method defined on your state classes, allowing you to encapsulate UI and business logic within your states:

```php
abstract class PaymentState extends State
{
    abstract public function color(): string;
    abstract public function label(): string;
    abstract public function icon(): string;

    // ...other state methods
}

class Paid extends PaymentState
{
    public function color(): string
    {
        return '#4CAF50'; // green
    }

    public function label(): string
    {
        return 'Mark as Paid';
    }

    public function icon(): string
    {
        return 'check-circle';
    }
}
```

## Retrieving state counts
This method tells you how many available transitions exist for the current state.

```php
$stateCount = $payment->state->transitionableStatesCount(); // 4
```

## Checking for available transitions
This method tells you whether there are any available transitions for the current state.
```php
$hasTransitions = $payment->state->hasTransitionableStates(); // true or false
```

## Can transition to

If you want to know whether a state can be transitioned to another one, you can use the `canTransitionTo` method:

```php
$payment->state->canTransitionTo(Paid::class);
```
