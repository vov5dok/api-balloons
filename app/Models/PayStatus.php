<?php

namespace App\Models;

use App\Enums\PayStatuses;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayStatus extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public function scopeStatusCreated()
    {
        return $this->where('name', PayStatuses::CREATED->value);
    }

    public function scopeStatusPayed()
    {
        return $this->where('name', PayStatuses::PAYED->value);
    }

    public function scopeStatusCanceled()
    {
        return $this->where('name', PayStatuses::CANCELLED->value);
    }
}
