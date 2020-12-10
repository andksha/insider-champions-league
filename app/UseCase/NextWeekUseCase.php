<?php

namespace App\UseCase;

use App\DTO\NextWeekDTO;
use App\DTO\WeekResult;
use App\Exceptions\TeamsNotFoundException;
use App\Model\Season;
use App\Model\Team;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class NextWeekUseCase
{
    /**
     * @param NextWeekDTO $nextWeekDTO
     * @return bool
     * @throws TeamsNotFoundException
     * @throws Throwable
     */
    public function play(NextWeekDTO $nextWeekDTO): WeekResult
    {
        $teams = $this->getTeams($nextWeekDTO);
        $season = Season::getOrCreate($teams);

        $playMatchUseCase = new PlayMatchUseCase();

        /** if season has no matches, play first week */
        if ($season->matches->isEmpty()) {
            $firstWeekMatchesUseCase = new FirstWeekMatchesUseCase($playMatchUseCase);

            $playingMatches = function () use ($firstWeekMatchesUseCase, $season) {
                return $firstWeekMatchesUseCase->play($season);
            };
        } else {
            /** else play next week */
            $nextWeekMatchesUseCase = new NextWeekMatchesUseCase($playMatchUseCase);
            $playingMatches = function () use ($nextWeekMatchesUseCase, $season) {
                return $nextWeekMatchesUseCase->play($season);
            };
        }

        return $this->execute($playingMatches);
    }

    /**
     * @param NextWeekDTO $nextWeekDTO
     * @return Collection
     * @throws TeamsNotFoundException
     */
    private function getTeams(NextWeekDTO $nextWeekDTO): Collection
    {
        $teams = Team::query()->whereIn('id', $nextWeekDTO->getTeamIds())->get();

        if ($teams->count() !== Season::TEAMS_IN_SEASON) {
            $foundIds = $teams->pluck('id')->toArray();
            /** return teams that were not found in database as error */
            throw new TeamsNotFoundException(array_diff($nextWeekDTO->getTeamIds(), $foundIds));
        }

        return $teams;
    }

    /**
     * @param Closure $playingMatches
     * @return WeekResult
     * @throws Throwable
     */
    private function execute(Closure $playingMatches): WeekResult
    {
        DB::beginTransaction();

        try {
            $weekResult = $playingMatches();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return $weekResult;
    }

}