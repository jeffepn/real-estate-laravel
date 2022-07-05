<?php

namespace Jeffpereira\RealEstate\Models\Traits\Person;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jeffpereira\RealEstate\Models\Person\TypePerson;

trait Relationships
{
    public function type(): BelongsTo
    {
        return $this->belongsTo(TypePerson::class, 'type_person_id');
    }
}
