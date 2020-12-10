<?php

namespace App\UseCase;

use App\Exceptions\InvalidOperationException;
use App\Model\Match;
use App\Model\Team;

final class PlayMatchUseCase
{
    private int $seasonId;

    public function setSeasonId(int $seasonId): PlayMatchUseCase
    {
        $this->seasonId = $seasonId;

        return $this;
    }

    /**
     * @param Team $hostTeam
     * @param Team $guestTeam
     * @return Match
     * @throws InvalidOperationException
     */
    public function playMatch(Team $hostTeam, Team $guestTeam): Match
    {
        if (!$this->seasonId) {
            throw new InvalidOperationException('Season id must be set');
        }

        $match = new Match();

        return $match->setSeasonId($this->seasonId)->play($hostTeam, $guestTeam);
    }
}