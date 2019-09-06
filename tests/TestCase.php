<?php

namespace Spatie\State\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
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
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }
}
