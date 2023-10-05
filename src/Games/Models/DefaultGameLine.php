<?php

namespace App\Games\Models;

class DefaultGameLine extends AbstractGameLine
{
    public function __construct(Game $game)
    {
        $this->line = $game->getHomeTeam()->value . ' ' . $game->getHomeTeamScore() . ' - ' . $game->getAwayTeam()->value . ' ' . $game->getAwayTeamScore();
    }
}