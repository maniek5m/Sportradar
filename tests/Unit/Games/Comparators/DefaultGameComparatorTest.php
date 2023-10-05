<?php

namespace Unit\Games\Comparators;

use App\Countries\Enums\Country;
use App\Games\Comparators\DefaultGameComparator;
use App\Games\Exceptions\NegativeScoreException;
use App\Games\Models\Game;
use PHPUnit\Framework\TestCase;

class DefaultGameComparatorTest extends TestCase
{
    /**
     * @test
     *
     * @throws NegativeScoreException
     */
    public function whenDefaultComparatorIsUsed_correctValueIsReturned(): void
    {
        $game1 = new Game(Country::ARG, Country::BRA);
        $game1->setScores(1, 3);

        $game2 = new Game(Country::CAN, Country::DEU);
        $game2->setScores(2, 4);

        self::assertEquals(1, DefaultGameComparator::compare($game1, $game2));
        self::assertEquals(-1, DefaultGameComparator::compare($game2, $game1));

        $game1->setScores(3, 3);

        self::assertEquals(1, DefaultGameComparator::compare($game1, $game2));
        self::assertEquals(-1, DefaultGameComparator::compare($game2, $game1));
    }
}