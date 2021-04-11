---
title: Serializing states
weight: 2
---

Say you create a `Payment` like so:

```php
$payment = Payment::create();
```

If you've setup the default state to be `Pending`, this `state` field in the database will contain the class name of this state, eg. `\App\States\Payment\Pending`.

Chances are you don't want to work directly with a state's class name all the time. This is why you may add a static `$name` property on each state class, which will be used to serialize the state instead.

```php
class Paid extends PaymentState
{
    public static $name = 'paid';

    // …
}
```

You can still use `::class` in your codebase though, the package will take care of name mappings for you.

For example:

```php
$payment = Payment::create([
    'paid' => Paid::class,
]);
```

The state value will still be saved as `paid` in the database.

## Resolving states from the database

There's one caveat if you're using custom names: you'll need to make sure they can be resolved back from the database. In order to do so, the package requires you to keep the abstract state class and its concrete implementations together in the same directory, which allows them to be resolved automatically.

```
States/
  ├── Failed.php
  ├── Paid.php
  ├── PaymentState.php // This abstract class will automatically detect all relevant states within this directory.
  └── Pending.php
```
