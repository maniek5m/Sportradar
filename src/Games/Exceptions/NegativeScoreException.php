<?php

namespace App\Games\Exceptions;

use Exception;

class NegativeScoreException extends Exception
{
    public function __construct(protected int $score)
    {
        parent::__construct('Score cannot be lower than 0 (given: ' . $score . ')');
    }
}