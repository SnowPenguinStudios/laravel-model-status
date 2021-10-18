<?php

namespace SnowPenguinStudios\LaravelModelStatus\Tests;

use SnowPenguinStudios\LaravelModelStatus\Models\Status;
use SnowPenguinStudios\LaravelModelStatus\Models\StatusUpdate;
use SnowPenguinStudios\LaravelModelStatus\Tests\Models\DataModel;

class HasStatusTraitTest extends TestCase
{
    /** @test */
    public function can_get_status_relationship()
    {
        $status = Status::factory()->active()->create(['name' => 'TRAIT TEST']);
        $dataModel = DataModel::make();

        $dataModel->name = 'test';
        $dataModel->status_id = $status->id;
        $dataModel->save();

        $this->assertEquals($status->id, $dataModel->status->id);
    }

    /** @test */
    public function can_provide_statuses_available()
    {
        $activeStatuses = Status::factory()
            ->count(3)
            ->active()
            ->create([
                'model' => DataModel::class,
            ]);

        Status::factory()
            ->count(3)
            ->inactive()
            ->create([
                'model' => DataModel::class,
            ]);

        $this->assertEquals(3, $activeStatuses->count());
        $this->assertJson($activeStatuses, DataModel::availableStatuses());
    }

    /** @test */
    public function can_provide_global_statuses_available()
    {
        Status::factory()
            ->count(3)
            ->active()
            ->create();

        Status::factory()
            ->count(3)
            ->active()
            ->create([
                'model' => DataModel::class,
            ]);

        $this->assertEquals(6, Status::all()->count());
        $this->assertJson(Status::all(), DataModel::availableStatuses());
    }

    /** @test */
    public function can_set_model_status_on_creation()
    {
        $status = Status::factory()->active()->create();
        $dataModel = DataModel::create(['name' => 'Test Model', 'status_id' => $status->id]);

        $this->assertEquals($status->id, $dataModel->status_id);
    }

    /** @test */
    public function can_set_model_default_status_on_creation()
    {
        Status::factory()->active()->create();
        $defaultStatus = Status::factory()->active()->default()->create();
        $dataModel = DataModel::create(['name' => 'Test Model']);

        $this->assertEquals($defaultStatus->id, $dataModel->status_id);
    }

    /** @test */
    public function can_update_model_status()
    {
        Status::factory()->active()->default()->create();
        $status = Status::factory()->active()->create();
        $dataModel = DataModel::create(['name' => "Test Model"]);

        $dataModel->update(['status_id' => $status->id]);

        $this->assertEquals($status->id, $dataModel->status_id);
    }

    /** @test */
    public function does_not_set_status_id_when_status_is_not_available()
    {
        $dataModel = DataModel::create(['name' => "Test Model"]);

        $this->assertEquals(null, $dataModel->status_id);
        $this->assertCount(0, $dataModel->status_updates);
    }

    /** @test */
    public function inserts_status_update_when_model_created()
    {
        Status::factory()->active()->default()->create();
        $dataModel = DataModel::create(['name' => "Test Model"]);

        $this->assertCount(1, $dataModel->status_updates);
    }

    /** @test */
    public function inserts_status_update_when_model_status_changes()
    {
        Status::factory()->active()->default()->create();
        $status = Status::factory()->active()->create(['model' => DataModel::class]);
        $dataModel = DataModel::create(['name' => "Test Model"]);

        $dataModel->update(['status_id' => $status->id]);

        $this->assertCount(2, $dataModel->status_updates);
    }

    /** @test */
    public function no_status_update_created_when_model_status_does_not_change()
    {
        Status::factory()->active()->default()->create();
        $dataModel = DataModel::create(['name' => "Test Model"]);
        $dataModel->update(['name' => "Test Model Changes"]);

        $this->assertCount(1, $dataModel->status_updates);
    }

    /** @test */
    public function restores_status_id_to_previous_when_new_status_id_is_not_available()
    {
        $defaultStatus = Status::factory()->active()->default()->create();
        $status = Status::factory()->inactive()->create();
        $dataModel = DataModel::create(['name' => "Test Model"]);

        $dataModel->update(['status_id' => $status->id]);

        $this->assertEquals($defaultStatus->id, $dataModel->status_id);
        $this->assertCount(1, $dataModel->status_updates);
    }

    /** @test */
    public function receive_latest_status_update()
    {
        Status::factory()->active()->default()->create();
        $status = Status::factory()->active()->create(['model' => DataModel::class]);
        $dataModel = DataModel::create(['name' => "Test Model"]);

        $dataModel->update(['status_id' => $status->id]);

        $this->assertEquals($status->id, $dataModel->latestStatusUpdate()->status_id);
    }

    /** @test */
    public function will_default_sort_model_specific_available_statuses_by_id(): void
    {
        $dataModel = DataModel::create(['name' => 'Test Model']);
        $statuses = StatusUpdate::factory()->count(2)->create(['model_type' => DataModel::class, 'model_id' => $dataModel->id]);

        $this->assertEquals([1,2], $statuses->pluck('id')->toArray());
    }

    /** @test */
    public function will_default_sort_model_specific_available_statuses_by_order_asc(): void
    {
        Status::factory()->create(['model' => DataModel::class, 'order' => 1]);
        Status::factory()->create(['model' => DataModel::class, 'order' => 2]);
        Status::factory()->create(['model' => DataModel::class, 'order' => 0]);
        Status::factory()->create(['order' => 3]);

        $this->assertEquals([3,1,2,4], DataModel::availableStatuses('asc')->pluck('id')->toArray());
    }

    /** @test */
    public function will_default_sort_model_specific_available_statuses_by_order_desc(): void
    {
        Status::factory()->create(['model' => DataModel::class, 'order' => 1]);
        Status::factory()->create(['model' => DataModel::class, 'order' => 2]);
        Status::factory()->create(['model' => DataModel::class, 'order' => 0]);
        Status::factory()->create(['order' => 3]);

        $this->assertEquals([4,2,1,3], DataModel::availableStatuses('desc')->pluck('id')->toArray());
    }

    /** @test */
    public function will_default_sort_model_specific_default_status_with_non_model_default_created_before(): void
    {
        Status::factory()->create([ 'order' => 1, 'is_default' => true]);
        $modelDefault = Status::factory()->create(['model' => DataModel::class, 'order' => 1, 'is_default' => true]);

        $this->assertEquals($modelDefault->id, DataModel::defaultStatus()->id);
    }

    /** @test */
    public function will_default_sort_model_specific_default_status_with_non_model_default_created_after(): void
    {
        $modelDefault = Status::factory()->create(['model' => DataModel::class, 'order' => 1, 'is_default' => true]);
        Status::factory()->create([ 'order' => 1, 'is_default' => true]);

        $this->assertEquals($modelDefault->id, DataModel::defaultStatus()->id);
    }
}
