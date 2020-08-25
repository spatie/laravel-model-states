<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\States\ModelState;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\States\StateC;
use Spatie\ModelStates\Tests\Dummy\States\StateD;

/**
 * @method static self create(array $extra = [])
 * @method static self find(int $id)
 * @property ModelState state
 */
class TestModel extends Model
{
    protected $guarded = [];

    use HasStates;

    public function getTable()
    {
        return 'test_models';
    }

    public function registerStates(): void
    {
        $this
            ->addState('state', ModelState::class)
            ->allowTransition(StateA::class, StateB::class)
            ->allowTransition(StateA::class, StateC::class)
            ->allowTransition(StateA::class, StateD::class);
    }
}
