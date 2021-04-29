<?php

namespace Rushable\GooglePlace\Tests;

class DistanceTest extends TestCase
{
    /**
     * Test distance function returns expected result
     */
    public function testDistance(): void
    {
        $from = [
            'latitude'  => 29.7163393,
            'longitude' => -95.5479659,
            ];
        $to = [
            'latitude'  => 29.7059222,
            'longitude' => -95.5466758,
        ];
        $distance = $this->googlePlace->distance($from['latitude'], $from['longitude'], $to['latitude'], $to['longitude']);

        self::assertGreaterThan(0, $distance->distance);
        self::assertGreaterThan(0, $distance->duration);
    }
}
