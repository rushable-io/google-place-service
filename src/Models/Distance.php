<?php

namespace Rushable\GooglePlace\Models;

use Illuminate\Support\Arr;

class Distance
{
    public $distance;
    public $duration;

    public static function build(array $distance): self
    {
        $self = new self();
        $self->distance = Arr::get($distance, 'rows.0.elements.0.distance.value');
        $self->duration = Arr::get($distance, 'rows.0.elements.0.duration.value');

        return $self;
    }

    public function getMiles(): float
    {
        return $this->distance ? $this->distance / 1609.34 : 0;
    }
}
