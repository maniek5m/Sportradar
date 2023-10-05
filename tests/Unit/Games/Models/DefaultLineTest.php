<?php

namespace Unit\Games\Models;

use App\Countries\Enums\Country;
use App\Games\Exceptions\NegativeScoreException;
use App\Games\Models\DefaultGameLine;
use App\Games\Models\Game;
use PHPUnit\Framework\TestCase;

class DefaultLineTest extends TestCase
{
    /**
     * @test
     *
     * @throws NegativeScoreException
     */
    public function whenDefaultLineIsCreated_dataAreValid(): void
    {
        $game = new Game(Country::ARG, Country::BRA);
        $game->setScores(1, 3);
        $defaultLine = new DefaultGameLine($game);

        self::assertEquals('Argentina 1 - Brazil 3', $defaultLine->getLine());
    }
}