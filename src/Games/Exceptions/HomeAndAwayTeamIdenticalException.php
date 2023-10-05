<?php

namespace App\Games\Exceptions;

use App\Countries\Enums\Country;
use Exception;

class HomeAndAwayTeamIdenticalException extends Exception
{
    public function __construct(Country $homeTeam, Country $awayTeam)
    {
        parent::__construct('Home team ' . $homeTeam->value . ' and away team ' . $awayTeam->value . ' cannot be the same.');
    }
}