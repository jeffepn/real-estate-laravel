<?php

namespace Jeffpereira\RealEstate\Models\Project;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Jeffpereira\RealEstate\Models\Common\Image;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class ImageProject extends Pivot
{
    use UsesUuid;

    public $table = 'image_projects';

    protected $fillable = [
        'project_id', 'image_id',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
