<?php

use Illuminate\Support\Facades\Validator;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateD;
use Spatie\ModelStates\Tests\Dummy\TestModel;
use Spatie\ModelStates\Validation\ValidStateRule;
use Spatie\ModelStates\Validation\ValidStateTransitionRule;

it('test state validation', function () {
    $rule = new ValidStateRule(ModelState::class);

    expect(! Validator::make(
        ['state' => StateA::getMorphClass()],
        ['state' => $rule]
    )->fails())->toBeTrue();

    expect(! Validator::make(
        ['state' => 'wrong'],
        ['state' => $rule]
    )->fails())->toBeFalse();
});

it('test nullable state validation', function () {
    $rule = (new ValidStateRule(ModelState::class))->required();

    expect(Validator::make(
        ['state' => null],
        ['state' => $rule]
    )->fails())->toBeTrue();

    $rule = (new ValidStateRule(ModelState::class))->nullable();

    expect(Validator::make(
        ['state' => null],
        ['state' => $rule]
    )->fails())->toBeFalse();
});


it('test transition validation', function () {
    $modelA = TestModel::create([
        'state' => StateA::class,
    ]);
    $rule = new ValidStateTransitionRule(ModelState::class,$modelA);

    expect(Validator::make(
        ['state' => StateB::getMorphClass()],
        ['state' => $rule]
    )->passes())->toBeTrue();

    expect(Validator::make(
        ['state' => StateC::getMorphClass()],
        ['state' => $rule]
    )->passes())->toBeTrue();


    $modelB= TestModel::create([
        'state' => StateB::class,
    ]);
    $rule = new ValidStateTransitionRule(ModelState::class,$modelB);

    expect(Validator::make(
        ['state' => StateA::getMorphClass()],
        ['state' => $rule]
    )->fails())->toBeTrue()
        ->and(Validator::make(
            ['state' => StateD::getMorphClass()],
            ['state' => $rule]
        )->fails())->toBeTrue();
});

it('test nullable transition validation', function () {
    $modelA = TestModel::create([
        'state' => StateA::class,
    ]);
    $rule = (new ValidStateTransitionRule(ModelState::class,$modelA))->required();
    expect(Validator::make(
        ['state' => null],
        ['state' => $rule]
    )->fails())->toBeTrue();

    $rule = (new ValidStateTransitionRule(ModelState::class,$modelA))->nullable();
    expect(Validator::make(
        ['state' => null],
        ['state' => $rule]
    )->fails())->toBeFalse();
});
