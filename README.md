# Adding state behaviour to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-model-states.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-states)
[![Build Status](https://img.shields.io/travis/spatie/laravel-model-states/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-model-states)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-model-states.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-model-states)
[![StyleCI](https://github.styleci.io/repos/206020634/shield?branch=master)](https://github.styleci.io/repos/206020634)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-model-states.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-states)

This package adds state support to models. It combines concepts from the [state pattern](https://en.wikipedia.org/wiki/State_pattern) and [state machines](https://www.youtube.com/watch?v=N12L5D78MAA).

It is recommended that you're familiar with both patterns if you're going to use this package.

To give you a feel about how this package can be used, let's look at a quick example.

Imagine a model `Payment`, which has three possible states: `Pending`, `Paid` and `Failed`. This package allows you to represent each state as a separate class, handles serialization of states to the database behind the scenes, and allows for easy state transitions.

For the sake of our example, let's say that, depending on the state, the color of a payment should differ.

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


## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-model-states
```

## Usage

Please refer to the [docs](https://docs.spatie.be/laravel-model-states/v1/01-introduction/) to learn how to use this package.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
