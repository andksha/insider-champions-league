<?php

namespace App\DTO;

use App\Exceptions\DTOException;
use App\Model\Season;

final class NextWeekDTO
{
    private array $teamIds;

    /**
     * StartSeasonDTO constructor.
     * @param $input
     * @throws DTOException
     */
    public function __construct($input)
    {
        if (!isset($input['team_ids']) || count($input['team_ids']) !== Season::TEAMS_IN_SEASON) {
            throw new DTOException(Season::TEAMS_IN_SEASON . ' teams should be selected');
        }

        $this->teamIds = $input['team_ids'];
    }

    public function getTeamIds(): array
    {
        return $this->teamIds;
    }
}