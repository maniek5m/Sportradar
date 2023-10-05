<?php

namespace App\Games\Exceptions;

use App\Countries\Enums\Country;
use Exception;

class GameNotFoundException extends Exception
{
    public function __construct(Country $homeTeam, Country $awayTeam)
    {
        parent::__construct('The game between team ' . $homeTeam->value . ' and ' . $awayTeam->value . ' has not been found in the scoreboard.');
    }
}