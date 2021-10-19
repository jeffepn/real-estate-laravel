<?php

namespace Jeffpereira\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class AppSettings extends Model
{
    use UsesUuid;

    protected $guarded = [];

    protected $casts = [
        'value' => 'array'
    ];
}
