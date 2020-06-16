# Changelog

All notable changes to `state` will be documented in this file

## 1.6.2 - 2020-06-16

- Support model::updated state casting (#88)

## 1.6.1 - 2020-03-27

- change `static::` call to `self::` for private `State::resolveStateMapping` method (#75)

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

- Revert 06a4359a184bc747d7fd8c9b062e4333e9b30f80

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
