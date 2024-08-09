<?php

it('can generate abstract states', function () {
    $file = $this->app->basePath('app/Models/States') . '/AbstractState.php';

    $this->artisan('make:abstract-state AbstractState')->assertExitCode(0);

    expect($file)
        ->toBeFile()
        ->toContainAsFile('class AbstractState extends State');

    unlink($file);
});

it('can generate states', function () {
    $file = $this->app->basePath('app/Models/States') . '/State.php';

    $this->artisan('make:state State')
        ->expectsQuestion('What is the name of the parent abstract state?', 'AbstractState')
        ->assertExitCode(0);

    expect($file)
        ->toBeFile()
        ->toContainAsFile('class State extends AbstractState');

    unlink($file);
});

it('can generate transitions', function () {
    $file = $this->app->basePath('app/Models/States') . '/CustomTransition.php';

    $this->artisan('make:transition CustomTransition')
        ->expectsQuestion('What is the name of the model?', 'Model')
        ->assertExitCode(0);

    expect($file)
        ->toBeFile()
        ->toContainAsFile('class CustomTransition extends Transition')
        ->toContainAsFile('private readonly Model $model,')
        ->toContainAsFile('return $this->model;');

    unlink($file);
});
