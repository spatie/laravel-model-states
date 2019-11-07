---
title: Introduction
weight: 1
---

This package adds state support to models. It combines concepts from the [state pattern](https://en.wikipedia.org/wiki/State_pattern) and [state machines](https://www.youtube.com/watch?v=N12L5D78MAA).

It is recommended that you're familiar with both patterns if you're going to use this package.

To give you a feel about how this package can be used, let's look at a quick example.

Imagine a model `Payment`, which has three possible states: `Pending`, `Paid` and `Failed`. This package allows you to represent each state as a separate class, handles serialization of states to the database behind the scenes, and allows for easy and controller state transitions.

For the sake of our example, let's say that, depending on the state, a the color of a payment should differ.

Here's what the `Payment` model would look like:

```php
use Spatie\ModelStates\HasStates;

/**
 * @property \App\States\PaymentState $state
 */
class Payment extends Model
{
    use HasStates;

    protected function registerStates(): void
    {
        $this
            ->addState('state', PaymentState::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class, PendingToFailed::class);
    }
}
```

This is what the abstract `PaymentState` class would look like:

```php
use Spatie\ModelStates\State;

abstract class PaymentState extends State
{
    abstract public function color(): string;
}
```

Here's a concrete implementation of one state, the `Paid` state:

```php
class Paid extends PaymentState
{
    public function color(): string
    {
        return 'green';
    }
}
```

And here's how it it used:

```php
$payment = Payment::find(1);

$payment->state->transitionTo(Paid::class);

echo $payment->state->color();
```

There's a lot more to tell about how this package can be used. So let's dive in.

## We have badges!

<section class="article_badges">
    <a href="https://github.com/spatie/laravel-model-states/releases"><img src="https://img.shields.io/github/release/spatie/laravel-model-states.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://github.com/spatie/laravel-model-states/blob/master/LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></a>
    <a href="https://travis-ci.org/spatie/laravel-model-states"><img src="https://img.shields.io/travis/spatie/laravel-model-states/master.svg?style=flat-square" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/spatie/laravel-model-states"><img src="https://img.shields.io/scrutinizer/g/spatie/laravel-model-states.svg?style=flat-square" alt="Quality Score"></a>
    <a href="https://packagist.org/packages/spatie/laravel-model-states"><img src="https://img.shields.io/packagist/dt/spatie/laravel-model-states.svg?style=flat-square" alt="Total Downloads"></a>
</section>
