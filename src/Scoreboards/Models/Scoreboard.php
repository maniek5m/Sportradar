<?php

namespace App\Scoreboards\Models;

use App\Countries\Enums\Country;
use App\Countries\Utils\CountryIdGenerator;
use App\Games\Exceptions\CountryAlreadyInGameExistsException;
use App\Games\Exceptions\GameAlreadyExistsException;
use App\Games\Exceptions\GameNotFoundException;
use App\Games\Exceptions\HomeAndAwayTeamIdenticalException;
use App\Games\Exceptions\NegativeScoreException;
use App\Games\Models\Game;
use App\Scoreboards\Models\Interfaces\ScoreboardInterface;

class Scoreboard implements ScoreboardInterface
{
    /** @var Game[] */
    protected array $games = [];
    protected array $countries = [];

    /**
     * @throws GameAlreadyExistsException
     * @throws HomeAndAwayTeamIdenticalException
     * @throws CountryAlreadyInGameExistsException
     */
    public function addGame(Country $homeTeam, Country $awayTeam): void
    {
        $gameId = CountryIdGenerator::generate($homeTeam, $awayTeam);

        if (array_key_exists($gameId, $this->games)) {
            throw new GameAlreadyExistsException($homeTeam, $awayTeam);
        }

        if (array_key_exists($homeTeam->name, $this->countries)) {
            throw new CountryAlreadyInGameExistsException($homeTeam);
        }

        if (array_key_exists($awayTeam->name, $this->countries)) {
            throw new CountryAlreadyInGameExistsException($awayTeam);
        }

        $this->games[$gameId] = new Game($homeTeam, $awayTeam);

        $this->countries[$homeTeam->name] = 1;
        $this->countries[$awayTeam->name] = 1;
    }

    /**
     * @throws GameNotFoundException
     */
    public function finishGame(Country $homeTeam, Country $awayTeam): bool
    {
        $gameId = CountryIdGenerator::generate($homeTeam, $awayTeam);

        if (!array_key_exists($gameId, $this->games)) {
            throw new GameNotFoundException($homeTeam, $awayTeam);
        }

        unset($this->games[$gameId], $this->countries[$homeTeam->name], $this->countries[$awayTeam->name]);

        return true;
    }

    /**
     * @throws GameNotFoundException
     * @throws NegativeScoreException
     */
    public function updateGameScore(Country $homeTeam, Country $awayTeam, int $newHomeTeamScore, int $newAwayTeamScore): bool
    {
        $gameId = CountryIdGenerator::generate($homeTeam, $awayTeam);

        if (!array_key_exists($gameId, $this->games)) {
            throw new GameNotFoundException($homeTeam, $awayTeam);
        }

        $this->games[$gameId]->setScores($newHomeTeamScore, $newAwayTeamScore);

        return true;
    }

    /**
     * @return Game[]
     */
    public function getGames(): array
    {
        return $this->games;
    }

    /**
     * @return Country[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }
}