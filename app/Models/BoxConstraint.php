<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxConstraint extends Model
{
    protected $fillable = [
        'name',
        'length',
        'width',
        'height',
        'weight_limit',
    ];
}
