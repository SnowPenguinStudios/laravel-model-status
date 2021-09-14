<?php

namespace SnowPenguinStudios\LaravelModelStatus\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use SnowPenguinStudios\LaravelModelStatus\Traits\HasStatus;

class DataModel extends Model
{
    use HasStatus;

    protected $table = 'data_models';

    protected $fillable = ['name', 'status_id'];
}
