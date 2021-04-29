<?php

namespace Rushable\GooglePlace\Models;

use Illuminate\Support\Arr;

class Candidate
{
    public $place_id;
    public $formatted_address;
    public $name;
    public $latitude;
    public $longitude;

    public static function build(array $candidate): self
    {
        $result = new self();
        $result->formatted_address = Arr::get($candidate, 'formatted_address');
        $result->place_id = Arr::get($candidate, 'place_id');
        $result->name = Arr::get($candidate, 'name');
        $result->latitude = Arr::get($candidate, 'geometry.location.lat');
        $result->longitude = Arr::get($candidate, 'geometry.location.lng');

        return $result;
    }
}
