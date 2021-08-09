<?php

namespace Rushable\GooglePlace\Contract;

use Rushable\GooglePlace\Models\Address;
use Rushable\GooglePlace\Models\Business;
use Rushable\GooglePlace\Models\Distance;

interface GooglePlace
{
    /**
     * Search address by given keyword input
     *
     * @param string $address
     * @param bool $tz
     * @return Address|null
     */
    public function searchAddress(string $address, bool $tz = false): ?Address;

    /**
     * Search business by given keyword input
     *
     * @param string $address
     * @param string $name
     * @return Business|null
     */
    public function searchBusiness(string $address, string $name): ?Business;

    /**
     * Retrieve autocomplete result
     *
     * @param string $address
     * @param array $options
     * @param string|null $sessionToken
     * @return array
     */
    public function autocomplete(string $address, array $options = [], string $sessionToken = null): array;

    /**
     * Calculate distance between two coordinates
     *
     * @param string $fromLat
     * @param string $fromLng
     * @param string $toLat
     * @param string $toLng
     */
    public function distance(string $fromLat, string $fromLng, string $toLat, string $toLng): ?Distance;
}
