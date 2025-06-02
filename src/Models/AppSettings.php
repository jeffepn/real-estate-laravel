<?php

namespace Jeffpereira\RealEstate\Models;

use Database\Factories\AppSettingFactory;
use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppSettings extends Model
{
    use UsesUuid;
    use HasFactory;
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

    protected static function newFactory()
    {
        return AppSettingFactory::new();
    }
}
