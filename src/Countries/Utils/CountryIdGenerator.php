<?php

namespace App\Countries\Utils;

use App\Countries\Enums\Country;

class CountryIdGenerator
{
    public static function generate(Country $homeTeam, Country $awayTeam): string
    {
        return strcmp($homeTeam->name, $awayTeam->name) <= 0
            ? $homeTeam->name . $awayTeam->name
            : $awayTeam->name . $homeTeam->name;
    }
}