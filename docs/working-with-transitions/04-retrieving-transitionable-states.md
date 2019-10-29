---
title: Retrieving transitionable states
weight: 4
---

An array of transitionable states can be retrieved with the `transitionableStates()` method on your model.


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

## Transitionable states from state

It's also possible to use `transitionableStates()` method directly on a state:

```php
$payment->state->transitionableStates();

## Can transition to

If you want to know whether a state can be transitioned to another one, you can use the `canTransitionTo` method:

```php
$payment->state->canTransitionTo(Paid::class);
```
