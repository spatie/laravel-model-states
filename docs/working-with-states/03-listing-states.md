---
title: Listing states
weight: 3
---

Say you have setup the invoice model as follows:

```php
namespace App;

use App\States\Invoice\Paid;
use App\States\Invoice\Pending;
use App\States\Invoice\Declined;
use App\States\HasStates;
use App\States\Fulfillment\Partial;
use App\States\Fulfillment\Complete;
use App\States\Invoice\InvoiceState;
use App\States\Fulfillment\Unfulfilled;
use Illuminate\Database\Eloquent\Model;
use App\States\Fulfillment\FulfillmentState;

class Invoice extends Model
{
    use HasStates;

    protected function registerStates(): void
    {
        $this
            ->addState('state', InvoiceState::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Declined::class)
            ->default(Pending::class);

        $this
            ->addState('fulfillment', FulfillmentState::class)
            ->allowTransition(Unfulfilled::class, Complete::class)
            ->allowTransition(Unfulfilled::class, Partial::class)
            ->allowTransition(Partial::class, Complete::class);
    }
}

```

## Get Registered States

You can get all the registered states with `Invoice::getStates()`, which returns a collection of state classes, grouped by column:

```php
[
    "state" => [
        'App\States\Invoice\Declined',
        'App\States\Invoice\Paid',
        'App\States\Invoice\Pending',
    ],
    "fulfillment" => [
        'App\States\Fulfillment\Complete',
        'App\States\Fulfillment\Partial',
        'App\States\Fulfillment\Unfulfilled',
    ]
]
```

You can also get the registered states for a specific column with `Invoice::getStatesFor('state')`, which returns a collection of state classes:

```php
[
    'App\States\Invoice\Declined',
    'App\States\Invoice\Paid',
    'App\States\Invoice\Pending',
],
```

## Get Default States

You can get all the default states with `Invoice::getDefaultStates()`, which returns a collection of state classes, keyed by column:

```php
[
    "state" => 'App\States\Invoice\Pending',
    "fulfillment" => null,
]
```

You can also get the default state for a specific column with `Invoice::getDefaultStateFor('state')`, which returns:

```php
'App\States\Invoice\Pending'
```
