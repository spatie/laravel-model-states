<?php

namespace Spatie\ModelStates\Tests;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var \Carbon\Carbon */
    protected $knownDate;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        Carbon::setTestNow(Carbon::create(2020, 1, 4, 16, 43, 00));
        $this->knownDate = Carbon::now();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app[ 'config' ]->set('database.default', 'sqlite');
        $app[ 'config' ]->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUpDatabase()
    {
        $this->app->get('db')->connection()->getSchemaBuilder()->create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('state')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->datetime('failed_at')->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });

        $this->app->get('db')->connection()->getSchemaBuilder()->create('model_with_multiple_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stateA')->nullable();
            $table->string('stateB')->nullable();
            $table->timestamps();
        });
    }
}
