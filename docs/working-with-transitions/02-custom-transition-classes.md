---
title: Custom transition classes
weight: 2
---

If you want your transitions to do more stuff than just changing the state, you can use transition classes.

Imagine transitioning a payment's state from pending to failed, which will also save an error message to the database.
Here's what such a basic transition class might look like.

```php
use Spatie\ModelStates\Transition;

class PendingToFailed extends Transition
{
    /** @var Payment */
    private $payment;

    /** @var string */
    private $message;

    public function __construct(Payment $payment, string $message)
    {
        $this->payment = $payment;

        $this->message = $message;
    }

    public function handle(): Payment
    {
        $this->payment->state = new Failed($this->payment);
        $this->payment->failed_at = now();
        $this->payment->error_message = $this->message;

        $this->payment->save();

        return $this->payment;
    }
}
```

Now the transition should be configured in the model:

```php
class Payment extends Model
{
    // …

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransition(Pending::class, Failed::class, PendingToFailed::class);
    }
}
```

It can be used like so:

```php
$payment->state->transitionTo(Failed::class, 'error message');
```

> **Note**: the `State::transitionTo` method will take as many additional arguments as you'd like, 
> these arguments will be passed to the transition's constructor. 
> The first argument in the transition's constructor will always be the model that the transition is performed on. 

Another way of handling transitions is by working directly with the transition classes, this allows for better IDE autocompletion, which can be useful to some people. Instead of using `transitionTo()`, you can use the `transition()` and pass it a transition class directly.

```php
$payment->state->transition(new CreatedToFailed($payment, 'error message'));
```

If you're using the approach above, and want to ensure that this transition can only be performed when the payment is in the `Created` state, you may implement the `canTransition()` method on the transition class itself.

```php
class CreatedToFailed extends Transition
{
    // …

    public function canTransition(): bool
    {
        return $this->payment->state->is(Created::class);
    
        // return $this->payment->state->isOneOf(Created::class, Pending::class);
    }
}
```

If the check in `canTransition()` fails, a `\Spatie\ModelStates\Exceptions\TransitionNotAllowed` will be thrown.

> **Note**: `transition()` also supports a shorthand: `$payment->state->transition(CreatedToFailed::class, 'message')`.
