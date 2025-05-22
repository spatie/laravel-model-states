<?php

use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomDirectories;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\Directory1\StateA;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\Directory1\StateB;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\Directory1\StateC;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\Directory2\StateF;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\Directory2\StateG;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\Directory2\StateH;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\DefaultState;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

it('registers all states from custom directories', function () {
    $model = TestModelWithCustomDirectories::create([
        'state' => StateA::class,
    ]);

    $registeredStates = $model->state::config()->registeredStates;

    expect($registeredStates)->toContain(StateA::class);
    expect($registeredStates)->toContain(StateB::class);
    expect($registeredStates)->toContain(StateC::class);
    expect($registeredStates)->toContain(StateF::class);
    expect($registeredStates)->toContain(StateG::class);
    expect($registeredStates)->toContain(StateH::class);
    expect($registeredStates)->toContain(DefaultState::class);
    expect(count($registeredStates))->toBeGreaterThanOrEqual(7);
});

it('can transition between states registered from custom directories', function () {
    $model = TestModelWithCustomDirectories::create([
        'state' => StateA::class,
    ]);

    $model->state->transitionTo(StateF::class);
    $model->refresh();
    expect($model->state)->toBeInstanceOf(StateF::class);
});


it('get default states for', function () {
    $defaultState = TestModelWithCustomDirectories::getDefaultStateFor('state');

    expect($defaultState)->toEqual(StateA::getMorphClass());
});

it('throws if a directory does not exist', function () {
    $config = new \Spatie\ModelStates\StateConfig(
        \Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\RegisterStatesFromCustomDirectories::class
    );
    $nonExistentDir = __DIR__ . '/Dummy/RegisterStatesFromCustomDirectories/DirectoryDoesNotExist';
    expect(fn() => $config->registerStatesFromDirectory($nonExistentDir))->toThrow(DirectoryNotFoundException::class);
});
