<?php

namespace Unit\Games\Models;

use App\Countries\Enums\Country;
use App\Countries\Utils\CountryIdGenerator;
use App\Games\Exceptions\HomeAndAwayTeamIdenticalException;
use App\Games\Exceptions\NegativeScoreException;
use App\Games\Models\Game;
use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @test
     */
    public function whenGameIsCreatedWithCorrectData_dataAreValid(): void
    {
        $game = new Game(Country::ARG, Country::BRA);

        self::assertEquals(Country::ARG, $game->getHomeTeam());
        self::assertEquals(0, $game->getHomeTeamScore());
        self::assertEquals(Country::BRA, $game->getAwayTeam());
        self::assertEquals(0, $game->getAwayTeamScore());
        self::assertEquals(CountryIdGenerator::generate(Country::ARG, Country::BRA), $game->getId());
        self::assertEquals((new DateTimeImmutable())->format('Y-m-d'), $game->getCreatedAt()->format('Y-m-d'));
    }

    /**
     * @test
     *
     * @throws NegativeScoreException
     */
    public function whenGameScoreIsSet_dataAreValid(): void
    {
        $game = new Game(Country::ARG, Country::BRA);

        $game->setScores(1, 7);

        self::assertEquals(1, $game->getHomeTeamScore());
        self::assertEquals(7, $game->getAwayTeamScore());
        self::assertEquals(8, $game->calculateSumScores());
    }

    /**
     * @test
     */
    public function whenCountriesAreTheSame_itThrowsException(): void
    {
        $this->expectException(HomeAndAwayTeamIdenticalException::class);

        new Game(Country::ARG, Country::ARG);
    }

    /**
     * @test
     *
     * @dataProvider scoreDataProvider
     */
    public function whenScoreIsNegative_itThrowsException(int $homeTeamScore, int $awayTeamScore): void
    {
        $this->expectException(NegativeScoreException::class);

        $game = new Game(Country::ARG, Country::BRA);

        $game->setScores($homeTeamScore, $awayTeamScore);
    }

    public static function scoreDataProvider(): Generator
    {
        yield [-1, 0];
        yield [0, -1];
    }
}