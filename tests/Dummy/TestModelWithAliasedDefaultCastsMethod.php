<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\AliasedModelStates\AliasedModelState;

/**
 * @property \Spatie\ModelStates\Tests\Dummy\AliasedModelStates\AliasedModelState state
 */
class TestModelWithAliasedDefaultCastsMethod extends Model
{
    use HasStates;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'state' => AliasedModelState::class,
        ];
    }

    public function getTable(): string
    {
        return 'test_models';
    }
}
