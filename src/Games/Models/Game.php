<?php

namespace App\Games\Models;

use App\Countries\Enums\Country;
use App\Countries\Utils\CountryIdGenerator;
use App\Games\Exceptions\HomeAndAwayTeamIdenticalException;
use App\Games\Exceptions\NegativeScoreException;
use DateTimeImmutable;

class Game
{
    protected int $homeTeamScore = 0;
    protected int $awayTeamScore = 0;

    protected readonly string $id;
    protected readonly DateTimeImmutable $createdAt;

    /**
     * @throws HomeAndAwayTeamIdenticalException
     */
    public function __construct(
        protected readonly Country $homeTeam,
        protected readonly Country $awayTeam,
    )
    {
        if ($this->homeTeam === $this->awayTeam) {
            throw new HomeAndAwayTeamIdenticalException($this->homeTeam, $this->awayTeam);
        }

        $this->createdAt = new DateTimeImmutable();
        $this->id = CountryIdGenerator::generate($homeTeam, $awayTeam);
    }

    /**
     * @throws NegativeScoreException
     */
    public function setScores(int $homeTeamScore, int $awayTeamScore): void
    {
        if ($homeTeamScore < 0) {
            throw new NegativeScoreException($homeTeamScore);
        }

        if ($awayTeamScore < 0) {
            throw new NegativeScoreException($homeTeamScore);
        }

        $this->homeTeamScore = $homeTeamScore;
        $this->awayTeamScore = $awayTeamScore;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHomeTeamScore(): int
    {
        return $this->homeTeamScore;
    }

    public function getAwayTeamScore(): int
    {
        return $this->awayTeamScore;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getHomeTeam(): Country
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): Country
    {
        return $this->awayTeam;
    }

    public function calculateSumScores(): int
    {
        return $this->homeTeamScore + $this->awayTeamScore;
    }
}