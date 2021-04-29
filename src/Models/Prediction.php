<?php

namespace Rushable\GooglePlace\Models;

use Illuminate\Support\Arr;
use JsonSerializable;

class Prediction implements JsonSerializable
{
    public $description;
    public $place_id;
    public $main_text;
    public $secondary_text;

    public static function build(array $prediction): self
    {
        $result = new self();
        $result->description = Arr::get($prediction, 'description');
        $result->place_id  = Arr::get($prediction, 'place_id');
        $result->main_text  = Arr::get($prediction, 'main_text');
        $result->secondary_text  = Arr::get($prediction, 'secondary_text');

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->description;
    }
}
