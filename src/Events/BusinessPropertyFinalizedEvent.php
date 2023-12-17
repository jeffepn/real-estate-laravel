<?php

namespace Jeffpereira\RealEstate\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusinessPropertyFinalizedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $businessPropertyId;

    public function __construct(string $businessPropertyId)
    {
        $this->businessPropertyId = $businessPropertyId;
    }
}
