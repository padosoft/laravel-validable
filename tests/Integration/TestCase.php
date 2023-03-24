<?php

namespace Padosoft\Laravel\Validable\Test\Integration;

use File;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{


    /**
     * @var \Padosoft\Laravel\Validable\Test\Integration\TestModel
     */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

    }

    protected function tearDown(): void
    {
        //remove created path during test
        //$this->removeCreatedPathDuringTest(__DIR__);
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->initializeDirectory($this->getTempDirectory());

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            //'database' => $this->getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);
    }

    /**
     * @param  $app
     */
    protected function setUpDatabase(Application $app)
    {
        //   file_put_contents($this->getTempDirectory().'/database.sqlite', null);

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('order')->default(0);
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_models_with_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->date('date_test');
            $table->dateTime('datetime_test');
        });
    }

    protected function initializeDirectory(string $directory)
    {
        return;
        /*
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
        */
    }

    public function getTempDirectory(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'temp';
    }

    public function getSysTempDirectory(): string
    {
        return sys_get_temp_dir();
    }
}
