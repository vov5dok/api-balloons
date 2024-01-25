<?php

namespace App\Models;

use App\Enums\MoneyTypes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public function scopeTypeCoins($query)
    {
        return $query->where('name', MoneyTypes::COINS)->first();
    }
}
