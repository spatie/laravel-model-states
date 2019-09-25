<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

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
