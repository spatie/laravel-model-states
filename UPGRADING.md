## From 1.x to 2

The biggest change in v2 is that states now know which fields they belong to. So instead of having to pass in fields like so: 

```php
$model->canTransitionTo(StateB::class, 'status');
```

You can now do:

```php
$model->status->canTransitionTo(StateB::class);
```

This change means that a lot of boilerplate code can be removed. Also keep in mind that this package wants you to always use state objects, and never their serialized values. That's why many other methods have been removed, in favour of Laravel's built-in model casts.

- States aren't configured on models anymore, but on the state class itself. Refer to [the docs](/docs/working-with-states/01-configuring-states.md) for more info.
- `HasStates::transitionableStates(string $fromClass, string $field)` has been removed.
- `State::transitionableStates()` now doesn't need the `$field` parameter anymore.
- `HasStates::getStates()` now returns the morph values instead of the hardcoded class names.
- Default states are only set on model creations.
- `State::find()` has been removed, use `State::make` instead.
- `State::isOneOf()` is removed, `State::equals` now accepts multiple state objects or morph classes.
- `State::is()` is removed, you should use `State::equals()`.
- Dropped support for Laravel 5, 6, and 7. The minimal required version is `laravel/framework:^8`
- Dropped support for PHP 7.2 and 7.3. The minimal required version is `php:^7.4`
- Proper support for `finalState` in `StateChanged` event
