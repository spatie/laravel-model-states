---
title: Configuring states
weight: 1
---

This package provides a `HasStates` trait which you can use in whatever model you want state support in. Within your codebase, each state is represented by a class, and will be serialised to the database by this package behind the scenes.

This way you don't have to worry about whether a state is in its textual form or not: you're always working with state objects.

```php
use Spatie\ModelStates\HasStates;

class Payment extends Model
{
    use HasStates;

    // …
}
```

A model can have as many state fields as you want, and you're allowed to call them whatever you want. Just make sure every state has a corresponding database string field.

```php
Schema::table('payments', function (Blueprint $table) {
    $table->string('state');
});
```

Each state field should be represented by a class, which itself extends an abstract class you also must provide. An example would be `PaymentState`, having three concrete implementations: `Pending`, `Paid` and `Failed`.

```php
use Spatie\ModelStates\State;

abstract class PaymentState extends State
{
    abstract public function color(): string;
}
```

```php
class Paid extends PaymentState
{
    public function color(): string
    {
        return 'green';
    }
}
```

There might be some cases where this abstract class will simply be empty, still it's important to provide it, as type validation will be done using it.

To link the `Payment::$state` field and the `PaymentState` class together, you should list it as a cast:

```php
class Payment extends Model
{
    // …

    protected $casts = [
        'state' => PaymentState::class,
    ];
}
```

States can be configured to have a default value and to register transitions. This is done by implementing the `config` method in your abstract state classes:

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

## Manually registering states
If you want to place your concrete state implementations in a different directory, you may do so and register them manually:

```php
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

use Your\Concrete\State\Class\Cancelled; // this may be wherever you want
use Your\Concrete\State\Class\ExampleOne;
use Your\Concrete\State\Class\ExampleTwo;

abstract class PaymentState extends State
{
    abstract public function color(): string;
    
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class)
            ->registerState(Cancelled::class)
            ->registerState([ExampleOne::class, ExampleTwo::class])
        ;
    }
}
```

In case you want to register all your states manually, you can disable the automatic registration of states by calling `skipAutoRegisterStates()`!

## Configuring states using attributes

If you're using PHP 8 or higher, you can also configure your state using attributes:

```php
use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\RegisterState;use Spatie\ModelStates\State;use const Grpc\STATUS_CANCELLED;

#[
    AllowTransition(Pending::class, Paid::class),
    AllowTransition(Pending::class, Failed::class),
    DefaultState(Pending::class),
    RegisterState(Cancelled::class),
    RegisterState([ExampleOne::class, ExampleTwo::class]),
]
abstract class PaymentState extends State
{
    abstract public function color(): string;
}
```

Next up, we'll take a moment to discuss how state classes are serialized to the database.
