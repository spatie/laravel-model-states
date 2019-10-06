---
title: Configuring states
weight: 1
---

This package provides a `HasStates` trait which you can use in whatever model you want state support in. Within your codebase, each state is represented by a class, and will be serialised to the database by this package behind the scenes.

This way you don't have to worry about whether a state is in its textual form or not: you're always working with state objects.

For this reason, it's recommended to add a `@property` docblock on your model class, to make sure you always have IDE autocompletion.

```php
use Spatie\ModelStates\HasStates;

/**
 * @property \App\States\PaymentState $state
 */
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

To link the `Payment::$state` field and the `PaymentState` class together, you must implement the `registerStates` method.

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this
            ->addState('state', PaymentState::class);
    }
}
```

If you want to, you can add a default state like so:

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this
            ->addState('state', PaymentState::class)
            ->default(Pending::class);
    }
}
```

Next up, we'll take a moment to discuss how state classes are serialized to the database.
