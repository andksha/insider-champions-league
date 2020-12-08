<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Season extends Model
{
    protected $table = 'seasons';

    public function matches(): hasMany
    {
        return $this->hasMany(Match::class, 'season_id');
    }

    /**
     * @return Season
     */
    public static function createForNextWeek(): Season
    {
        if (!$season = Season::query()->whereNull('winner_id')->first()) {
            $season = Season::query()->create();
        }

        return $season;
    }
}