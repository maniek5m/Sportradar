<?php

namespace App\Games\Comparators;

use App\Games\Comparators\Interfaces\GameComparatorInterface;
use App\Games\Models\Game;

class DefaultGameComparator implements GameComparatorInterface
{
    public static function compare(Game $game1, Game $game2): int
    {
        $sumScores1 = $game1->calculateSumScores();
        $sumScores2 = $game2->calculateSumScores();

        if ($sumScores1 !== $sumScores2) {
            return $sumScores1 < $sumScores2 ? 1 : -1;
        }

        return $game1->getCreatedAt() < $game2->getCreatedAt() ? 1 : -1;
    }
}