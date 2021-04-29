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
}
