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

This line will only work when a valid transition was configured. If the initial state of `$payment` already was `Paid`, a `\Spatie\ModelStates\Exceptions\TransitionNotFound` will be thrown instead of changing the state. 

## Allow multiple transitions at once

A little shorthand `allowTransitions` can be used to allow multiple transitions at once:

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransitions([
                [Pending::class, Paid::class],
                [Pending::class, Failed::class, PendingToFailed::class],
            ]);
    }
}
```

## Allowing multiple from states

If you've got multiple states that can transition to the same state, you can define all of them in one `allowTransition` call:

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransition([Created::class, Pending::class], Failed::class, ToFailed::class);
    }
}
```

## Using transitions

Transitions can be used by calling the `transitionTo` method on the state field like so:

```php
$payment->state->transitionTo(Paid::class);
```

If you only have one state field on your model, you can use the `transitionTo` method directly on it:

```php
$payment->transitionTo(Paid::class);
```

If there are multiple fields, a `\Spatie\ModelStates\Exceptions\CouldNotResolveTransitionField` exception will be thrown. You can pass the state field name explicitly as a second parameter if you want to: 

```php
$payment->transitionTo(Paid::class, 'fieldName');
```
