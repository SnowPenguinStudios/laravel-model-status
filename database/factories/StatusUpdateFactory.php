<?php

namespace SnowPenguinStudios\LaravelModelStatus\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SnowPenguinStudios\LaravelModelStatus\Models\Status;
use SnowPenguinStudios\LaravelModelStatus\Models\StatusUpdate;

class StatusUpdateFactory extends Factory
{
    protected $model = StatusUpdate::class;

    public function definition()
    {
        return [
            'status_id' => Status::factory()->active()->create(),
        ];
    }
}

