<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Figure extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['image', 'figure_type_id'];

    public function figureType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FigureType::class);
    }


    public function scopeHasFigureTypeName($query, $name)
    {
        return $query->whereHas('figureType', function ($query) use ($name) {
            $query->where('name', $name);
        });
    }
}
