<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['x', 'y', 'is_exist', 'level_id', 'figure_id', 'count_shoot'];
}
