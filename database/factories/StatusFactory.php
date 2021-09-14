<?php

namespace SnowPenguinStudios\LaravelModelStatus\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SnowPenguinStudios\LaravelModelStatus\Models\Status;

class StatusFactory extends Factory
{
    protected $model = Status::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    public function default()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_default' => true,
            ];
        });
    }
}

