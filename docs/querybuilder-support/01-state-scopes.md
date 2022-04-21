---
title: State scopes
weight: 1
---

Every model using the `HasStates` trait will have these scopes available: 
- `whereState($column, $states)` and `orWhereState($column, $states)` 
- `whereNotState($column, $states)` and `orWhereNotState($column, $states)`

```php
$payments = Payment::whereState('state', Paid::class);
$payments = Payment::whereState('state', [Pending::class, Paid::class]);
$payments = Payment::whereState('state', Pending::class)->orWhereState('state', Paid::class);

$payments = Payment::whereNotState('state', Pending::class);
$payments = Payment::whereNotState('state', [Failed::class, Canceled::class]);
$payments = Payment::whereNotState('state', Failed::class)->orWhereNotState('state', Canceled::class);
```

When the state field has another column name in the query (for example due to a join), it is possible to use the full column name: 

```php
$payments = Payment::whereState('payments.state', Paid::class);

$payments = Payment::whereNotState('payments.state', Pending::class);
```
