<?php

namespace SnowPenguinStudios\LaravelModelStatus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['model_type', 'model_id', 'status_id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $updateCount = (self::class)::where('model_type', $model->model_type)
                ->where('model_id', $model->model_id)
                ->count();

            $model->sequence_id = $updateCount + 1;

            return $model;
        });
    }
}
