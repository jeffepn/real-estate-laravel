<?php

namespace Jeffpereira\RealEstate\Models\Traits\Project;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jeffpereira\RealEstate\Models\Person\Person;

trait Relationships
{
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
