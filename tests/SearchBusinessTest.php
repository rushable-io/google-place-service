<?php

namespace Rushable\GooglePlace\Tests;

use Rushable\GooglePlace\Models\Business;

class SearchBusinessTest extends TestCase
{
    /**
     * Test search business returns correct result.
     *
     * @param string $keyword
     * @param string $name
     * @param array $expected
     * @dataProvider dataProvider
     */
    public function testSearchBusiness(string $keyword, string $name, array $expected): void
    {
        $business = $this->googlePlace->searchBusiness($keyword, $name);

        self::assertNotNull($business);
        $expectedBusiness = new Business();
        foreach ($expected as $item => $value) {
            $expectedBusiness->$item = $value;
        }
        self::assertEquals($expectedBusiness, $business);
    }

    public function dataProvider(): array
    {
        return [
            'Optima Ninja' => [
                '10333 Harwin Dr. #570, Houston, TX 77036',
                'Optima Ninja',
                [
                    'formatted_address'      => '10333 Harwin Dr. #570, Houston, TX 77036, USA',
                    'name'                   => 'Optima Ninja',
                    'formatted_phone_number' => null,
                    'business_status'        => 'OPERATIONAL',
                    'website'                => 'https://optimaninja.com/',
                    'street'                 => '10333 Harwin Dr.',
                    'unit'                   => '#570',
                    'city'                   => 'Houston',
                    'state'                  => 'TX',
                    'country'                => 'US',
                    'zipcode'                => '77036',
                    'latitude'               => 29.71633599999999,
                    'longitude'              => -95.547906,
                    'timezone'               => null,
                ],
            ],
            'Jusgo Supermarket' => [
                '9280 Bellaire Blvd, Houston, TX 77036',
                'Jusgo Supermarket',
                [
                    'formatted_address'      => '9280 Bellaire Blvd, Houston, TX 77036, USA',
                    'name'                   => 'Jusgo Supermarket',
                    'formatted_phone_number' => '(713) 270-1658',
                    'business_status'        => 'OPERATIONAL',
                    'website'                => 'http://www.jusgosupermarket.com/houston/index-cn4.html',
                    'street'                 => '9280 Bellaire Blvd',
                    'unit'                   => null,
                    'city'                   => 'Houston',
                    'state'                  => 'TX',
                    'country'                => 'US',
                    'zipcode'                => '77036',
                    'latitude'               => 29.7059219,
                    'longitude'              => -95.5469068,
                    'timezone'               => null,
                ],
            ]
        ];
    }
}
