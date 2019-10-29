---
title: Retrieving transitionable states
weight: 4
---

An array of transitionable states can use the retrieved with the `transitionableStates()` method on the state.

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Paid::class, Refunded::class)
    }
}
```

```php
$transitionableStates = $payment->state->transitionableStates();
```

This will return an array with all transitionable states for `Pending::class`

```php
[
    0 => "paid"
]
```

## Can transition to

If you want to know whether a state can be transitioned to another one, you can use the `canTransitionTo` method:

```php
$payment->state->canTransitionTo(Paid::class);
```
