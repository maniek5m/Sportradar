<?php

namespace App\Scoreboards\Utils;

use App\Games\Comparators\DefaultGameComparator;
use App\Games\Comparators\Interfaces\GameComparatorInterface;
use App\Games\Models\AbstractGameLine;
use App\Games\Models\DefaultGameLine;
use App\Games\Models\Game;
use App\Scoreboards\Models\Interfaces\ScoreboardInterface;
use InvalidArgumentException;

class ScoreboardSummaryGenerator
{
    /**
     * @return AbstractGameLine[]
     */
    public function summary(ScoreboardInterface $scoreboard, ?string $gameComparatorClass = DefaultGameComparator::class, string $gameLineClass = DefaultGameLine::class): array
    {
        if ($gameComparatorClass !== null && !is_a($gameComparatorClass, GameComparatorInterface::class, true)) {
            throw new InvalidArgumentException('GameComparatorClass has to be null or instance of GameComparatorInterface.');
        }

        if (!is_a($gameLineClass, AbstractGameLine::class, true)) {
            throw new InvalidArgumentException('GameLineClass has to be instance of AbstractGameLine.');
        }

        $games = $scoreboard->getGames();

        if ($gameComparatorClass !== null) {
            uasort($games, [$gameComparatorClass, 'compare']);
        }

        return array_map(static function (Game $game) use ($gameLineClass) {
            return new $gameLineClass($game);
        }, $games);
    }
}