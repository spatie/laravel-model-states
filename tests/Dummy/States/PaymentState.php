<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

use Carbon\Carbon;
use Spatie\ModelStates\State;

abstract class PaymentState extends State
{
    /** @var \Spatie\ModelStates\Tests\Dummy\Payment|\Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions */
    protected $model;

    public static $states = [
        Canceled::class,
        Created::class,
        Failed::class,
        Paid::class,
        Pending::class,
        PaidWithoutName::class,
        Refunded::class,
    ];

    /**
     * @var \Spatie\ModelStates\Tests\Dummy\Payment|\Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions
     */
    public function __construct($payment)
    {
        parent::__construct($payment);
    }

    abstract public function color(): string;

    public function shouldSetTimestamp(): bool
    {
        return isset(static::$timestamp);
    }

    public function getTimestampField(): ?string
    {
        return static::$timestamp ?? null;
    }

    public function getTimestamp(): ?Carbon
    {
        return $this->shouldSetTimestamp()
            ? $this->model->{$this->getTimestampField()}
            : null;
    }
}
