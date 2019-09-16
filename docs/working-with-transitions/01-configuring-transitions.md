---
title: Configuring transitions
weight: 1
---

Transitions can be used to transition the state of a model from one to another, in a structured and safe way.

You can specify which states are allowed to transition from one to another, and if you want to handle side effects or have more complex transitions, you can also provide custom transition classes.

Transitions are configured in the `registerStates` method on your model.

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class, PendingToFailed::class);
    }
}
```

In this example we're using both a simple transition, and a custom one. Transitions can be used like so:

```php
$payment->state->transitionTo(Paid::class);
```

This line will only work when a valid transition was configured. If the initial state of `$payment` already was `Paid`, a `\Spatie\State\Exceptions\TransitionError` will be thrown instead of changing the state. 
