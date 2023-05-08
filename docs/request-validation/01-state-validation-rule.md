---
title: State validation rule
weight: 1
---

This package provides validation rules to validate incoming request data.

## Validating state values
Validating state values can be done using the `ValidStateRule` rule.

```php
use Spatie\ModelStates\Validation\ValidStateRule;

request()->validate([
    'state' => new ValidStateRule(PaymentState::class),
]);

// Allowing null
request()->validate([
    'state' => ValidStateRule::make(PaymentState::class)->nullable(),
]);
```

Only valid state values of `PaymentState` implementations will be allowed.

## Validating state transitions

Validating state transitions can be done using the `ValidStateTransitionRule` rule.

Example below shows how to validate a state transition for a `Payment` model.

```php
use Spatie\ModelStates\Validation\ValidStateTransitionRule;

$model = Payment::create([
    'state' => Pending::class,
]);

Validator::make(
    ['state' => Paid::class],
    ['state' => new ValidStateTransitionRule(PaymentState::class, $model)]
)->validate();

// Allowing null 
Validator::make(
    ['state' => null],
    ['state' => (new ValidStateTransitionRule(PaymentState::class, $model))->nullable()]
)->validate();

// Using a custom 'column' on Payment model
$model = Payment::create([
    'custom_column' => Pending::class,
]);
   
Validator::make(
    ['custom_column' => Paid::class],
    ['custom_column' => new ValidStateTransitionRule(PaymentState::class, $model, 'custom_column')]
)->validate();

//via request; assuming payment is available via route model binding

request()->validate([
    'state' => new ValidStateTransitionRule(PaymentState::class,request()->input('payment')),
]);

// Allowing null
request()->validate([
    'state' => ValidStateTransitionRule::make(PaymentState::class,request()->input('payment'))->nullable(),
]);
```
## Validation translations

Validation translations can be published using the `php artisan vendor:publish --tag='model-states-translations'` command. 

Once published, translations can be found in the `lang/vendor/model-states` directory, where they can be updated or new languages added according to user's needs.

```bash
