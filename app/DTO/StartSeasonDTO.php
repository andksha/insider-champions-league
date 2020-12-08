<?php

namespace App\DTO;

use App\Exceptions\DTOException;

final class StartSeasonDTO
{
    private array $teamIds;

    /**
     * StartSeasonDTO constructor.
     * @param $input
     * @throws DTOException
     */
    public function __construct($input)
    {
        if (!isset($input['team_ids']) || count($input['team_ids']) !== 4) {
            throw new DTOException('4 teams should be selected');
        }

        $this->teamIds = $input['team_ids'];
    }

    public function getTeamIds(): array
    {
        return $this->teamIds;
    }
}