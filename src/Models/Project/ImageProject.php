<?php

namespace Jeffpereira\RealEstate\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jeffpereira\RealEstate\Models\Common\Image;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class ImageProject extends Model
{
    use UsesUuid;

    protected $fillable = [
        'project_id', 'image_id',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
