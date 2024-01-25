<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['price', 'count', 'money_type_id', 'figure_id'];

    public function scopeProductToCoins()
    {
        return $this->money_type_id == MoneyType::typeCoins()->id;
    }

    public function scopeProductTypeHint()
    {
        return $this->figure_id == FigureType::typeHint()->id;
    }
}
