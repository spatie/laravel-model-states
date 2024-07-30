---
title: Listing states
weight: 3
---

Say you have setup the invoice model as follows:

```php
namespace App;

use App\States\Invoice\InvoiceState;
use App\States\Invoice\Declined;
use App\States\Invoice\Paid;
use App\States\Invoice\Pending;
use App\States\Fulfillment\FulfillmentState;
use App\States\Fulfillment\Complete;
use App\States\Fulfillment\Partial;
use App\States\Fulfillment\Unfulfilled;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Invoice extends Model
{
    use HasStates;

    protected $casts = [
        'state' => InvoiceState::class,
        'fulfillment' => FulfillmentState::class,
    ];
}

```

## Get Registered States

You can get all the registered states with `Invoice::getStates()`, which returns a collection of state morph names, grouped by column:

```php
[
    "state" => [
        'declined',
        'paid',
        'pending',
    ],
    "fulfillment" => [
        'complete',
        'partial',
        'unfulfilled',
    ]
]
```

You can also get the registered states for a specific column with `Invoice::getStatesFor('state')`, which returns a collection of state classes:

```php
[
    'declined',
    'paid',
    'pending',
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
