<?php

namespace App\Games\Comparators\Interfaces;

use App\Games\Models\Game;

interface GameComparatorInterface
{
    public static function compare(Game $game1, Game $game2): int;
}