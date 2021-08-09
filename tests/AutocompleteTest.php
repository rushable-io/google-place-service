<?php

namespace Rushable\GooglePlace\Tests;

use Rushable\GooglePlace\Models\Prediction;

class AutocompleteTest extends TestCase
{
    /**
     * Test autocomplete function return expect result contains expected address in line 1
     */
    public function testAutocomplete(): void
    {
        $predictions = $this->googlePlace->autocomplete('10333 Harwin Dr');

        self::assertIsArray($predictions);
        self::assertCount(5, $predictions);
        $match = $predictions[0];
        self::assertInstanceOf(Prediction::class, $match);
        self::assertEquals('10333 Harwin Drive, Houston, TX, USA', $match->description);
    }

    public function testAutocompleteWithCountryRestriction(): void
    {
        $predictions = $this->googlePlace->autocomplete('1146 ON-21', ['components' => 'country:ca']);

        self::assertIsArray($predictions);
        self::assertCount(1, $predictions);

        $match = $predictions[0];
        self::assertInstanceOf(Prediction::class, $match);
        self::assertEquals('1146 ON-21, Port Elgin, ON, Canada', $match->description);

        $predictions = $this->googlePlace->autocomplete('1146 ON-21', ['components' => 'country:us']);

        self::assertIsArray($predictions);
        self::assertCount(0, $predictions);
    }
}
