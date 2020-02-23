---
title: State scopes
weight: 1
---

Every model using the `HasStates` trait will have a `whereState($column, $states)` and a `whereNotState($column, $states)` scope available.

```php
$payments = Payment::whereState('state', Paid::class);
$payments = Payment::whereState('state', [Pending::class, Paid::class]);

$payments = Payment::whereNotState('state', Pending::class);
$payments = Payment::whereNotState('state', [Failed::class, Canceled::class]);
```

When the state field has another column name in the query (for example due to a join), it is possible to use the full column name: 

```php
$payments = Payment::whereState('payments.state', Paid::class);

$payments = Payment::whereNotState('payments.state', Pending::class);
```
