# WIP states for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/state.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)
[![Build Status](https://img.shields.io/travis/spatie/state/master.svg?style=flat-square)](https://travis-ci.org/spatie/:package_name)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/state.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/:package_name)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/state.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-state
```

## Usage

> **Note**: make sure you're familiar with the basics of the [state pattern](https://en.wikipedia.org/wiki/State_pattern) before using this package.

This package adds state support to your Laravel models. 

Start of by using the `Spatie\State\HasStates` trait in your model. Now you're able to define state fields. 
These are defined in the `$states` array on your model class. It requires you to map a field name unto a state class.
Here's an example of a `Payment` class with one state field, simply called `state`.

```php
use App\States\PaymentState;
use Spatie\State\HasStates;

/**
 * @property \App\States\PaymentState state
 */
class Payment extends Model
{
    use HasStates;

    protected $states = [
        'state' => PaymentState::class,
    ];
}
```

> **Note**: by adding a `@property` docblock, you'll get IDE autocompletion and static analysis support on your state fields.

You will always have to create an abstract class which will represent the possible states for that field. This class should extend the `Spatie\State\State` class. In our case, this class is called `PaymentState`. All concrete payment states should extend this base state class. Each concrete implementation can provide state-specific behaviour, as described by the [state pattern](https://en.wikipedia.org/wiki/State_pattern). 

This is what such a base class might look like:

```php
use Spatie\State\State;

abstract class PaymentState extends State
{
    abstract public function color(): string;
}
```

And this is a possible concrete implementation:

```php
class Paid extends PaymentState
{
    public static $name = 'paid';

    public function color(): string
    {
        return 'green';
    }
}
```

Now you can use the `state` field on your model directly as a `PaymentState` object, it will be properly saved and loaded to and from the database behind the scenes.

```php
$payment = Payment::create();

$payment->state = new Paid();

$payment->save();

// Color depending on the current state
echo $payment->state->color();
```

### Defaults

If you want a state to have a default value, you can do so in your model's constructor.

```php
// …

class Payment extends Model
{
    // …

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->state = $this->state ?? new Created();
    }
}
```

### State names

By default, the state's classname will be saved into the database. If you want the state to be saved using another value, you can provide a static `$name` property on your concrete state classes.

```php
class Paid extends PaymentState
{
    public static $name = 'paid';

    // …
}
```

#### Resolving states from the database

If you're using custom names, you'll need to make sure they can be resolved back from the database. There's two ways to do this:

- Manually provide the available states on an abstract state class
- Keep the abstract state class and its concrete implementations together in the same directory, which allows them to be resolved automatically.

Here's what the manual mapping looks like:

```php
abstract class PaymentState extends State
{
    public static $states =[
        Canceled::class,
        Created::class,
        Failed::class,
        Paid::class,
        Pending::class,
        PaidWithoutName::class,
    ];
    
    // …
}
```

Note that you only need to provide a manual mapping, if the concrete state classes don't live within the same directory as their abstract state class.

### State transitions

Next up, you can make transition classes which will take care of state transitions for you. Here's an example of a transition class which will mark the payment as failed with an error message.

```php
use Spatie\State\Transition;

class PendingToFailed extends Transition
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function __invoke(Payment $payment): Payment
    {
        // Only payments which state currently is pending can be handled by this transition
        $this->ensureInitialState($payment, Pending::class);

        $payment->state = new Failed($payment);
        $payment->failed_at = time();
        $payment->error_message = $this->message;

        $payment->save();

        return $payment;
    }
}
```

This transition is used like so:

```php
$pendingToFailed = new PendingToFailed('Error message from payment provider');

$payment = $pendingToFailed($payment);
```

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
