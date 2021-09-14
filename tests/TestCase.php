<?php

namespace SnowPenguinStudios\LaravelModelStatus\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use SnowPenguinStudios\LaravelModelStatus\Providers\LaravelModelStatusServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'SnowPenguinStudios\\LaravelModelStatus\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->migrateTables();
        $this->createTestTables();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelModelStatusServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey(config()['app.cipher'])
        ));
    }

    protected function migrateTables()
    {
        include_once __DIR__.'/../database/migrations/create_statuses_table.php.stub';
        (new \CreateStatusesTable())->up();

        include_once __DIR__.'/../database/migrations/create_status_updates_table.php.stub';
        (new \CreateStatusUpdatesTable())->up();
    }

    protected function createTestTables()
    {
        Schema::create('data_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('status_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
