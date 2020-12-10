<?php

namespace App\DTO;

use App\Model\Match;

final class WeekResult
{
    private Match $match1;
    private Match $match2;
    private ?array $predictions;

    public function setMatch1(Match $match1): WeekResult
    {
        $this->match1 = $match1;

        return $this;
    }

    public function setMatch2(Match $match2): WeekResult
    {
        $this->match2 = $match2;

        return $this;
    }

    public function setPredictions(?array $predictions): WeekResult
    {
        $this->predictions = $predictions;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'match1' => [
                'host_name'   => $this->match1->host->name ?? '',
                'host_goals'  => $this->match1->host_goals ?? 0,
                'guest_name'  => $this->match1->guest->name ?? '',
                'guest_goals' => $this->match1->guest_goals ?? 0
            ],
            'match2' => [
                'host_name'   => $this->match2->host->name ?? '',
                'host_goals'  => $this->match2->host_goals ?? 0,
                'guest_name'  => $this->match2->guest->name ?? '',
                'guest_goals' => $this->match2->guest_goals ?? 0
            ],
            'predictions' => $this->predictions,
        ];
    }
}