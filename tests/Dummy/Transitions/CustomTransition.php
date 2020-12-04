<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use PHPUnit\Framework\Assert;
use Spatie\ModelStates\Tests\Dummy\DummyDependency;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateY;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
use Spatie\ModelStates\Transition;

class CustomTransition extends Transition
{
    private TestModelWithCustomTransition $model;

    private string $message;

    public function __construct(TestModelWithCustomTransition $model, string $message)
    {
        $this->model = $model;

        $this->message = $message;
    }

    public function handle(DummyDependency $dummyDependency): TestModelWithCustomTransition
    {
        Assert::assertNotNull($dummyDependency);

        $this->model->fill([
            'state' => StateY::class,
            'message' => $this->message,
        ])->save();

        return $this->model->refresh();
    }
}
