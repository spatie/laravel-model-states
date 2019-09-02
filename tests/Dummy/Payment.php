<?php

namespace Spatie\State\Tests\Dummy;

use Spatie\State\Stateful;
use Spatie\State\Tests\Dummy\States\PaymentState;
use Spatie\State\Tests\Dummy\States\Pending;

class Payment implements Stateful
{
    /** @var \Spatie\State\Tests\Dummy\States\Pending */
    public $state;

    /** @var string */
    public $paid_at;

    /** @var string */
    public $canceled_at;

    /** @var string */
    public $errored_at;

    /** @var string */
    public $error_message;

    public function __construct()
    {
        $this->state = new Pending($this);
    }

    public function setState(PaymentState $state)
    {
        $this->state = $state;
    }

    public function getState(): PaymentState
    {
        return $this->state;
    }
}
