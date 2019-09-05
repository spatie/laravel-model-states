<?php

namespace Spatie\State\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\HasStates;
use Spatie\State\Tests\Dummy\States\Created;

/**
 * @method static self first
 * @method static self find(int $id)
 * @method static self create(array $data = [])
 * @property int id
 */
class Payment extends Model
{
    use HasStates;

    /** @var \Spatie\State\Tests\Dummy\States\PaymentState */
    public $state;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->state = $this->makeState(
            $attributes['state'] ?? Created::class,
            $this
        );
    }
}
