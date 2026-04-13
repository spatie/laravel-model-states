<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;

/**
 * @property \Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState state
 */
class TestModelWithCastsMethod extends Model
{
    use HasStates;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'state' => ModelState::class,
        ];
    }

    public function getTable()
    {
        return 'test_models';
    }
}
