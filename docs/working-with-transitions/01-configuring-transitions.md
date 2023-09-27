---
title: Configuring transitions
weight: 1
---

Transitions can be used to transition the state of a model from one to another, in a structured and safe way.

You can specify which states are allowed to transition from one to another, and if you want to handle side effects or have more complex transitions, you can also provide custom transition classes.

Transitions are configured in the `config` method on your state classes.

```php
abstract class PaymentState extends State
{
    // …

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class, PendingToFailed::class);
    }
}
```

In this example we're using both a simple transition, and a custom one. You can also allow all transitions if your states are already properly registered:

```php
abstract class PaymentState extends State
{
    // …

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowAllTransitions();
    }
}
```

Transitions can then be used like so:

```php
$payment->state->transitionTo(Paid::class);
```

This line will only work when a valid transition was configured. If the initial state of `$payment` already was `Paid`, a `\Spatie\ModelStates\Exceptions\TransitionNotFound` will be thrown instead of changing the state. 

## Allow multiple transitions at once

A little shorthand `allowTransitions` can be used to allow multiple transitions at once:

```php
abstract class PaymentState extends State
{
    // …

    public static function config(): StateConfig
    {
        return parent::config()
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
abstract class PaymentState extends State
{
    // …

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition([Created::class, Pending::class], Failed::class, ToFailed::class);
    }
}
```

## Using transitions

Transitions can be used by calling the `transitionTo` method on the state field like so:

```php
$payment->state->transitionTo(Paid::class);
```
