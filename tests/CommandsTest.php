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
