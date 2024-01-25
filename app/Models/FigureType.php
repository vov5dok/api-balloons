<?php

namespace App\Models;

use App\Enums\FigureTypes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FigureType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public function scopeTypeHint($query)
    {
        return $query->where('name', FigureTypes::Hint)->first();
    }
}
