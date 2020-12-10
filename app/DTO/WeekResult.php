<?php

namespace App\DTO;

use App\Model\Match;
use Illuminate\Database\Eloquent\Collection;

final class WeekResult
{
    private array $matches = [];
    private ?array $predictions = [];
    private ?Collection $teams = null;

    public function addMatch(Match $match): WeekResult
    {
        $key = 'match' . (count($this->matches) + 1);

        $this->matches[$key] = [
            'host_name'   => $match->host->name ?? '',
            'host_goals'  => $match->host_goals ?? 0,
            'guest_name'  => $match->guest->name ?? '',
            'guest_goals' => $match->guest_goals ?? 0
        ];

        return $this;
    }

    public function setTeams(Collection $teams): WeekResult
    {
        $this->teams = $teams;

        return $this;
    }

    public function setPredictions(?array $predictions): WeekResult
    {
        $this->predictions = $predictions;

        return $this;
    }

    public function toArray(): array
    {
        $teams = $this->teams ? $this->teams->sortByDesc(function ($a, $b) {
            return ($a->pts ?? 0) <=> ($b->pts ?? 0);
        })->toArray() : [];

        return [
            'matches' => $this->matches,
            'teams' => $teams,
            'predictions' => $this->predictions
        ];
    }
}