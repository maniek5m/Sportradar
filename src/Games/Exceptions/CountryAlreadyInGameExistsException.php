<?php

namespace App\Games\Exceptions;

use App\Countries\Enums\Country;
use Exception;

class CountryAlreadyInGameExistsException extends Exception
{
    public function __construct(Country $country)
    {
        parent::__construct('The country ' . $country->value . ' is already in a game.');
    }
}