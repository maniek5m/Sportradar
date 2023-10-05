<?php

namespace Unit\Scoreboards\Models;

use App\Countries\Enums\Country;
use App\Countries\Utils\CountryIdGenerator;
use App\Games\Exceptions\CountryAlreadyInGameExistsException;
use App\Games\Exceptions\GameAlreadyExistsException;
use App\Games\Exceptions\GameNotFoundException;
use App\Games\Exceptions\HomeAndAwayTeamIdenticalException;
use App\Games\Exceptions\NegativeScoreException;
use App\Scoreboards\Models\Scoreboard;
use Generator;
use PHPUnit\Framework\TestCase;

class ScoreboardTest extends TestCase
{
    private Scoreboard $scoreboard;

    /**
     * @throws GameAlreadyExistsException
     * @throws HomeAndAwayTeamIdenticalException
     * @throws CountryAlreadyInGameExistsException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->scoreboard = new Scoreboard();
        $this->scoreboard->addGame(Country::ARG, Country::BRA);
    }

    /**
     * @test
     *
     * @throws CountryAlreadyInGameExistsException
     * @throws GameAlreadyExistsException
     * @throws HomeAndAwayTeamIdenticalException
     */
    public function whenGameCanBeAdded_gamesIsAdded(): void
    {
        $this->scoreboard->addGame(Country::CAN, Country::DEU);

        self::assertArrayHasKey(CountryIdGenerator::generate(Country::CAN, Country::DEU), $this->scoreboard->getGames());
        self::assertArrayHasKey(Country::CAN->name, $this->scoreboard->getCountries());
        self::assertArrayHasKey(Country::DEU->name, $this->scoreboard->getCountries());
    }

    /**
     * @test
     *
     * @throws GameNotFoundException
     */
    public function whenGameCanBeFinished_gamesIsRemoved(): void
    {
        $this->scoreboard->finishGame(Country::ARG, Country::BRA);

        self::assertArrayNotHasKey(CountryIdGenerator::generate(Country::ARG, Country::BRA), $this->scoreboard->getGames());
        self::assertArrayNotHasKey(Country::ARG->name, $this->scoreboard->getCountries());
        self::assertArrayNotHasKey(Country::BRA->name, $this->scoreboard->getCountries());
    }

    /**
     * @test
     *
     * @throws GameNotFoundException
     * @throws NegativeScoreException
     */
    public function whenGameScoreCanBeUpdated_scoresAreUpdated(): void
    {
        $newHomeTeamScore = 1;
        $newAwayTeamScore = 7;
        $this->scoreboard->updateGameScore(Country::ARG, Country::BRA, $newHomeTeamScore, $newAwayTeamScore);

        $id = CountryIdGenerator::generate(Country::ARG, Country::BRA);
        $games = $this->scoreboard->getGames();
        self::assertArrayHasKey($id, $games);

        self::assertEquals($newHomeTeamScore, $games[$id]->getHomeTeamScore());
        self::assertEquals($newAwayTeamScore, $games[$id]->getAwayTeamScore());
    }

    /**
     * @test
     *
     * @throws CountryAlreadyInGameExistsException
     * @throws GameAlreadyExistsException
     * @throws HomeAndAwayTeamIdenticalException
     *
     * @dataProvider invalidAddGamesDataProvider
     */
    public function whenGameCanBeAdded_itThrowsException(string $expectedException, Country $homeTeam, Country $awayTeam): void
    {
        $this->expectException($expectedException);

        $this->scoreboard->addGame($homeTeam, $awayTeam);
    }

    /**
     * @test
     *
     * @throws GameNotFoundException
     */
    public function whenGameCannotBeFinished_itThrowsException(): void
    {
        $this->expectException(GameNotFoundException::class);

        $this->scoreboard->finishGame(Country::CAN, Country::DEU);
    }

    /**
     * @test
     *
     * @throws GameNotFoundException
     * @throws NegativeScoreException
     */
    public function whenGameScoreCannotBeUpdated_itThrowsException(): void
    {
        $this->expectException(GameNotFoundException::class);

        $this->scoreboard->updateGameScore(Country::CAN, Country::DEU, 1, 7);
    }

    public static function invalidAddGamesDataProvider(): Generator
    {
        yield [GameAlreadyExistsException::class, Country::ARG, Country::BRA];
        yield [CountryAlreadyInGameExistsException::class, Country::ARG, Country::ECU];
        yield [CountryAlreadyInGameExistsException::class, Country::ECU, Country::BRA];
    }
}