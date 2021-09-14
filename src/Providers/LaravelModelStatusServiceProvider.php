<?php

namespace SnowPenguinStudios\LaravelModelStatus\Providers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class LaravelModelStatusServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public const PACKAGE = 'laravel-model-status';

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->publishMigrations();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__."/../../config/".$this::PACKAGE.".php", $this::PACKAGE);
    }

    private function publishMigrations(): array
    {
        $now = Carbon::now();
        $migrationsPath = 'migrations/';

        foreach (glob(__DIR__."/../../database/${migrationsPath}*.php.stub") as $filename) {
            if (! $this->migrationFileExists($filename)) {
                $this->createPublishEntry($filename, $now);
            }
        }

        return [];
    }

    private function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName) + 4;

        $migrationsPath = 'migrations/';
        if (Str::contains($migrationFileName, '/')) {
            $migrationsPath .= Str::of($migrationFileName)->beforeLast('/')->finish('/');
            $migrationFileName = Str::of($migrationFileName)->afterLast('/');
        }

        foreach (glob(database_path("${migrationsPath}*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName . '.php')) {
                return true;
            }
        }

        return false;
    }

    private function createPublishEntry($migrationFileName, Carbon $now): void
    {
        $this->publishes([
            $migrationFileName => with($migrationFileName, function ($migrationFileName) use ($now) {
                $migrationPath = 'migrations/';

                if (Str::contains($migrationFileName, '/')) {
                    $migrationFileName = Str::of($migrationFileName)->afterLast('/')->substr(0, -5);
                }

                return database_path($migrationPath . $now->addSecond()->format('Y_m_d_His') . '_' . Str::of($migrationFileName)->snake()->finish('.php'));
            }),
        ], $this::PACKAGE."-migrations");
    }
}
