<?php

namespace App\Games\Exceptions;

use App\Countries\Enums\Country;
use Exception;

class GameAlreadyExistsException extends Exception
{
    public function __construct(Country $homeTeam, Country $awayTeam)
    {
        parent::__construct('The game between team ' . $homeTeam->value . ' and ' . $awayTeam->value . ' is already in the scoreboard.');
    }
}