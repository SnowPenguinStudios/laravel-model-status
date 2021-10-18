<?php

namespace SnowPenguinStudios\LaravelModelStatus\Tests;

use SnowPenguinStudios\LaravelModelStatus\Models\Status;
use SnowPenguinStudios\LaravelModelStatus\Tests\Models\DataModel;

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

    /** @test */
    public function provides_statuses_by_order_default_asc(): void
    {
        $statuses = collect();
        for ($i = 0; $i < 4; $i++) {
            $statuses->push(Status::factory()->active()->create(['order' => $i]));
        }

        $statusIds = $statuses->pluck('id');
        $currentStatusIds = Status::active()->order()->get()->pluck('id');
        $this->assertEquals($statusIds, $currentStatusIds);
    }

    /** @test */
    public function provides_statuses_by_order_asc(): void
    {
        $statuses = collect();
        for ($i = 0; $i < 4; $i++) {
            $statuses->push(Status::factory()->active()->create(['order' => $i]));
        }

        $statusIds = $statuses->pluck('id');
        $currentStatusIds = Status::active()->order('asc')->get()->pluck('id');
        $this->assertEquals($statusIds, $currentStatusIds);
    }

    /** @test */
    public function provides_statuses_by_order_desc(): void
    {
        $statuses = collect();
        for ($i = 0; $i < 4; $i++) {
            $statuses->push(Status::factory()->active()->create(['order' => $i]));
        }

        $statusIds = $statuses->sortByDesc('id')->pluck('id');
        $currentStatusIds = Status::active()->order('desc')->get()->pluck('id');
        $this->assertEquals($statusIds, $currentStatusIds);
    }

    /** @test */
    public function can_mass_assign_attributes_on_create(): void
    {
        $data = collect([
            'model' => DataModel::class,
            'name' => 'Name',
            'description' => 'Descriptions',
            'is_default' => false,
            'is_active' => true,
            'order' => 1
        ]);

        $status = Status::create($data->toArray());
        $this->assertJson($data, $status);
    }

    /** @test */
    public function can_mass_assign_attributes_on_update(): void
    {
        $status = Status::factory()->active()->create(['model' => DataModel::class]);
        $data = collect([
            'model' => DataModel::class,
            'name' => 'Name',
            'description' => 'Descriptions',
            'is_default' => false,
            'is_active' => true,
            'order' => 1
        ]);

        $status->update($data->toArray());
        $this->assertJson($data, $status);
    }
}
