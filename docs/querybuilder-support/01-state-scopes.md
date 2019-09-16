---
title: State scopes
weight: 1
---

Every model using the `HasStates` trait will have a `whereState($field, $states)` and a `whereNotState($field, $states)` scope available.

```php
$payments = Payment::whereState('state', Paid::class);
$payments = Payment::whereState('state', [Pending::class, Paid::class]);

$payments = Payment::whereNotState('state', Pending::class);
$payments = Payment::whereNotState('state', [Failed::class, Canceled::class]);
```
