<?php

namespace Rushable\GooglePlace\Tests;

use Rushable\GooglePlace\Models\Address;

class SearchAddressTest extends TestCase
{
    /**
     * Test search address returns correct result
     *
     * @param string $keyword
     * @param array $expected
     * @dataProvider dataProvider
     */
    public function testSearchAddress(string $keyword, array $expected): void
    {
        $address = $this->googlePlace->searchAddress($keyword);

        self::assertNotNull($address);
        $expectedAddress = new Address();
        foreach ($expected as $item => $value) {
            $expectedAddress->$item = $value;
        }
        self::assertEquals($expectedAddress, $address);
    }

    public function dataProvider(): array
    {
        return [
            'Optimal Ninja' => [
                '10333 Harwin Dr. #570, Houston, TX 77036',
                [
                    'street'    => '10333 Harwin Dr.',
                    'unit'      => '#570',
                    'city'      => 'Houston',
                    'state'     => 'TX',
                    'country'   => 'US',
                    'zipcode'   => '77036',
                    'latitude'  => 29.7163393,
                    'longitude' => -95.5479659,
                    'timezone'  => null,
                ],
            ],
            'Jusgo Supermarket' => [
                '9280 Bellaire Blvd, Houston, TX 77036',
                [
                    'street'    => '9280 Bellaire Blvd',
                    'unit'      => null,
                    'city'      => 'Houston',
                    'state'     => 'TX',
                    'country'   => 'US',
                    'zipcode'   => '77036',
                    'latitude'  => 29.7059222,
                    'longitude' => -95.5466758,
                    'timezone'  => null,
                ],
            ]
        ];
    }
}
