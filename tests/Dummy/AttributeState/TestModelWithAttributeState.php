<?php

namespace Spatie\ModelStates\Tests\Dummy\AttributeState;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

/**
 * @method static static create(array $extra = [])
 * @method static|Builder whereNotState(string $fieldNames, $states)
 * @method static|Builder whereState(string $fieldNames, $states)
 * @method static static query()
 * @method static self find(int $id)
 * @property AttributeState|null state
 * @property string|null message
 * @property int id
 */
class TestModelWithAttributeState extends Model
{
    protected $guarded = [];

    use HasStates;

    protected $casts = [
        'state' => AttributeState::class,
    ];

    public function getTable()
    {
        return 'test_models';
    }

}
