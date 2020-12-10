<?php

namespace App\DTO;

use App\Model\Match;

final class WeekResult
{
    private Match $match1;
    private Match $match2;
    private ?array $predictions;

    public function __construct(Match $match1, Match $match2, ?array $predictions = [])
    {
        $this->match1 = $match1;
        $this->match2 = $match2;
        $this->predictions = $predictions;
    }

    public function toArray(): array
    {
        return [
            'match1' => [
                'host_name'   => $this->match1->host->name,
                'host_goals'  => $this->match1->host_goals,
                'guest_name'  => $this->match1->guest->name,
                'guest_goals' => $this->match1->guest_goals
            ],
            'match2' => [
                'host_name'   => $this->match2->host->name,
                'host_goals'  => $this->match2->host_goals,
                'guest_name'  => $this->match2->guest->name,
                'guest_goals' => $this->match2->guest_goals
            ],
            'predictions' => $this->predictions,
        ];
    }
}