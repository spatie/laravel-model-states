<?php

it('can generate abstract states', function () {
    $statesPath = $this->app->basePath('app/Models/States');

    $this->artisan('make:abstract-state AbstractState')->assertExitCode(0);

    expect($statesPath . '/AbstractState.php')
        ->toBeFile()
        ->toContainAsFile('class AbstractState extends State');
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
