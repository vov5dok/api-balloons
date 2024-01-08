<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedLevel extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['count_star', 'level_id', 'user_id'];

    public function level(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
