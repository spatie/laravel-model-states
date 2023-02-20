<?php

use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateC;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateD;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateE;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateA;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateB;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateTransition;
use Spatie\ModelStates\Tests\Dummy\AttributeState\TestModelWithAttributeState;

it('test default', function () {
    $model = new TestModelWithAttributeState();

    expect($model->state->equals(AttributeStateA::class))->toBeTrue();
})->skip(PHP_VERSION_ID < 80000, 'Not PHP 8');

it('test allowed transition', function () {
    $model = new TestModelWithAttributeState();

    $model->state->transitionTo(AttributeStateB::class);

    expect($model->state->equals(AttributeStateB::class))->toBeTrue();
    expect(AttributeStateTransition::$transitioned)->toBeTrue();
})->skip(PHP_VERSION_ID < 80000, 'Not PHP 8');

it('should allow transition', function () {
    $model = new TestModelWithAttributeState();

    $model->state->transitionTo(AttributeStateB::class);

    expect($model->state->equals(AttributeStateB::class))->toBeTrue();
    expect(AttributeStateTransition::$transitioned)->toBeTrue();
})->skip(PHP_VERSION_ID < 80000, 'Not PHP 8');

it('should register states', function () {
    $model = new TestModelWithAttributeState();

    expect(AttributeStateA::config()->registeredStates)->toEqual([AttributeStateC::class, AttributeStateD::class, AttributeStateE::class]);
    expect(AttributeStateC::config()->registeredStates)->toEqual([AttributeStateC::class, AttributeStateD::class, AttributeStateE::class]);

    expect($model->state->equals(AttributeStateA::class))->toBeTrue();

    $model->state->transitionTo(AttributeStateC::class);

    expect($model->state->equals(AttributeStateC::class))->toBeTrue();
})->skip(PHP_VERSION_ID < 80000, 'Not PHP 8');
