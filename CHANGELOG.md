# Changelog

All notable changes to `laravel-model-states` will be documented in this file (#188)

## 2.10.0 - 2024-12-30

### What's Changed

* Added test to ensure same-state transition fails when not allowed by @zayedadel in https://github.com/spatie/laravel-model-states/pull/270

### New Contributors

* @zayedadel made their first contribution in https://github.com/spatie/laravel-model-states/pull/270

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.9.0...2.10.0

## 2.9.0 - 2024-12-16

### What's Changed

* Feature: Added ...transitionArgs to default transition constructor call to allow arguments use for custom default transition by @IlliaVeremiev in https://github.com/spatie/laravel-model-states/pull/269

### New Contributors

* @IlliaVeremiev made their first contribution in https://github.com/spatie/laravel-model-states/pull/269

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.8.0...2.9.0

## 2.8.0 - 2024-12-11

### What's Changed

* Make using allowAllTransitions() less cumbersome by @jonjakoblich in https://github.com/spatie/laravel-model-states/pull/265

### New Contributors

* @jonjakoblich made their first contribution in https://github.com/spatie/laravel-model-states/pull/265

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.7.2...2.8.0

## 2.7.2 - 2024-09-27

### What's Changed

* [docs] Fix documentation badges by @maartenpaauw in https://github.com/spatie/laravel-model-states/pull/254
* [docs] Add generic docblock to code snippet by @maartenpaauw in https://github.com/spatie/laravel-model-states/pull/253
* Fix missing namespace in phpdoc by @conorjmurphy in https://github.com/spatie/laravel-model-states/pull/255
* Update 01-introduction.md by @StreetYo in https://github.com/spatie/laravel-model-states/pull/257
* docs: update 03-listing-states.md improve use statements by @mmachatschek in https://github.com/spatie/laravel-model-states/pull/261
* fix(config): var annotation for `$allowedTransitions` by @maartenpaauw in https://github.com/spatie/laravel-model-states/pull/259

### New Contributors

* @maartenpaauw made their first contribution in https://github.com/spatie/laravel-model-states/pull/254
* @conorjmurphy made their first contribution in https://github.com/spatie/laravel-model-states/pull/255
* @StreetYo made their first contribution in https://github.com/spatie/laravel-model-states/pull/257
* @mmachatschek made their first contribution in https://github.com/spatie/laravel-model-states/pull/261

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.7.1...2.7.2

## 2.7.1 - 2024-03-07

### What's Changed

* Use jsonSerialize in StateCaster by @piotrjoniec in https://github.com/spatie/laravel-model-states/pull/252

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.7.0...2.7.1

## 2.7.0 - 2024-03-01

### What's Changed

* Implement SerializesCastableAttributes in StateCaster by @piotrjoniec in https://github.com/spatie/laravel-model-states/pull/251

### New Contributors

* @piotrjoniec made their first contribution in https://github.com/spatie/laravel-model-states/pull/251

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.6.2...2.7.0

## 2.6.2 - 2024-02-15

### What's Changed

* Add support for laravel 11 by @shuvroroy in https://github.com/spatie/laravel-model-states/pull/249

### New Contributors

* @shuvroroy made their first contribution in https://github.com/spatie/laravel-model-states/pull/249

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.6.1...2.6.2

## 2.6.1 - 2024-02-07

### What's Changed

* Typo in 01-configuring-states.md by @MarceauKa in https://github.com/spatie/laravel-model-states/pull/241
* Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot in https://github.com/spatie/laravel-model-states/pull/240
* Bump actions/cache from 3 to 4 by @dependabot in https://github.com/spatie/laravel-model-states/pull/245
* Generics PHPDoc annotations for State class by @lorenzolosa in https://github.com/spatie/laravel-model-states/pull/247

### New Contributors

* @MarceauKa made their first contribution in https://github.com/spatie/laravel-model-states/pull/241

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.6.0...2.6.1

## 2.6.0 - 2023-09-27

### What's Changed

- Feature/Method to allow all state transitions by @fmeccanici in https://github.com/spatie/laravel-model-states/pull/238

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.5.0...2.6.0

## 2.5.0 - 2023-09-25

### What's Changed

- Bump actions/checkout from 3 to 4 by @dependabot in https://github.com/spatie/laravel-model-states/pull/236
- Feature/Allow to set custom StateChanged event by @fmeccanici in https://github.com/spatie/laravel-model-states/pull/237

### New Contributors

- @fmeccanici made their first contribution in https://github.com/spatie/laravel-model-states/pull/237

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.6...2.5.0

## 2.4.6 - 2023-04-17

### What's Changed

- Don't assume scandir() always returns current and parent directory first by @sebastiandedeyne in https://github.com/spatie/laravel-model-states/pull/228

### New Contributors

- @sebastiandedeyne made their first contribution in https://github.com/spatie/laravel-model-states/pull/228

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.5...2.4.6

## 2.4.5 - 2023-02-20

### What's Changed

- Refactor tests to pest by @AyoobMH in https://github.com/spatie/laravel-model-states/pull/217

### New Contributors

- @AyoobMH made their first contribution in https://github.com/spatie/laravel-model-states/pull/217

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.4...2.4.5

## 2.4.4 - 2023-01-24

### What's Changed

- Add missing `RegisterState` Attribute by @ralphjsmit in https://github.com/spatie/laravel-model-states/pull/224

### New Contributors

- @ralphjsmit made their first contribution in https://github.com/spatie/laravel-model-states/pull/224

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.3...2.4.4

## 2.4.3 - 2023-01-23

### What's Changed

- Recommend not to use hypens in the name by @barclaymichael in https://github.com/spatie/laravel-model-states/pull/210
- Add Dependabot Automation by @patinthehat in https://github.com/spatie/laravel-model-states/pull/218
- Bump actions/checkout from 2 to 3 by @dependabot in https://github.com/spatie/laravel-model-states/pull/219
- Bump actions/cache from 2 to 3 by @dependabot in https://github.com/spatie/laravel-model-states/pull/220
- Add PHP 8.2 Support by @patinthehat in https://github.com/spatie/laravel-model-states/pull/221
- Update deps for laravel 10 by @hailam in https://github.com/spatie/laravel-model-states/pull/223

### New Contributors

- @barclaymichael made their first contribution in https://github.com/spatie/laravel-model-states/pull/210
- @dependabot made their first contribution in https://github.com/spatie/laravel-model-states/pull/219
- @hailam made their first contribution in https://github.com/spatie/laravel-model-states/pull/223

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.2...2.4.3

## 2.4.2 - 2022-08-01

### What's Changed

- Removed Model type enforcement and replaced with PHPDoc by @luckcolors in https://github.com/spatie/laravel-model-states/pull/208

### New Contributors

- @luckcolors made their first contribution in https://github.com/spatie/laravel-model-states/pull/208

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.1...2.4.2

## 2.4.1 - 2022-07-29

### What's Changed

- Update .gitattributes by @angeljqv in https://github.com/spatie/laravel-model-states/pull/205
- Ensures field is always set when changing states by @ChangingTerry in https://github.com/spatie/laravel-model-states/pull/207

### New Contributors

- @angeljqv made their first contribution in https://github.com/spatie/laravel-model-states/pull/205
- @ChangingTerry made their first contribution in https://github.com/spatie/laravel-model-states/pull/207

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.4.0...2.4.1

## 2.4.0 - 2022-06-07

### What's Changed

- Manually register concrete state classes by @javoscript in https://github.com/spatie/laravel-model-states/pull/203

### New Contributors

- @javoscript made their first contribution in https://github.com/spatie/laravel-model-states/pull/203

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.3.0...2.4.0

## 2.3.0 - 2022-04-21

## What's Changed

- Update 03-listing-stated.md // Wrong namespace in the docs by @SahinU88 in https://github.com/spatie/laravel-model-states/pull/200
- Add orWhereState and orWhereNotState by @masterix21 in https://github.com/spatie/laravel-model-states/pull/201

## New Contributors

- @SahinU88 made their first contribution in https://github.com/spatie/laravel-model-states/pull/200
- @masterix21 made their first contribution in https://github.com/spatie/laravel-model-states/pull/201

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.2.0...2.3.0

## 2.2.0 - 2022-03-03

## What's Changed

- Use getMorphClass method when resolving state class. by @kayrunm in https://github.com/spatie/laravel-model-states/pull/198

## New Contributors

- @kayrunm made their first contribution in https://github.com/spatie/laravel-model-states/pull/198

**Full Changelog**: https://github.com/spatie/laravel-model-states/compare/2.1.4...2.2.0

## 2.2.0 - 2022-03-03

- Better internal use of `getMorphClass` (#198)

## 2.1.2 - 2021-10-08

- Support for custom transition classes in `transitionableStates` method

## 2.1.1 - 2021-09-01

- Support for custom transition classes' `canTransition` in `State::canTransitionTo` (#185)

## 2.1.0 - 2021-04-21

- Add default transition config (#159)

## 2.0.2 - 2020-12-09

- Add `State::getModel()` and `State::getField()`

## 2.0.1 - 2020-12-09

- Fix bug on two consecutive transitions (#145)

## 2.0.0 - 2020-12-04

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
- `State::find()` has been removed.
- `State::isOneOf()` is removed, `State::equals` now accepts multiple state objects or morph classes.
- `State::is()` is removed, you should use `State::equals()`.
- Dropped support for Laravel 5, 6, and 7. The minimal required version is `laravel/framework:^8`
- Dropped support for PHP 7.2 and 7.3. The minimal required version is `php:^7.4`
- Proper support for `finalState` in `StateChanged` event

## 1.9.1 - 2020-12-01

- add support for PHP 8.0 ([#141](https://github.com/spatie/laravel-model-states/pull/141))

## 1.9.0 - 2020-08-24

- add support for Laravel 8 ([#101](https://github.com/spatie/laravel-model-states/pull/101))

## 1.8.0 - 2020-08-19

- Add getters for `TransitionNotFound` attributes ([#99](https://github.com/spatie/laravel-model-states/pull/99))

## 1.7.0 - 2020-08-19

- Add `canTransitionTo` ([#92](https://github.com/spatie/laravel-model-states/pull/92))

## 1.6.3 - 2020-06-23

- Support `0` state ([#89](https://github.com/spatie/laravel-model-states/pull/89))

## 1.6.2 - 2020-06-16

- Support model::updated state casting ([#88](https://github.com/spatie/laravel-model-states/issues/88), [351c008](https://github.com/spatie/laravel-model-states/commit/351c008f9e1ab42ed8f9a598d78615787669f43b))

## 1.6.1 - 2020-03-27

- change `static::` call to `self::` for private `State::resolveStateMapping` method ([#75](https://github.com/spatie/laravel-model-states/pull/75))

## 1.6.0 - 2020-03-03

- add support for Laravel 7

## 1.5.1 - 2020-02-18

- Add support for fully qualified column names in `whereState` scope ([#63](https://github.com/spatie/laravel-model-states/pull/63))

## 1.5.0 - 2019-12-13

- ❗️ `$finalState` in the `StateChanged` event is deprecated and will always be null. This is because of a fix for [bug #49](https://github.com/spatie/laravel-model-states/issues/49). This fix might have unforeseen effects if you're using `StateChanged`.

## 1.4.2 - 2019-11-28

- Fix for unknown $modelClass variable ([#47](https://github.com/spatie/laravel-model-states/issues/47))

## 1.4.1 - 2019-10-30

- Return Eloquent model when using transitionTo method directly ([#33](https://github.com/spatie/laravel-model-states/pull/33))

## 1.4.0 - 2019-10-29

- Add better exceptions and Ignition support ([#23](https://github.com/spatie/laravel-model-states/pull/23))

## 1.3.1 - 2019-10-28

- Revert [06a4359](https://github.com/spatie/laravel-model-states/commit/06a4359a184bc747d7fd8c9b062e4333e9b30f80)

## 1.3.0 - 2019-10-28

- Allow to get transitional states ([#17](https://github.com/spatie/laravel-model-states/pull/17))

## 1.2.0 - 2019-10-21

- Add state listing methods ([#21](https://github.com/spatie/laravel-model-states/pull/21))

## 1.1.3 - 2019-10-11

- Proper support for non-string columns

## 1.1.2 - 2019-10-03

- Proper support for JSON serialise

## 1.1.1 - 2019-10-02

- Default support via `new`

## 1.1.0 - 2019-10-02

- Improved default support

## 1.0.1 - 2019-10-02

- Properly handle corrupt state values from the database

## 1.0.0 - 2019-09-27

- initial release
