<?php

namespace SnowPenguinStudios\LaravelModelStatus\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SnowPenguinStudios\LaravelModelStatus\Models\Status;
use SnowPenguinStudios\LaravelModelStatus\Models\StatusUpdate;

trait HasStatus
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function status_updates(): MorphMany
    {
        return $this->morphMany(StatusUpdate::class, 'model');
    }

    public function latestStatusUpdate(): StatusUpdate
    {
        return $this->status_updates()->orderBy('sequence_id', 'desc')->first();
    }

    public static function availableStatuses(): Collection
    {
        return Status::query()
            ->where(function ($q) {
                return $q->whereNull('model')
                    ->orWhere('model', self::class);
            })->active()
            ->get();
    }

    public static function defaultStatus(): ?Status
    {
        $defaultStatus = Status::query()
            ->where(function ($q) {
                return $q->whereNull('model')
                    ->orWhere('model', self::class);
            })->active()
            ->default()
            ->first();

        return $defaultStatus;
    }

    public static function bootHasStatus()
    {
        static::creating(function ($model) {
            if (! $model->status_id) {
                $defaultStatus = self::defaultStatus();

                $model->status_id = $defaultStatus->id ?? null;
            }

            return $model;
        });

        static::saving(function (Model $model) {
            if (! in_array($model->status_id, $model::availableStatuses()->pluck('id')->toArray())) {
                $model->status_id = $model->getOriginal()['status_id'] ?? null;
            }
            if (is_null($model->status_id) && $model::defaultStatus()) {
                $model->status_id = $model::defaultStatus()->id;
            }

            return $model;
        });

        static::saved(function (Model $model) {
            if (is_null($model->status_id)) {
                return;
            }

            if (key_exists('status_id', $model->getChanges()) || empty($model->getOriginal())) {
                StatusUpdate::create([
                    'model_type' => self::class,
                    'model_id' => $model->id,
                    'status_id' => $model->status_id,
                ]);
            }
        });
    }
}
