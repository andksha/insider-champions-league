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
    private Collection $matches;
    private array $playedTeamsIds;

    public function __construct(PlayMatchUseCase $playMatchUseCase)
    {
        $this->playMatchUseCase = $playMatchUseCase;
    }

    /**
     * @param Season $season
     * @return WeekResult
     * @throws \App\Exceptions\InvalidOperationException
     */
    public function play(Season $season): WeekResult
    {
        $this->season = $season;
        $this->teams = $season->teams;
        $this->matches = $season->matches;
        $weekResult = new WeekResult();
        $previousMatch = null;
        $this->playedTeamsIds = [];

        if ($season->matches->count() >= Season::TOTAL_MATCHES) {
            return $weekResult;
        }

        foreach ($this->teams as $key => $team) {
            if (in_array($team->id, $this->playedTeamsIds)) {
                continue;
            }

            if ($previousMatch = $this->getPreviousMatch($team)) {
                $match = $this->playSecondMatch($previousMatch, $team);
            } else {
                $match = $this->playNextMatch($team);
            }

            if (!is_null($match)) {
                array_push($this->playedTeamsIds, $match->host_id, $match->guest_id);
                $weekResult->addMatch($match);
            }
        }

        $season->load('matches', 'teams');

        if ($season->matches->count() >= 8) {
            $weekResult->setPredictions($season->getPredictions());
        }

        return $weekResult->setTeams($season->teams);
    }

    /**
     * @param Team $team
     * @return Match|null
     */
    private function getPreviousMatch(Team $team): ?Match
    {
        /** @var Match $firstMatch */
        $teamMatches = $this->getTeamMatches($team);

        if ($teamMatches->count() % 2 === 0) {
            return null;
        }

        return $teamMatches->sortByDesc('id', SORT_NUMERIC)->first();
    }

    private function getTeamMatches(Team $team): Collection
    {
        return $this->matches->filter(function ($match) use ($team) {
            return (int) $match->host_id === (int) $team->id
                || (int) $match->guest_id === (int) $team->id;
        });
    }

    /**
     * @param Match $previousMatch
     * @param Team $team
     * @return Match|null
     * @throws \App\Exceptions\InvalidOperationException
     */
    private function playSecondMatch(Match $previousMatch, Team $team): ?Match
    {
        if ((int) $previousMatch->host_id === (int) $team->id) {
            $secondTeamId = $previousMatch->guest_id;
            $secondTeam = $this->teams->find($secondTeamId);

            $hostTeam = $secondTeam;
            $guestTeam = $team;
        } else {
            $secondTeamId = $previousMatch->host_id;
            $secondTeam = $this->teams->find($secondTeamId);

            $hostTeam = $team;
            $guestTeam = $secondTeam;
        }

        if (!$secondTeam) {
            return null;
        }

        return $this->playMatchUseCase->setSeasonId($this->season->id)->playMatch($hostTeam, $guestTeam);
    }

    /**
     * @param Team $hostTeam
     * @return Match
     * @throws \App\Exceptions\InvalidOperationException
     */
    public function playNextMatch(Team $hostTeam)
    {
        $playedWith = [];

        /** @var Match $match */
        foreach ($this->getTeamMatches($hostTeam) as $match) {
            $playedWith[abs($match->host_id - $match->guest_id)] = 0;
        }

        /** @var Team $guestTeam */
        foreach ($this->teams as $team) {
            if (
                !isset($playedWith[abs($hostTeam->id - $team->id)])
                && (int) $team->id !== (int) $hostTeam->id
                && !in_array($team->id, $this->playedTeamsIds)
            ) {
                $guestTeam = $team;
            }
        }

        if (!isset($guestTeam)) {
            return null;
        }

        return $this->playMatchUseCase->setSeasonId($this->season->id)->playMatch($hostTeam, $guestTeam);
    }

}