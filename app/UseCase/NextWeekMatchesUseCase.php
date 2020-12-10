<?php

namespace App\UseCase;

use App\DTO\WeekResult;
use App\Model\Match;
use App\Model\Season;
use App\Model\Team;
use Illuminate\Database\Eloquent\Collection;

final class NextWeekMatchesUseCase
{
    private PlayMatchUseCase $playMatchUseCase;
    private Season $season;
    private Collection $teams;

    public function __construct(PlayMatchUseCase $playMatchUseCase)
    {
        $this->playMatchUseCase = $playMatchUseCase;
    }

    public function play(Season $season): WeekResult
    {
        $weekResult = new WeekResult();
        $this->season = $season;
        $secondTeam = null;

        var_dump($season->matches);


        // @TODO: finish second match
//        foreach ($season->teams as $key => $team) {
//            if ($firstMatch === $this->getFirstMatch($firstMatch))
//        }

        return $weekResult;
    }

    private function getForReturnMatch(Collection $matches, Team $team): bool
    {
        $teamMatches = $this->findTeamMatches($matches, $team);


    }

    private function findTeamMatches(Collection $matches, Team $team): Collection
    {
        return $matches->filter(function (Match $match) use ($team) {
            return (int) $match->host_id === (int) $team->id
                || (int) $match->guest_id === (int) $team->id;
        });
    }

    /**
     * @param Collection $matches
     * @param Team $team
     * @return Match|null
     */
    private function getFirstMatch(Collection $matches, Team $team): ?Match
    {
        /** @var Match $firstMatch */
        $firstMatch = null;

        /** @var Match $teamMatch */
        foreach ($matches as $match) {
            if ($firstMatch === null) {
                $firstMatch = $match;
                continue;
            }

            if (
                $firstMatch->host_id === $match->guest_id
                || $firstMatch->guest_id === $match->host_id
            ) {
                $firstMatch = null;
                continue;
            }
        }

       return $firstMatch;
    }

    private function getFromPreviousGame(Collection $teamMatches, Team $team): Team
    {

    }

    private function determineWinner(Season $season)
    {

    }
}