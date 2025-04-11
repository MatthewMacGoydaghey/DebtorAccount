<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public bool $full;

    public function __construct($resource, bool $full = false)
    {
        parent::__construct($resource);
        $this->full = $full;
    }
}
