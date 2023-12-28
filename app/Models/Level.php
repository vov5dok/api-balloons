<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['height', 'point_first_star', 'point_second_star', 'point_third_star', 'number', 'category_id'];

    public function completedLevelByUser()
    {
        return $this->hasMany(CompletedLevel::class)->where('user_id', auth()->id());
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
