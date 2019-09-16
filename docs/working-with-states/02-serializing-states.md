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
    'state' => Paid::class,
]);
```

The state value will still be saved as `paid` in the database.

## Resolving states from the database

There's one caveat if you're using custom names: you'll need to make sure they can be resolved back from the database. There's two ways to do this:

- Manually provide the available states on an abstract state class
- Keep the abstract state class and its concrete implementations together in the same directory, which allows them to be resolved automatically.

Here's what the manual mapping looks like:

```php
abstract class PaymentState extends State
{
    public static $states =[
        Pending::class,
        Paid::class,
        Failed::class,
    ];
    
    // …
}
```

Note that you only need to provide a manual mapping, if the concrete state classes don't live within the same directory as their abstract state class. The following would work out of the box, without adding an explicit mapping:

```
States/
  ├── Failed.php
  ├── Paid.php
  ├── PaymentState.php // This abstract class will automatically detect all relevant states within this directory.
  └── Pending.php
```
