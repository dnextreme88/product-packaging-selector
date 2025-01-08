<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BoxConstraint extends Model
{
    protected $fillable = [
        'name',
        'length',
        'width',
        'height',
        'volume',
        'weight_limit',
    ];

    protected function volume(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->length * $this->width * $this->height,
            set: fn () => $this->length * $this->width * $this->height
        );
    }
}
