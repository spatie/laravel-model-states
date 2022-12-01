<?php

use Illuminate\Support\Facades\Validator;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Validation\ValidStateRule;

it('test validation', function () {
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

it('nullable validation', function () {
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
