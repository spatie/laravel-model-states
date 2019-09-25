---
title: State validation rule
weight: 1
---

This package provides a validation rule to validate incoming request data.

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
