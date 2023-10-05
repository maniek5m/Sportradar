<?php

namespace App\Games\Models;

abstract class AbstractGameLine
{
    protected string $line;

    abstract public function __construct(Game $game);

    public function getLine(): string
    {
        return $this->line;
    }
}