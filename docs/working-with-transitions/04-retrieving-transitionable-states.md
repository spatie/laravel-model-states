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
$transitionableStates = $payment->transitionableStates(Pending::class);
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
```

## Multiple state fields on model

If there are multiple fields, a `\Spatie\ModelStates\Exceptions\InvalidConfig` exception will be thrown. You can pass the state field name explicitly as a parameter:

```php
// From a model
$payment->transitionableStates(Pending::class, 'fieldName')

// From a state
$payment->state->transitionableStates('fieldName');
```
