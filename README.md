# Adding state behaviour to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-model-states.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-states)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-states/run-tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-model-states.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-model-states)
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

And here's how it is used:

```php
$payment = Payment::find(1);

$payment->state->transitionTo(Paid::class);

echo $payment->state->color();
```

## Support us

Learn how to create a package like this one, by watching our premium video course:

[![Laravel Package training](https://spatie.be/github/package-training.jpg)](https://laravelpackage.training)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-model-states
```

## Usage

Please refer to the [docs](https://docs.spatie.be/laravel-model-states/v1/01-introduction/) to learn how to use this package.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
