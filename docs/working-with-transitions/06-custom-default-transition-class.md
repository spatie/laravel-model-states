---
title: Custom default transition class
weight: 6
---

When working with state transitions, you may need to pass additional contextual data to your `StateChanged` event
listeners. While custom transitions allow this for specific state changes, sometimes you need this functionality for all
transitions. To handle such scenarios `DefaultTransition` class can be extended.

The following example uses different logic depending on how `transitionTo` is called.

Creating custom default transition class:

```php
use Spatie\ModelStates\DefaultTransition;
use Spatie\ModelStates\State;

class CustomDefaultTransitionWithAttributes extends DefaultTransition
{
    public function __construct($model, string $field, State $newState, public bool $silent = false)
    {
        parent::__construct($model, $field, $newState);
    }
}
```


Implement your state change listener to use the custom parameter:

```php
use Spatie\ModelStates\Events\StateChanged;

class OrderStateChangedListener
{
    public function handle(StateChanged $event): void
    {
        $isSilent = $event->transition->silent;

        $this->processOrderState($event->model);

        if (! $isSilent) {
            $this->notifyUser($event->model);
        }
    }
}
```

Now we can pass additional parameter to `transitionTo` method, to omit notification logic:

```php
class OrderService {
    public function markAsPaid(Order $order): void
    {
        // Will trigger notification
        $order->state->transitionTo(PaidState::class);
        // Also can be specified explicitly
        $order->state->transitionTo(PaidState::class, false);
    }

    public function markAsPaidSilently(Order $order): void
    {
        // Will not trigger notification
        $order->state->transitionTo(PaidState::class, true);
    }
}
```

Important notes:

- Custom parameters are only available within the context of the event listeners
- Parameters must be serializable if you plan to queue your state change listeners
