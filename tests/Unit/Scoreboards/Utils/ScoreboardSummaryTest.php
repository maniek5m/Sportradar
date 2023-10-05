<?php

namespace Unit\Scoreboards\Utils;

use App\Countries\Enums\Country;
use App\Games\Comparators\DefaultGameComparator;
use App\Games\Exceptions\CountryAlreadyInGameExistsException;
use App\Games\Exceptions\GameAlreadyExistsException;
use App\Games\Exceptions\GameNotFoundException;
use App\Games\Exceptions\HomeAndAwayTeamIdenticalException;
use App\Games\Exceptions\NegativeScoreException;
use App\Scoreboards\Models\Scoreboard;
use App\Scoreboards\Utils\ScoreboardSummaryGenerator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ScoreboardSummaryTest extends TestCase
{
    private Scoreboard $scoreboard;

    /**
     * @throws CountryAlreadyInGameExistsException
     * @throws GameAlreadyExistsException
     * @throws GameNotFoundException
     * @throws HomeAndAwayTeamIdenticalException
     * @throws NegativeScoreException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->scoreboard = new Scoreboard();
        $this->scoreboard->addGame(Country::MEX, Country::CAN);
        $this->scoreboard->updateGameScore(Country::MEX, Country::CAN, 0, 5);
        $this->scoreboard->addGame(Country::ESP, Country::BRA);
        $this->scoreboard->updateGameScore(Country::ESP, Country::BRA, 10, 2);
        $this->scoreboard->addGame(Country::DEU, Country::FRA);
        $this->scoreboard->updateGameScore(Country::DEU, Country::FRA, 2, 2);
        $this->scoreboard->addGame(Country::URY, Country::ITA);
        $this->scoreboard->updateGameScore(Country::URY, Country::ITA, 6, 6);
        $this->scoreboard->addGame(Country::ARG, Country::AUS);
        $this->scoreboard->updateGameScore(Country::ARG, Country::AUS, 3, 1);
    }

    /**
     * @test
     */
    public function whenDefaultScoreboardSummaryIsPrepared_dataOrderIsValidWithDefaultGameLine(): void
    {
        $scoreboardSummaryGenerator = new ScoreboardSummaryGenerator();
        $summary = $scoreboardSummaryGenerator->summary($this->scoreboard);

        $expectedOrder = ['ITAURY', 'BRAESP', 'CANMEX', 'ARGAUS', 'DEUFRA'];
        self::assertEquals($expectedOrder, array_keys($summary));
        self::assertEquals('Uruguay 6 - Italy 6', $summary['ITAURY']->getLine());
    }

    /**
     * @test
     */
    public function whenScoreboardSummaryWithoutSortingIsPrepared_dataOrderIsValid(): void
    {
        $scoreboardSummaryGenerator = new ScoreboardSummaryGenerator();
        $summary = $scoreboardSummaryGenerator->summary($this->scoreboard, null);

        $expectedOrder = ['CANMEX', 'BRAESP', 'DEUFRA', 'ITAURY', 'ARGAUS'];
        self::assertEquals($expectedOrder, array_keys($summary));
    }

    /**
     * @test
     */
    public function whenInvalidComparatorIsAdded_itThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $scoreboardSummaryGenerator = new ScoreboardSummaryGenerator();
        $scoreboardSummaryGenerator->summary($this->scoreboard, (new stdClass())::class);
    }

    /**
     * @test
     */
    public function whenInvalidGameLineIsAdded_itThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $scoreboardSummaryGenerator = new ScoreboardSummaryGenerator();
        $scoreboardSummaryGenerator->summary($this->scoreboard, DefaultGameComparator::class, (new stdClass())::class);
    }
}