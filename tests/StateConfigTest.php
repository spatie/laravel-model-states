<?php

namespace Spatie\State\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\Exceptions\InvalidConfig;
use Spatie\State\HasStates;
use Spatie\State\StateConfig;
use Spatie\State\Tests\Dummy\States\Paid;
use Spatie\State\Tests\Dummy\States\PaymentState;
use Spatie\State\Tests\Dummy\States\Pending;

class StateConfigTest extends TestCase
{
    /** @test */
    public function allow_transition_from_is_wrong_subclass()
    {
        $this->expectException(InvalidConfig::class);

        (new StateConfig('state', PaymentState::class))
            ->allowTransition('wrong', Pending::class);
    }

    /** @test */
    public function allow_transition_to_is_wrong_subclass()
    {
        $this->expectException(InvalidConfig::class);

        (new StateConfig('state', PaymentState::class))
            ->allowTransition(Pending::class, 'wrong');
    }

    /** @test */
    public function allow_transition_transition_is_wrong_subclass()
    {
        $this->expectException(InvalidConfig::class);

        (new StateConfig('state', PaymentState::class))
            ->allowTransition(Pending::class, Paid::class, 'wrong');
    }
}
