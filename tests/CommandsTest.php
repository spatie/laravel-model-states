<?php

it('can generate abstract states', function () {
    $statesPath = $this->app->basePath('app/Models/States');

    $this->artisan('make:abstract-state AbstractState')->assertExitCode(0);

    expect($statesPath . '/AbstractState.php')
        ->toBeFile()
        ->toContainAsFile('class AbstractState extends State');
});
