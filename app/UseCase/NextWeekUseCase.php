<?php

namespace App\UseCase;

use App\DTO\NextWeekDTO;
use App\Exceptions\TeamsNotFoundException;
use App\Model\Season;
use App\Model\Team;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class NextWeekUseCase
{
    /**
     * @param NextWeekDTO $startSeasonDTO
     * @return bool
     * @throws TeamsNotFoundException
     * @throws Throwable
     */
    public function startSeason(NextWeekDTO $startSeasonDTO)
    {
        $season = Season::createForNextWeek();
        $teams = Team::getTeamsForNextWeek($startSeasonDTO);

        return $this->playMatches($season, $teams);
    }

    /**
     * @param Season $season
     * @param Collection $teams
     * @return bool|void
     * @throws Throwable
     */
    private function playMatches(Season $season, Collection $teams): bool
    {
        $seasonMatches = $season->matches()->get();
        $playMatchUseCase = new PlayMatchUseCase();

        if ($seasonMatches->isEmpty()) {
            $startNewSeasonUseCase = new StartNewSeasonUseCase($playMatchUseCase);

            DB::beginTransaction();

            try {
                $result = $startNewSeasonUseCase->startNewSeason($season, $teams);
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

            DB::commit();

            return $result;
        }

        return true;
    }

}