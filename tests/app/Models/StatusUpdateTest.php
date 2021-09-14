<?php

namespace SnowPenguinStudios\LaravelModelStatus\Tests;

use SnowPenguinStudios\LaravelModelStatus\Models\Status;
use SnowPenguinStudios\LaravelModelStatus\Models\StatusUpdate;
use SnowPenguinStudios\LaravelModelStatus\Tests\Models\DataModel;

class StatusUpdateTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function provides_no_status_updates(): void
    {
        $this->assertEmpty(StatusUpdate::all());
    }

    /** @test */
    public function provides_all_statuses(): void
    {
        $dataModel = DataModel::create(['name' => 'Test Model']);
        $statuses = StatusUpdate::factory()->count(2)->create(['model_type' => DataModel::class, 'model_id' => $dataModel->id]);

        $this->assertCount(2, Status::all());
        $this->assertJson(collect($statuses), Status::all());
    }
}
