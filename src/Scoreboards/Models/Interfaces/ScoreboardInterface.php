<?php

namespace App\Scoreboards\Models\Interfaces;

use App\Countries\Enums\Country;
use App\Games\Models\Game;

interface ScoreboardInterface
{
    public function addGame(Country $homeTeam, Country $awayTeam): void;

    public function finishGame(Country $homeTeam, Country $awayTeam): bool;

    public function updateGameScore(Country $homeTeam, Country $awayTeam, int $newHomeTeamScore, int $newAwayTeamScore): bool;

    /**
     * @return Game[]
     */
    public function getGames(): array;
}