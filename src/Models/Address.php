<?php

namespace Rushable\GooglePlace\Models;

class Address extends AbstractAddress
{
    public static function build(array $address): self
    {
        $self = new self();

        return self::abstractBuild($self, $address);
    }
}
