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

## Can transition to

If you want to know whether a state can be transitioned to another one, you can use the `canTransitionTo` method:

```php
$payment->state->canTransitionTo(Paid::class);
```
