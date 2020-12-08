<?php

namespace App\UseCase;

use App\Model\Season;
use Illuminate\Database\Eloquent\Collection;

final class StartNewSeasonUseCase
{
    private PlayMatchUseCase $playMatchUseCase;

    public function __construct(PlayMatchUseCase $playMatchUseCase)
    {
        $this->playMatchUseCase = $playMatchUseCase;
    }

    /**
     * @param Season $season
     * @param Collection $teams
     * @return bool
     * @throws \App\Exceptions\InvalidOperationException
     */
    public function startNewSeason(Season $season, Collection $teams)
    {
        $teams->shuffle();

        $hostTeam1 = $teams->pop();
        $guestTeam1 = $teams->shift();
        $this->playMatchUseCase->playMatch($season->id, $hostTeam1, $guestTeam1);

        $hostTeam2 = $teams->shift();
        $guestTeam2 = $teams->pop();
        $this->playMatchUseCase->playMatch($season->id, $hostTeam2, $guestTeam2);

        return true;
    }
}