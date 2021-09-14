<?php

namespace SnowPenguinStudios\LaravelModelStatus\Tests;

use SnowPenguinStudios\LaravelModelStatus\Models\Status;

class StatusTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function provides_no_statuses(): void
    {
        $this->assertEmpty(Status::all());
    }

    /** @test */
    public function provides_all_statuses(): void
    {
        Status::factory()->count(2)->create();

        $this->assertCount(2, Status::all());
    }

    /** @test */
    public function provides_active_statuses(): void
    {
        $activeStatus = Status::factory()->active()->create();
        Status::factory()->inactive()->create();

        $this->assertCount(1, Status::active()->get());
        $this->assertJson(collect($activeStatus), Status::active()->get());
    }
}
