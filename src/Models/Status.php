<?php

namespace SnowPenguinStudios\LaravelModelStatus\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('is_active', true);
    }

    public function scopeDefault(Builder $builder): Builder
    {
        return $builder->where('is_default', true);
    }
}
