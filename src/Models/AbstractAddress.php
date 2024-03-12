<?php

namespace Rushable\GooglePlace\Models;

use Illuminate\Support\Arr;

abstract class AbstractAddress
{
    public $street;
    public $unit;
    public $city;
    public $state;
    public $country;
    public $zipcode;
    public $latitude;
    public $longitude;
    public $timezone;

    public static function abstractBuild($self, array $address)
    {
        $self->latitude = Arr::get($address, 'geometry.location.lat');
        $self->longitude = Arr::get($address, 'geometry.location.lng');

        if ($addressComponents = Arr::get($address, 'address_components')) {
            $streetNumber = $route = '';
            foreach ($addressComponents as $addressComponent) {
                switch (Arr::get($addressComponent, 'types.0')) {

                        // 比较确定的类型，优先选择
                    case 'subpremise':
                        $self->unit = '#' . $addressComponent['short_name'];
                        break;
                    case 'street_number':
                        $streetNumber = $addressComponent['short_name'];
                        break;
                    case 'route':
                        $route = $addressComponent['short_name'];
                        break;
                    case 'locality':
                    case 'neighborhood':
                        $self->city = $addressComponent['short_name'];
                        break;
                    case 'administrative_area_level_1':
                        $self->state = $addressComponent['short_name'];
                        break;
                    case 'country':
                        $self->country = $addressComponent['short_name'];
                        break;
                    case 'postal_code':
                        $self->zipcode = $addressComponent['short_name'];
                        break;

                        // 比较模糊的类型，在上述字段找不到时的备选
                    case 'administrative_area_level_3':
                    case 'administrative_area_level_4':
                        if (!$self->city) $self->city = $addressComponent['short_name'];
                        break;
                    case 'administrative_area_level_6':
                        if (!$route) $route = $addressComponent['short_name'];
                        break;
                    case 'administrative_area_level_7':
                        if (!$streetNumber) $streetNumber = $addressComponent['short_name'];
                        break;
                    default:
                        break;
                }
            }
            $self->street = "$streetNumber $route";
        }

        return $self;
    }
}
