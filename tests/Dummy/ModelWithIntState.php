<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\IntStates\IntState;
use Spatie\ModelStates\Tests\Dummy\IntStates\IntStateA;

/**
 * @property \Spatie\ModelStates\Tests\Dummy\IntStates\IntState $state
 */
class ModelWithIntState extends Model
{
    protected $guarded = [];

    protected $table = 'model_with_int_state';

    use HasStates;

    public static function migrate(): void
    {
        app()->get('db')->connection()->getSchemaBuilder()->create('model_with_int_state', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('state')->nullable();
            $table->timestamps();
        });
    }

    protected function registerStates(): void
    {
        $this->addState('state', IntState::class)
            ->default(IntStateA::class);
    }
}
