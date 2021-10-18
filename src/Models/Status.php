<?php

namespace SnowPenguinStudios\LaravelModelStatus\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['model', 'name', 'description', 'is_default', 'is_active', 'order'];

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('is_active', true);
    }

    public function scopeDefault(Builder $builder): Builder
    {
        return $builder->where('is_default', true);
    }

    public function scopeOrder(Builder $builder, string $direction = 'asc'): Builder
    {
        return $builder->orderBy('order', $direction);
    }
}
