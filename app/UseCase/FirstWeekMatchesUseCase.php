<?php

namespace App\UseCase;

use App\DTO\WeekResult;
use App\Exceptions\InvalidOperationException;
use App\Model\Season;

final class FirstWeekMatchesUseCase
{
    private PlayMatchUseCase $playMatchUseCase;

    public function __construct(PlayMatchUseCase $playMatchUseCase)
    {
        $this->playMatchUseCase = $playMatchUseCase;
    }

    /**
     * @param Season $season
     * @return WeekResult
     * @throws InvalidOperationException
     */
    public function play(Season $season): WeekResult
    {
        $teams = $season->teams;

        if (!$teams || empty($teams)) {
            throw new InvalidOperationException('Failed to get teams from DB');
        }

        $hostTeam1 = $teams->pop();
        $guestTeam1 = $teams->shift();
        $match1 =$this->playMatchUseCase->playMatch($season->id, $hostTeam1, $guestTeam1);

        $hostTeam2 = $teams->shift();
        $guestTeam2 = $teams->pop();
        $match2 = $this->playMatchUseCase->playMatch($season->id, $hostTeam2, $guestTeam2);

        return new WeekResult($match1, $match2);
    }
}