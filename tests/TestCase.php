<?php

namespace Rushable\GooglePlace\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use Rushable\GooglePlace\Service\GooglePlaceService;

abstract class TestCase extends BaseTestCase
{
    protected $googlePlace;
    protected function setUp(): void
    {
        parent::setUp();
        $this->googlePlace = new GooglePlaceService(getenv('GOOGLE_PLACE_KEY'));
    }

    public function invokeMethod($object, string $methodName, array $parameters = [])
    {
        $method = (new ReflectionClass($object))->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs((is_string($object) ? null : $object), $parameters);
    }
}
