<?php

namespace Rushable\GooglePlace\Service;

use GuzzleHttp\Exception\ClientException;
use Rushable\GooglePlace\Contract\GooglePlace;
use Rushable\GooglePlace\Exceptions\RequestDeniedException;
use Rushable\GooglePlace\Models\Address;
use Rushable\GooglePlace\Models\Candidate;
use Rushable\GooglePlace\Models\Business;
use Rushable\GooglePlace\Models\Distance;
use Rushable\GooglePlace\Models\Prediction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Http\Message\ResponseInterface;

class GooglePlaceService implements GooglePlace
{
    protected $client;

    protected $baseFormParams = [
        'inputtype' => 'textquery',
    ];

    public function __construct(string $key)
    {
        $this->baseFormParams['key'] = $key;
        $stack = HandlerStack::create();
        $stack->push(GuzzleRetryMiddleware::factory([
            'max_retry_attempts' => 3,
        ]));

        $this->client = new Client([
            'base_uri'    => 'https://maps.googleapis.com/maps/api/',
            'handler'     => $stack,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function searchAddress(string $address, bool $tz = false): ?Address
    {
        $candidates = $this->placeSearch($address);

        if (!count($candidates)) {
            return null;
        }

        if ($googleAddress = $this->addressDetail($candidates[0]->place_id)) {
            if ($tz) {
                $googleAddress->timezone = $this->timezone($googleAddress->latitude, $googleAddress->longitude);
            }
            return $googleAddress;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function searchBusiness(string $address, string $name): ?Business
    {
        if (!count($candidates = $this->placeSearch("$name $address"))) {
            return null;
        }

        if ($business = $this->businessDetail($candidates[0]->place_id)) {
            return $business;
        }

        return null;
    }


    /**
     * {@inheritDoc}
     */
    public function autocomplete(string $address, string $sessionToken = null): array
    {
        $predictions = [];
        $response = $this->client->get('place/autocomplete/json', [
            RequestOptions::QUERY => $this->baseFormParams + [
                    'input'        => $address,
                    'sessionToken' => $sessionToken,
                    'types'        => 'address',
                ],
        ]);

        $response = $this->processResponse($response, __FUNCTION__);

        foreach($response['predictions'] as $prediction) {
            $predictions[] = Prediction::build($prediction);
        }

        return $predictions;
    }

    /**
     * {@inheritDoc}
     */
    public function distance(string $fromLat, string $fromLng, string $toLat, string $toLng): ?Distance
    {
        $response = $this->client->get('distancematrix/json', [
            RequestOptions::QUERY => $this->baseFormParams + [
                    'origins'      => "$fromLat,$fromLng",
                    'destinations' => "$toLat,$toLng",
                    'units'        => 'imperial',
                ],
        ]);

        $response = $this->processResponse($response, __FUNCTION__);

        return Distance::build($response);
    }

    /**
     * Get timezone information regarding the coordinate
     *
     * @throws ClientException|RequestDeniedException
     */
    protected function timezone(string $lat, string $lng): string
    {
        $response = $this->client->get('timezone/json', [
            RequestOptions::QUERY => $this->baseFormParams + [
                    'location'  => "$lat,$lng",
                    'timestamp' => (new Carbon())->timestamp,
                ],
        ]);

        $response = $this->processResponse($response, __FUNCTION__);

        return $response['timeZoneId'];
    }

    /**
     * Search place(s) that match the input.
     *
     * @param string $input
     * @return array
     * @throws ClientException|RequestDeniedException
     */
    protected function placeSearch(string $input): array
    {
        $candidates = [];
        $response = $this->client->get('place/findplacefromtext/json', [
            RequestOptions::QUERY => $this->baseFormParams + [
                    'input'  => $input,
                    'fields' => 'place_id,name,formatted_address,geometry/location',
                ],
        ]);

        $response = $this->processResponse($response, __FUNCTION__);

        foreach($response['candidates'] as $candidate) {
            $candidates[] = Candidate::build($candidate);
        }

        return $candidates;
    }

    /**
     * Search the place detail as address
     *
     * @throws ClientException|RequestDeniedException
     */
    protected function addressDetail(string $placeId): ?Address
    {
        $response = $this->client->get('place/details/json', [
            RequestOptions::QUERY => $this->baseFormParams + [
                    'place_id' => $placeId,
                    'fields'   => 'address_component,geometry/location',
                ],
        ]);

        $response = $this->processResponse($response, __FUNCTION__);

        return Address::build($response['result']);
    }

    /**
     * Search the place detail as business
     *
     * @throws ClientException|RequestDeniedException
     */
    protected function businessDetail(string $placeId): ?Business
    {
        $response = $this->client->get('place/details/json', [
            RequestOptions::QUERY => $this->baseFormParams + [
                    'place_id' => $placeId,
                    'fields'   => 'address_component,geometry/location,website,name,business_status,formatted_phone_number,formatted_address',
                ],
        ]);

        $response = $this->processResponse($response, __FUNCTION__);

        return Business::build($response['result']);
    }

    /**
     * Process guzzle response, throw exception when needed.
     *
     * @param ResponseInterface $response
     * @param string $service
     * @return array
     * @throws RequestDeniedException
     */
    protected function processResponse(ResponseInterface $response, string $service): array
    {
        $responseArray = json_decode($response->getBody()->getContents(), true);

        switch ($status = $responseArray['status']) {
            case 'REQUEST_DENIED':
                throw new RequestDeniedException($responseArray['error_message'], $status, $service);
            default:
                break;
        }

        return $responseArray;
    }
}
