<?php

namespace Rushable\GooglePlace\Models;

use Illuminate\Support\Arr;

class Business extends AbstractAddress
{
    public $formatted_address;
    public $name;
    public $formatted_phone_number;
    public $business_status;
    public $website;

    public static function build(array $place): self
    {
        $self = self::abstractBuild(new self(), $place);

        $self->formatted_address = Arr::get($place, 'formatted_address');
        $self->name = Arr::get($place, 'name');
        $self->formatted_phone_number = Arr::get($place, 'formatted_phone_number');
        $self->business_status = Arr::get($place, 'business_status');
        $self->website = Arr::get($place, 'website');

        return $self;
    }
}
