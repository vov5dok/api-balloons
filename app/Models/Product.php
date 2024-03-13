<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['price', 'count', 'money_type_id', 'figure_id'];

    public function figure()
    {
        return $this->belongsTo(Figure::class);
    }

    public function scopeProductToCoins()
    {
        return $this->money_type_id == MoneyType::typeCoins()->id;
    }

    public function scopeProductToMoney()
    {
        return $this->money_type_id == MoneyType::typeMoney()->id;
    }

    public function scopeProductTypeHint()
    {
        return $this->figure->figure_type_id == FigureType::typeHint()->id;
    }
}
