<?php

namespace App\UseCase;

use App\Model\Match;
use App\Model\Team;

final class PlayMatchUseCase
{
    /**
     * @param int $seasonId
     * @param Team $hostTeam
     * @param Team $guestTeam
     * @return Match
     * @throws \App\Exceptions\InvalidOperationException
     */
    public function playMatch(int $seasonId, Team $hostTeam, Team $guestTeam): Match
    {
        $match = new Match();

        return $match->setSeasonId($seasonId)->play($hostTeam, $guestTeam);
    }
}