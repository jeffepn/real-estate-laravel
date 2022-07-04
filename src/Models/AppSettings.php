<?php

namespace Jeffpereira\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class AppSettings extends Model
{
    use UsesUuid;
    public const NAME_KEY_ROUTE = 'name';

    protected $fillable = ['name', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    public function getValueAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getRouteKeyName()
    {
        return self::NAME_KEY_ROUTE;
    }
}
