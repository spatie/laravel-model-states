# Adding state behaviour to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-model-states.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-states)
![Test](https://github.com/spatie/laravel-model-states/workflows/Test/badge.svg)
![Check & fix styling](https://github.com/spatie/laravel-model-states/workflows/Check%20&%20fix%20styling/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-model-states.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-states)

This package adds state support to models. It combines concepts from the [state pattern](https://en.wikipedia.org/wiki/State_pattern) and [state machines](https://www.youtube.com/watch?v=N12L5D78MAA).

It is recommended that you're familiar with both patterns if you're going to use this package.

To give you a feel about how this package can be used, let's look at a quick example.

Imagine a model `Payment`, which has three possible states: `Pending`, `Paid` and `Failed`. This package allows you to represent each state as a separate class, handles serialization of states to the database behind the scenes, and allows for easy state transitions.

For the sake of our example, let's say that, depending on the state, the color of a payment should differ.

Here's what the `Payment` model would look like:

```php
use Spatie\ModelStates\HasStates;

class Payment extends Model
{
    use HasStates;

    protected $casts = [
        'state' => PaymentState::class,
    ];
}
```

This is what the abstract `PaymentState` class would look like:

```php
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PaymentState extends State
{
    abstract public function color(): string;
    
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class)
        ;
    }
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

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-model-states.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-model-states)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-model-states
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Spatie\ModelStates\ModelStatesServiceProvider" --tag="model-states-config"
```

This is the content of the published config file:

```php
return [

    /*
     * The fully qualified class name of the default transition.
     */
    'default_transition' => Spatie\ModelStates\DefaultTransition::class,

];
```

## Usage

Please refer to the [docs](https://spatie.be/docs/laravel-model-states/v2/01-introduction/) to learn how to use this package.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
