<?php

namespace Unit\Countries;

use App\Countries\Enums\Country;
use App\Countries\Utils\CountryIdGenerator;
use PHPUnit\Framework\TestCase;

class CountryIdGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function whenCountryIdIsGenerated_correctValueIsReturned(): void
    {
        self::assertEquals('ARGBRA', CountryIdGenerator::generate(Country::ARG, Country::BRA));
    }

    /**
     * @test
     */
    public function whenCountryIdIsGenerated_argumentsOrderIsIrrelevant(): void
    {
        self::assertEquals(CountryIdGenerator::generate(Country::BRA, Country::ARG), CountryIdGenerator::generate(Country::ARG, Country::BRA));
    }
}