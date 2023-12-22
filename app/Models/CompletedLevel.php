<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedLevel extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['count_star', 'level_id', 'user_id'];
}
